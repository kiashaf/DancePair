<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;

use App\Notifications\StudentPaymentConfirmedNotification;
use App\Notifications\TeacherPaymentReceivedNotification;

use App\Services\BookingActivityNotifier;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();

        $signature = $request->header(
            'Stripe-Signature'
        );

        $webhookSecret = env(
            'STRIPE_WEBHOOK_SECRET'
        );

        try {

            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );

        } catch (\UnexpectedValueException $e) {

            Log::warning(
                'Stripe webhook: invalid payload.'
            );

            return response(
                'Invalid payload',
                400
            );

        } catch (SignatureVerificationException $e) {

            Log::warning(
                'Stripe webhook: invalid signature.'
            );

            return response(
                'Invalid signature',
                400
            );
        }


        switch ($event->type) {

            /*
            |--------------------------------------------------------------------------
            | CHECKOUT PAYMENT COMPLETED
            |--------------------------------------------------------------------------
            */

            case 'checkout.session.completed':

                $this->handleCheckoutSession(
                    $event->data->object
                );

                break;


            /*
            |--------------------------------------------------------------------------
            | ASYNC PAYMENT SUCCEEDED
            |--------------------------------------------------------------------------
            */

            case 'checkout.session.async_payment_succeeded':

                $this->handleCheckoutSession(
                    $event->data->object
                );

                break;


            /*
            |--------------------------------------------------------------------------
            | ASYNC PAYMENT FAILED
            |--------------------------------------------------------------------------
            */

            case 'checkout.session.async_payment_failed':

                $this->handleFailedSession(
                    $event->data->object
                );

                break;


            /*
            |--------------------------------------------------------------------------
            | REFUND UPDATED
            |--------------------------------------------------------------------------
            |
            | Important for refunds that initially returned:
            |
            | pending
            | requires_action
            |
            | and later become:
            |
            | succeeded
            | failed
            | canceled
            |
            */

            case 'refund.updated':

                $this->handleRefundUpdated(
                    $event->data->object
                );

                break;


            /*
            |--------------------------------------------------------------------------
            | REFUND FAILED
            |--------------------------------------------------------------------------
            */

            case 'refund.failed':

                $this->handleRefundFailed(
                    $event->data->object
                );

                break;
        }


        return response(
            'Webhook received',
            200
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HANDLE SUCCESSFUL CHECKOUT SESSION
    |--------------------------------------------------------------------------
    */

    private function handleCheckoutSession(
        $session
    ): void
    {
        if (
            ($session->payment_status ?? null)
            !== 'paid'
        ) {
            return;
        }


        $paymentId = (int) (
            $session->metadata->payment_id
            ?? 0
        );

        $bookingId = (int) (
            $session->metadata->booking_id
            ?? 0
        );

        $studentId = (int) (
            $session->metadata->student_id
            ?? 0
        );

        $teacherId = (int) (
            $session->metadata->teacher_id
            ?? 0
        );


        if (
            !$paymentId
            ||
            !$bookingId
            ||
            !$studentId
            ||
            !$teacherId
        ) {

            Log::warning(
                'Stripe webhook missing metadata.'
            );

            return;
        }


        $transactionId =
            $session->payment_intent
            ?? $session->id;


        $shouldNotify = false;


        DB::transaction(function () use (
            $paymentId,
            $bookingId,
            $studentId,
            $teacherId,
            $transactionId,
            &$shouldNotify
        ) {

            $payment = Payment::where(
                'id',
                $paymentId
            )
                ->lockForUpdate()
                ->first();


            if (!$payment) {
                return;
            }


            if (
                (int) $payment->booking_id
                    !== $bookingId
                ||
                (int) $payment->student_id
                    !== $studentId
                ||
                (int) $payment->teacher_id
                    !== $teacherId
            ) {

                Log::error(
                    'Stripe webhook metadata mismatch.'
                );

                return;
            }


            $booking = Booking::where(
                'id',
                $bookingId
            )
                ->lockForUpdate()
                ->first();


            if (!$booking) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | ALREADY PROCESSED
            |--------------------------------------------------------------------------
            */

            if (
                $payment->status === 'paid'
                &&
                $booking->paid
            ) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | PAYMENT PAID
            |--------------------------------------------------------------------------
            */

            $payment->update([
                'status' => 'paid',

                'payment_provider' =>
                    'stripe',

                'transaction_id' =>
                    $transactionId,

                'paid_at' =>
                    $payment->paid_at
                    ?? now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | BOOKING PAID
            |--------------------------------------------------------------------------
            */

            $booking->update([
                'paid' => true,
            ]);


            $shouldNotify = true;
        });


        if (!$shouldNotify) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        try {

            $payment = Payment::with([
                'booking.student.user',
                'booking.teacher.user',
                'booking.danceStyle',
            ])->find($paymentId);


            $booking =
                $payment?->booking;


            if ($booking?->student?->user) {

                $booking
                    ->student
                    ->user
                    ->notify(
                        new StudentPaymentConfirmedNotification(
                            $booking,
                            $payment
                        )
                    );
            }


            if ($booking?->teacher?->user) {

                $booking
                    ->teacher
                    ->user
                    ->notify(
                        new TeacherPaymentReceivedNotification(
                            $booking,
                            $payment
                        )
                    );
            }

        } catch (\Throwable $e) {

            Log::error(
                'Stripe payment notification failed.',
                [
                    'payment_id' =>
                        $paymentId,

                    'error' =>
                        $e->getMessage(),
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HANDLE FAILED CHECKOUT SESSION
    |--------------------------------------------------------------------------
    */

    private function handleFailedSession(
        $session
    ): void
    {
        $paymentId = (int) (
            $session->metadata->payment_id
            ?? 0
        );


        if (!$paymentId) {
            return;
        }


        Payment::where(
            'id',
            $paymentId
        )
            ->where(
                'status',
                'pending'
            )
            ->update([
                'status' => 'failed',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | HANDLE REFUND UPDATED
    |--------------------------------------------------------------------------
    |
    | This handles a refund that was previously pending
    | and later changes status at Stripe.
    |
    */

    private function handleRefundUpdated(
        $refund
    ): void
    {
        $status =
            (string) (
                $refund->status
                ?? ''
            );


        /*
        |--------------------------------------------------------------------------
        | STILL PROCESSING
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $status,
                [
                    'pending',
                    'requires_action',
                ],
                true
            )
        ) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | REFUND SUCCEEDED
        |--------------------------------------------------------------------------
        */

        if (
            $status ===
            'succeeded'
        ) {

            $this->handleSuccessfulRefund(
                $refund
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | REFUND FAILED / CANCELED
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $status,
                [
                    'failed',
                    'canceled',
                ],
                true
            )
        ) {

            $this->handleRefundFailed(
                $refund
            );

            return;
        }


        Log::warning(
            'Stripe webhook: unknown refund status.',
            [
                'refund_id' =>
                    $refund->id
                    ?? null,

                'status' =>
                    $status,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HANDLE SUCCESSFUL REFUND
    |--------------------------------------------------------------------------
    |
    | This is primarily for:
    |
    | refund_pending
    |       ↓
    | succeeded
    |
    | It is safe against duplicate webhook deliveries because
    | we only send the final notification when the payment
    | transitions to refunded.
    |
    */

    private function handleSuccessfulRefund(
        $refund
    ): void
    {
        $metadata =
            $refund->metadata
            ?? null;


        $paymentId =
            (int) (
                $metadata->payment_id
                ?? 0
            );


        $bookingId =
            (int) (
                $metadata->booking_id
                ?? 0
            );


        /*
        |--------------------------------------------------------------------------
        | REFUNDS CREATED BY DANCEPAIR ALWAYS HAVE PAYMENT ID
        |--------------------------------------------------------------------------
        */

        if (!$paymentId) {

            Log::warning(
                'Stripe refund webhook missing payment_id metadata.',
                [
                    'refund_id' =>
                        $refund->id
                        ?? null,
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DETERMINE WHO STARTED THE REFUND
        |--------------------------------------------------------------------------
        */

        $cancelledBy =
            (string) (
                $metadata->cancelled_by
                ?? ''
            );


        $refundedBy =
            (string) (
                $metadata->refunded_by
                ?? ''
            );


        $action =
            'admin_refunded';


        $actorRole =
            'admin';


        if (
            $cancelledBy ===
            'student'
        ) {

            $action =
                'student_cancelled_refunded';

            $actorRole =
                'student';

        } elseif (
            $cancelledBy ===
            'teacher'
        ) {

            $action =
                'teacher_cancelled_refunded';

            $actorRole =
                'teacher';

        } elseif (
            $refundedBy ===
            'admin'
        ) {

            $action =
                'admin_refunded';

            $actorRole =
                'admin';
        }


        $shouldNotify =
            false;


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATABASE
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $paymentId,
                $bookingId,
                &$shouldNotify
            ) {

                /*
                |--------------------------------------------------------------------------
                | LOCK PAYMENT
                |--------------------------------------------------------------------------
                */

                $payment = Payment::where(
                    'id',
                    $paymentId
                )
                    ->lockForUpdate()
                    ->first();


                if (!$payment) {

                    Log::warning(
                        'Stripe refund webhook payment not found.',
                        [
                            'payment_id' =>
                                $paymentId,
                        ]
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | VERIFY BOOKING ID IF PROVIDED
                |--------------------------------------------------------------------------
                */

                if (
                    $bookingId
                    &&
                    (int) $payment->booking_id
                    !==
                    $bookingId
                ) {

                    Log::error(
                        'Stripe refund webhook booking metadata mismatch.',
                        [
                            'payment_id' =>
                                $paymentId,

                            'metadata_booking_id' =>
                                $bookingId,

                            'payment_booking_id' =>
                                $payment->booking_id,
                        ]
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | BOOKING
                |--------------------------------------------------------------------------
                */

                $booking = Booking::where(
                    'id',
                    $payment->booking_id
                )
                    ->lockForUpdate()
                    ->first();


                if (!$booking) {

                    Log::warning(
                        'Stripe refund webhook booking not found.',
                        [
                            'payment_id' =>
                                $paymentId,

                            'booking_id' =>
                                $payment->booking_id,
                        ]
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | ALREADY PROCESSED
                |--------------------------------------------------------------------------
                |
                | Stripe can retry webhook events.
                |
                | We do not want duplicate emails / notifications.
                |
                */

                if (
                    $payment->status ===
                    'refunded'
                    &&
                    $payment->refunded_at
                ) {

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | PAYMENT REFUNDED
                |--------------------------------------------------------------------------
                */

                $payment->update([

                    'status' =>
                        'refunded',

                    'refunded_at' =>
                        $payment->refunded_at
                        ?? now(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | BOOKING
                |--------------------------------------------------------------------------
                */

                $booking->update([

                    'status' =>
                        'cancelled',

                    'paid' =>
                        false,
                ]);


                $shouldNotify =
                    true;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NOTHING CHANGED
        |--------------------------------------------------------------------------
        */

        if (!$shouldNotify) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | FINAL REFUND NOTIFICATION
        |--------------------------------------------------------------------------
        */

        try {

            $payment = Payment::with([
                'booking.student.user',
                'booking.teacher.user',
                'booking.danceStyle',
            ])->find(
                $paymentId
            );


            $booking =
                $payment?->booking;


            if (!$booking) {

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | ACTOR NAME
            |--------------------------------------------------------------------------
            */

            $actorName =
                'DancePair Support';


            if (
                $actorRole ===
                'student'
            ) {

                $actorName =
                    $booking
                        ->student
                        ?->user
                        ?->name
                    ??
                    'Student';
            }


            if (
                $actorRole ===
                'teacher'
            ) {

                $actorName =
                    $booking
                        ->teacher
                        ?->user
                        ?->name
                    ??
                    'Teacher';
            }


            /*
            |--------------------------------------------------------------------------
            | STUDENT + TEACHER
            |--------------------------------------------------------------------------
            |
            | Sends:
            |
            | - Email
            | - Database notification
            |
            */

            app(
                BookingActivityNotifier::class
            )->notifyBoth(
                booking: $booking,
                action: $action,
                actorRole: $actorRole,
                actorName: $actorName,
                payment: $payment
            );


        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | DO NOT FAIL STRIPE WEBHOOK BECAUSE EMAIL FAILED
            |--------------------------------------------------------------------------
            */

            Log::error(
                'Stripe refund completion notification failed.',
                [
                    'payment_id' =>
                        $paymentId,

                    'refund_id' =>
                        $refund->id
                        ?? null,

                    'error' =>
                        $e->getMessage(),
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HANDLE REFUND FAILED
    |--------------------------------------------------------------------------
    |
    | If a pending Stripe refund eventually fails:
    |
    | - Student was NOT refunded.
    | - Payment therefore becomes PAID again.
    | - refunded_at stays NULL.
    | - Booking stays cancelled because the cancellation itself
    |   already happened.
    |
    | A dedicated refund-failed email/notification can be added
    | separately after this step.
    |
    */

    private function handleRefundFailed(
        $refund
    ): void
    {
        $metadata =
            $refund->metadata
            ?? null;


        $paymentId =
            (int) (
                $metadata->payment_id
                ?? 0
            );


        if (!$paymentId) {

            Log::warning(
                'Stripe failed refund webhook missing payment_id metadata.',
                [
                    'refund_id' =>
                        $refund->id
                        ?? null,
                ]
            );

            return;
        }


        DB::transaction(
            function () use (
                $paymentId,
                $refund
            ) {

                $payment = Payment::where(
                    'id',
                    $paymentId
                )
                    ->lockForUpdate()
                    ->first();


                if (!$payment) {

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | ONLY REVERT REFUND_PENDING
                |--------------------------------------------------------------------------
                */

                if (
                    $payment->status !==
                    'refund_pending'
                ) {

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | MONEY WAS NOT REFUNDED
                |--------------------------------------------------------------------------
                */

                $payment->update([

                    'status' =>
                        'paid',

                    'refunded_at' =>
                        null,
                ]);


                Log::error(
                    'Stripe refund failed.',
                    [
                        'payment_id' =>
                            $paymentId,

                        'refund_id' =>
                            $refund->id
                            ?? null,

                        'failure_reason' =>
                            $refund->failure_reason
                            ?? null,
                    ]
                );
            }
        );
    }
}