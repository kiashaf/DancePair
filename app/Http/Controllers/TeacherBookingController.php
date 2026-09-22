<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Booking;

use App\Services\BookingActivityNotifier;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Refund;
use Stripe\Stripe;

class TeacherBookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TEACHER BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        $bookings = Booking::with([
            'student.user',
            'danceStyle',
            'teacherReview',
            'messages.sender',
            'payment',
        ])
            ->where(
                'teacher_id',
                $teacher->id
            )

            /*
            |--------------------------------------------------------------------------
            | ONLY REAL STUDENT REQUESTS
            |--------------------------------------------------------------------------
            */

            ->whereHas(
                'student.user',
                function ($query) {

                    $query->where(
                        'role',
                        'student'
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | ORDER
            |--------------------------------------------------------------------------
            */

            ->orderBy(
                'lesson_date',
                'desc'
            )

            ->orderBy(
                'lesson_time',
                'desc'
            )

            ->get();


        return view(
            'teacher.bookings',
            compact('bookings')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCEPT BOOKING
    |--------------------------------------------------------------------------
    */

    public function accept(
        Booking $booking
    ) {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $booking->teacher_id
            ===
            (int) $teacher->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | ONLY PENDING CAN BE ACCEPTED
        |--------------------------------------------------------------------------
        */

        if (
            $booking->status !== 'pending'
        ) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Cette demande ne peut plus être acceptée.'
                    : 'This request can no longer be accepted.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD RELATIONS
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'student.user',
            'teacher.user',
            'danceStyle',
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        */

        $booking->update([
            'status' =>
                'confirmed',
        ]);


        /*
        |--------------------------------------------------------------------------
        | NOTIFY STUDENT + TEACHER
        |--------------------------------------------------------------------------
        |
        | Both receive:
        |
        | - Email from DancePair Support
        | - Notification inside DancePair account
        |
        */

        app(BookingActivityNotifier::class)
            ->notifyBoth(
                booking: $booking,
                action: 'request_accepted',
                actorRole: 'teacher',
                actorName: Auth::user()?->name
            );


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            app()->getLocale() === 'fr'
                ? 'La demande de cours a été acceptée avec succès.'
                : 'Lesson request accepted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT PENDING BOOKING
    |--------------------------------------------------------------------------
    */

    public function reject(
        Booking $booking
    ) {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $booking->teacher_id
            ===
            (int) $teacher->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | ONLY PENDING CAN BE REJECTED
        |--------------------------------------------------------------------------
        */

        if (
            $booking->status !== 'pending'
        ) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Cette demande ne peut plus être refusée.'
                    : 'This request can no longer be refused.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD RELATIONS
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'student.user',
            'teacher.user',
            'danceStyle',
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        */

        $booking->update([
            'status' =>
                'cancelled',
        ]);


        /*
        |--------------------------------------------------------------------------
        | NOTIFY STUDENT + TEACHER
        |--------------------------------------------------------------------------
        */

        app(BookingActivityNotifier::class)
            ->notifyBoth(
                booking: $booking,
                action: 'request_rejected',
                actorRole: 'teacher',
                actorName: Auth::user()?->name
            );


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            app()->getLocale() === 'fr'
                ? 'La demande de cours a été refusée.'
                : 'Lesson request refused.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL CONFIRMED LESSON
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | When the TEACHER cancels a confirmed lesson:
    |
    | - There is NO 2h / 6h / 24h restriction.
    | - If the student paid, the student receives 100% refund.
    | - Teacher transfer is reversed.
    | - DancePair application fee is refunded.
    |
    */

    public function cancel(
        Booking $booking
    ) {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $booking->teacher_id
            ===
            (int) $teacher->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | LOAD PAYMENT + USERS
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'payment',
            'student.user',
            'teacher.user',
            'danceStyle',
        ]);


        $payment =
            $booking->payment;


        /*
        |--------------------------------------------------------------------------
        | ALREADY CANCELLED
        |--------------------------------------------------------------------------
        */

        if (
            $booking->status ===
            'cancelled'
        ) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Ce cours est déjà annulé.'
                    : 'This lesson is already cancelled.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ONLY CONFIRMED LESSON CAN BE CANCELLED
        |--------------------------------------------------------------------------
        */

        if (
            $booking->status !==
            'confirmed'
        ) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Seul un cours confirmé peut être annulé.'
                    : 'Only a confirmed lesson can be cancelled.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK PAYMENT STATE
        |--------------------------------------------------------------------------
        */

        $isPaid =
            (bool) $booking->paid
            ||
            (
                $payment
                &&
                in_array(
                    $payment->status,
                    [
                        'paid',
                        'refund_pending',
                        'refunded',
                    ],
                    true
                )
            );


        /*
        |--------------------------------------------------------------------------
        | UNPAID LESSON
        |--------------------------------------------------------------------------
        |
        | Teacher can cancel immediately.
        | No Stripe refund is required.
        |
        */

        if (!$isPaid) {

            DB::transaction(
                function () use (
                    $booking,
                    $payment
                ) {

                    if (
                        $payment
                        &&
                        $payment->status ===
                        'pending'
                    ) {

                        $payment->update([
                            'status' =>
                                'cancelled',
                        ]);
                    }


                    $booking->update([

                        'status' =>
                            'cancelled',

                        'paid' =>
                            false,
                    ]);
                }
            );


            /*
            |--------------------------------------------------------------------------
            | NOTIFY STUDENT + TEACHER
            |--------------------------------------------------------------------------
            */

            app(BookingActivityNotifier::class)
                ->notifyBoth(
                    booking: $booking,
                    action: 'teacher_cancelled',
                    actorRole: 'teacher',
                    actorName: Auth::user()?->name,
                    payment: $payment
                );


            return back()->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'Le cours a été annulé. Aucun paiement n’avait été effectué par l’élève.'
                    : 'The lesson has been cancelled. The student had not completed a payment.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAID BOOKING MUST HAVE PAYMENT RECORD
        |--------------------------------------------------------------------------
        */

        if (!$payment) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Impossible de trouver le paiement associé à cette réservation. Veuillez contacter le soutien DancePair.'
                    : 'The payment associated with this booking could not be found. Please contact DancePair Support.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ALREADY REFUNDED
        |--------------------------------------------------------------------------
        */

        if (
            $payment->status ===
            'refunded'
            ||
            $payment->refunded_at
        ) {

            $booking->update([

                'status' =>
                    'cancelled',

                'paid' =>
                    false,
            ]);


            /*
            |--------------------------------------------------------------------------
            | NOTIFY STUDENT + TEACHER
            |--------------------------------------------------------------------------
            */

            app(BookingActivityNotifier::class)
                ->notifyBoth(
                    booking: $booking,
                    action: 'teacher_cancelled_refunded',
                    actorRole: 'teacher',
                    actorName: Auth::user()?->name,
                    payment: $payment
                );


            return back()->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'Le cours a été annulé. Le paiement avait déjà été remboursé intégralement.'
                    : 'The lesson has been cancelled. The payment had already been fully refunded.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REFUND ALREADY PROCESSING
        |--------------------------------------------------------------------------
        */

        if (
            $payment->status ===
            'refund_pending'
        ) {

            $booking->update([
                'status' =>
                    'cancelled',
            ]);


            /*
            |--------------------------------------------------------------------------
            | NOTIFY STUDENT + TEACHER
            |--------------------------------------------------------------------------
            */

            app(BookingActivityNotifier::class)
                ->notifyBoth(
                    booking: $booking,
                    action: 'teacher_cancelled_refund_pending',
                    actorRole: 'teacher',
                    actorName: Auth::user()?->name,
                    payment: $payment
                );


            return back()->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'Le cours a été annulé. Le remboursement complet de l’élève est déjà en cours de traitement.'
                    : 'The lesson has been cancelled. The student’s full refund is already being processed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT MUST BE STRIPE
        |--------------------------------------------------------------------------
        */

        if (
            $payment->payment_provider
            !==
            'stripe'
        ) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Ce paiement ne peut pas être remboursé automatiquement. Veuillez contacter le soutien DancePair.'
                    : 'This payment cannot be refunded automatically. Please contact DancePair Support.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION ID
        |--------------------------------------------------------------------------
        */

        if (
            !$payment->transaction_id
        ) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'La transaction Stripe associée à ce paiement est introuvable. Veuillez contacter le soutien DancePair.'
                    : 'The Stripe transaction associated with this payment could not be found. Please contact DancePair Support.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STRIPE API
        |--------------------------------------------------------------------------
        */

        Stripe::setApiKey(
            env('STRIPE_SECRET')
        );


        try {

            /*
            |--------------------------------------------------------------------------
            | PAYMENT INTENT ID
            |--------------------------------------------------------------------------
            |
            | Current DancePair payments normally save pi_...
            |
            | Older records may contain cs_...
            |
            */

            $paymentIntentId =
                $payment->transaction_id;


            /*
            |--------------------------------------------------------------------------
            | CHECKOUT SESSION FALLBACK
            |--------------------------------------------------------------------------
            */

            if (
                str_starts_with(
                    (string) $paymentIntentId,
                    'cs_'
                )
            ) {

                $stripeSession =
                    Session::retrieve(
                        $paymentIntentId
                    );


                $paymentIntentId =
                    $stripeSession
                        ->payment_intent;
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDATE PAYMENT INTENT
            |--------------------------------------------------------------------------
            */

            if (
                !$paymentIntentId
                ||
                !str_starts_with(
                    (string) $paymentIntentId,
                    'pi_'
                )
            ) {

                return back()->with(
                    'error',
                    app()->getLocale() === 'fr'
                        ? 'La transaction Stripe associée à ce paiement est invalide. Veuillez contacter le soutien DancePair.'
                        : 'The Stripe transaction associated with this payment is invalid. Please contact DancePair Support.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | CREATE FULL STRIPE REFUND
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | Teacher cancellation ALWAYS gives the student a full refund.
            |
            | reverse_transfer = true
            |
            | Pull teacher's destination transfer back.
            |
            | refund_application_fee = true
            |
            | Refund DancePair's application fee too.
            |
            */

            $refund = Refund::create(
                [

                    'payment_intent' =>
                        $paymentIntentId,

                    'reason' =>
                        'requested_by_customer',

                    'reverse_transfer' =>
                        true,

                    'refund_application_fee' =>
                        true,

                    'metadata' => [

                        'booking_id' =>
                            (string) $booking->id,

                        'payment_id' =>
                            (string) $payment->id,

                        'student_id' =>
                            (string) $booking->student_id,

                        'teacher_id' =>
                            (string) $teacher->id,

                        'cancelled_by' =>
                            'teacher',

                        'refund_type' =>
                            'full',

                        'refund_percentage' =>
                            '100',
                    ],
                ],
                [

                    /*
                    |--------------------------------------------------------------------------
                    | IDEMPOTENCY
                    |--------------------------------------------------------------------------
                    |
                    | Prevent duplicate Stripe refunds if teacher
                    | double-clicks or the request is retried.
                    |
                    */

                    'idempotency_key' =>
                        'dancepair_teacher_refund_booking_'
                        . $booking->id
                        . '_payment_'
                        . $payment->id,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | REFUND FAILED / CANCELLED
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $refund->status,
                    [
                        'failed',
                        'canceled',
                    ],
                    true
                )
            ) {

                return back()->with(
                    'error',
                    app()->getLocale() === 'fr'
                        ? 'Stripe n’a pas pu effectuer le remboursement. Le cours n’a pas été annulé. Veuillez réessayer ou contacter le soutien DancePair.'
                        : 'Stripe could not complete the refund. The lesson was not cancelled. Please try again or contact DancePair Support.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | REFUND PENDING / REQUIRES ACTION
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $refund->status,
                    [
                        'pending',
                        'requires_action',
                    ],
                    true
                )
            ) {

                DB::transaction(
                    function () use (
                        $booking,
                        $payment
                    ) {

                        $payment->update([
                            'status' =>
                                'refund_pending',
                        ]);


                        $booking->update([
                            'status' =>
                                'cancelled',
                        ]);
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | NOTIFY STUDENT + TEACHER
                |--------------------------------------------------------------------------
                */

                app(BookingActivityNotifier::class)
                    ->notifyBoth(
                        booking: $booking,
                        action: 'teacher_cancelled_refund_pending',
                        actorRole: 'teacher',
                        actorName: Auth::user()?->name,
                        payment: $payment
                    );


                return back()->with(
                    'success',
                    app()->getLocale() === 'fr'
                        ? 'Le cours a été annulé. Le remboursement complet de 100 % de l’élève est maintenant en cours de traitement vers le mode de paiement d’origine.'
                        : 'The lesson has been cancelled. The student’s 100% full refund is now being processed to the original payment method.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | REFUND SUCCEEDED
            |--------------------------------------------------------------------------
            */

            if (
                $refund->status ===
                'succeeded'
            ) {

                DB::transaction(
                    function () use (
                        $booking,
                        $payment
                    ) {

                        $payment->update([

                            'status' =>
                                'refunded',

                            'refunded_at' =>
                                now(),
                        ]);


                        $booking->update([

                            'status' =>
                                'cancelled',

                            'paid' =>
                                false,
                        ]);
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | NOTIFY STUDENT + TEACHER
                |--------------------------------------------------------------------------
                */

                app(BookingActivityNotifier::class)
                    ->notifyBoth(
                        booking: $booking,
                        action: 'teacher_cancelled_refunded',
                        actorRole: 'teacher',
                        actorName: Auth::user()?->name,
                        payment: $payment
                    );


                return back()->with(
                    'success',
                    app()->getLocale() === 'fr'
                        ? 'Le cours a été annulé et l’élève recevra un remboursement complet de 100 % vers le mode de paiement d’origine.'
                        : 'The lesson has been cancelled and the student will receive a 100% full refund to the original payment method.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | UNKNOWN REFUND STATUS
            |--------------------------------------------------------------------------
            */

            report(
                new \RuntimeException(
                    'Unexpected Stripe refund status: '
                    . (
                        $refund->status
                        ?? 'unknown'
                    )
                )
            );


            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Stripe a retourné un statut de remboursement inattendu. Le cours n’a pas été annulé. Veuillez contacter le soutien DancePair.'
                    : 'Stripe returned an unexpected refund status. The lesson was not cancelled. Please contact DancePair Support.'
            );

        } catch (
            ApiErrorException $exception
        ) {

            report(
                $exception
            );


            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Le remboursement Stripe n’a pas pu être effectué. Le cours n’a pas été annulé. Veuillez réessayer ou contacter le soutien DancePair.'
                    : 'The Stripe refund could not be processed. The lesson was not cancelled. Please try again or contact DancePair Support.'
            );

        } catch (
            \Throwable $exception
        ) {

            report(
                $exception
            );


            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Une erreur est survenue pendant l’annulation. Veuillez réessayer ou contacter le soutien DancePair.'
                    : 'An error occurred while cancelling the lesson. Please try again or contact DancePair Support.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT PROFILE
    |--------------------------------------------------------------------------
    */

    public function studentProfile(
        Booking $booking
    ) {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $booking->teacher_id
            ===
            (int) $teacher->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | MARK REQUEST AS VIEWED
        |--------------------------------------------------------------------------
        */

        if (
            is_null(
                $booking->teacher_viewed_at
            )
        ) {

            $booking->update([
                'teacher_viewed_at' =>
                    now(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD DATA
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'student.user',
            'danceStyle',
        ]);


        $student =
            $booking->student;


        return view(
            'teacher.students.show',
            compact(
                'student',
                'booking'
            )
        );
    }
}