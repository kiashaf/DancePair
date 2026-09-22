<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Student;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Notifications\StudentPaymentConfirmedNotification;
use App\Notifications\TeacherPaymentReceivedNotification;

class StudentPaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PAYMENT HISTORY
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $student = Student::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $payments = Payment::with([
            'booking.teacher.user',
            'booking.danceStyle',
        ])
            ->where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        return view(
            'student.payments.index',
            compact('payments')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT PAGE
    |--------------------------------------------------------------------------
    */

    public function show(Booking $booking)
    {
        $student = Student::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $booking->student_id === (int) $student->id,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | BOOKING MUST BE ACCEPTED
        |--------------------------------------------------------------------------
        */

        if ($booking->status !== 'confirmed') {
            return redirect()
                ->route('student.bookings')
                ->with(
                    'error',
                    'This lesson must be accepted by the teacher before payment.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | ALREADY PAID
        |--------------------------------------------------------------------------
        */

        if ($booking->paid) {
            return redirect()
                ->route('student.payments.index')
                ->with(
                    'error',
                    'This lesson has already been paid.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | COMMISSION
        |--------------------------------------------------------------------------
        */

        $commissionPercent = (float) Setting::getValue(
            'platform_commission_percent',
            0
        );

        $amount = round(
            (float) $booking->price,
            2
        );

        if ($amount <= 0) {
            return redirect()
                ->route('student.bookings')
                ->with(
                    'error',
                    'The lesson price must be greater than $0.'
                );
        }

        $platformFee = round(
            $amount * ($commissionPercent / 100),
            2
        );

        $teacherAmount = round(
            $amount - $platformFee,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | CREATE / UPDATE PENDING PAYMENT
        |--------------------------------------------------------------------------
        */

        $payment = Payment::updateOrCreate(
            [
                'booking_id' => $booking->id,
            ],
            [
                'student_id' => $student->id,
                'teacher_id' => $booking->teacher_id,

                'amount' => $amount,

                'platform_fee' => $platformFee,

                'teacher_amount' => $teacherAmount,

                'currency' => 'CAD',

                'status' => 'pending',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | LOAD BOOKING INFORMATION
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'teacher.user',
            'teacher.danceStyles',
            'danceStyle',
        ]);

        /*
        |--------------------------------------------------------------------------
        | GET HOURLY RATE
        |--------------------------------------------------------------------------
        */

        $danceStyle = $booking->teacher
            ?->danceStyles
            ?->firstWhere(
                'id',
                $booking->dance_style_id
            );

        $hourlyRate = (float) (
            $danceStyle?->pivot?->hourly_rate
            ?? 0
        );

        return view(
            'student.payments.show',
            compact(
                'booking',
                'payment',
                'hourlyRate'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STRIPE CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function checkout(
        Request $request,
        Booking $booking
    )
    {
        $student = Student::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $booking->student_id === (int) $student->id,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | CANCELLATION & REFUND POLICY ACCEPTANCE
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'cancellation_policy' => [
                    'required',
                    'accepted',
                ],
            ],
            [
                'cancellation_policy.required' =>
                    app()->getLocale() === 'fr'
                        ? 'Vous devez accepter la politique d’annulation et de remboursement avant de continuer vers le paiement.'
                        : 'You must accept the Cancellation & Refund Policy before continuing to payment.',

                'cancellation_policy.accepted' =>
                    app()->getLocale() === 'fr'
                        ? 'Vous devez accepter la politique d’annulation et de remboursement avant de continuer vers le paiement.'
                        : 'You must accept the Cancellation & Refund Policy before continuing to payment.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | BOOKING MUST BE CONFIRMED
        |--------------------------------------------------------------------------
        */

        if ($booking->status !== 'confirmed') {
            return back()->with(
                'error',
                'This lesson must be accepted before payment.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ALREADY PAID
        |--------------------------------------------------------------------------
        */

        if ($booking->paid) {
            return redirect()
                ->route('student.payments.index')
                ->with(
                    'error',
                    'This lesson has already been paid.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD RELATIONS
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'teacher.user',
            'danceStyle',
        ]);

        /*
        |--------------------------------------------------------------------------
        | GET PAYMENT
        |--------------------------------------------------------------------------
        */

        $payment = Payment::where(
            'booking_id',
            $booking->id
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | SAVE CANCELLATION POLICY ACCEPTANCE
        |--------------------------------------------------------------------------
        */

        if (!$payment->cancellation_policy_accepted_at) {

            $payment->update([
                'cancellation_policy_accepted_at' => now(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | TEACHER STRIPE ACCOUNT
        |--------------------------------------------------------------------------
        */

        if (
            !$booking->teacher?->stripe_account_id
            ||
            !$booking->teacher?->stripe_payouts_enabled
        ) {
            return back()->with(
                'error',
                'This teacher is not ready to receive payments yet.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT INTENT DATA
        |--------------------------------------------------------------------------
        */

        $platformFeeCents = (int) round(
            ((float) $payment->platform_fee) * 100
        );

        $paymentIntentData = [

            'transfer_data' => [
                'destination' =>
                    $booking->teacher->stripe_account_id,
            ],

            'metadata' => [
                'booking_id' =>
                    (string) $booking->id,

                'payment_id' =>
                    (string) $payment->id,

                'student_id' =>
                    (string) $student->id,

                'teacher_id' =>
                    (string) $booking->teacher_id,
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | DANCEPAIR COMMISSION
        |--------------------------------------------------------------------------
        */

        if ($platformFeeCents > 0) {

            $paymentIntentData['application_fee_amount'] =
                $platformFeeCents;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATE AMOUNT
        |--------------------------------------------------------------------------
        */

        if ((float) $payment->amount <= 0) {
            return back()->with(
                'error',
                'The payment amount must be greater than $0.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STRIPE SECRET KEY
        |--------------------------------------------------------------------------
        */

        Stripe::setApiKey(
            env('STRIPE_SECRET')
        );

        /*
        |--------------------------------------------------------------------------
        | LESSON TIME
        |--------------------------------------------------------------------------
        */

        $startTime = \Carbon\Carbon::parse(
            $booking->lesson_time
        );

        $endTime = $startTime
            ->copy()
            ->addMinutes(
                (int) ($booking->duration ?? 60)
            );

        /*
        |--------------------------------------------------------------------------
        | CREATE STRIPE CHECKOUT SESSION
        |--------------------------------------------------------------------------
        |
        | Managed Payments is disabled for this Checkout Session.
        |
        | This lets us use normal Stripe Checkout for the current
        | DansePair payment flow without requiring a Managed Payments
        | product tax code.
        |
        */

        $session = Session::create([

            'mode' => 'payment',

            /*
            |--------------------------------------------------------------------------
            | DISABLE MANAGED PAYMENTS FOR THIS SESSION
            |--------------------------------------------------------------------------
            */

            'managed_payments' => [
                'enabled' => false,
            ],

            /*
            |--------------------------------------------------------------------------
            | STRIPE CONNECT - DESTINATION CHARGE
            |--------------------------------------------------------------------------
            */

            'payment_intent_data' => $paymentIntentData,

            /*
            |--------------------------------------------------------------------------
            | LINE ITEMS
            |--------------------------------------------------------------------------
            */

            'line_items' => [
                [
                    'price_data' => [

                        'currency' => strtolower(
                            $payment->currency ?? 'CAD'
                        ),

                        'unit_amount' => (int) round(
                            ((float) $payment->amount) * 100
                        ),

                        'product_data' => [

                            'name' =>
                                ($booking->danceStyle->name ?? 'Dance Lesson')
                                . ' with '
                                . ($booking->teacher->user->name ?? 'Teacher'),

                            'description' =>
                                \Carbon\Carbon::parse(
                                    $booking->lesson_date
                                )->format('M d, Y')
                                . ' • '
                                . $startTime->format('g:i A')
                                . ' - '
                                . $endTime->format('g:i A'),
                        ],
                    ],

                    'quantity' => 1,
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | METADATA
            |--------------------------------------------------------------------------
            */

            'metadata' => [

                'booking_id' =>
                    (string) $booking->id,

                'payment_id' =>
                    (string) $payment->id,

                'student_id' =>
                    (string) $student->id,

                'teacher_id' =>
                    (string) $booking->teacher_id,
            ],

            /*
            |--------------------------------------------------------------------------
            | SUCCESS URL
            |--------------------------------------------------------------------------
            */

            'success_url' =>
                route(
                    'student.payments.success',
                    $booking
                )
                . '?session_id={CHECKOUT_SESSION_ID}',

            /*
            |--------------------------------------------------------------------------
            | CANCEL URL
            |--------------------------------------------------------------------------
            */

            'cancel_url' =>
                route(
                    'student.payments.show',
                    $booking
                ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | SAVE STRIPE AS PAYMENT PROVIDER
        |--------------------------------------------------------------------------
        */

        $payment->update([
            'payment_provider' => 'stripe',
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO STRIPE CHECKOUT
        |--------------------------------------------------------------------------
        */

        return redirect()->away(
            $session->url
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STRIPE PAYMENT SUCCESS
    |--------------------------------------------------------------------------
    */

    public function success(Booking $booking)
    {
        $student = Student::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $booking->student_id === (int) $student->id,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | GET STRIPE SESSION ID
        |--------------------------------------------------------------------------
        */

        $sessionId = request(
            'session_id'
        );

        if (!$sessionId) {
            return redirect()
                ->route('student.bookings')
                ->with(
                    'error',
                    'Invalid payment session.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | STRIPE
        |--------------------------------------------------------------------------
        */

        Stripe::setApiKey(
            env('STRIPE_SECRET')
        );

        $session = Session::retrieve(
            $sessionId
        );

        /*
        |--------------------------------------------------------------------------
        | VERIFY PAYMENT
        |--------------------------------------------------------------------------
        */

        if ($session->payment_status !== 'paid') {
            return redirect()
                ->route('student.bookings')
                ->with(
                    'error',
                    'Payment was not completed.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFY BOOKING ID
        |--------------------------------------------------------------------------
        */

        $stripeBookingId = (int) (
            $session->metadata->booking_id
            ?? 0
        );

        if (
            $stripeBookingId
            !==
            (int) $booking->id
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFY STUDENT ID
        |--------------------------------------------------------------------------
        */

        $stripeStudentId = (int) (
            $session->metadata->student_id
            ?? 0
        );

        if (
            $stripeStudentId
            !==
            (int) $student->id
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | GET PAYMENT
        |--------------------------------------------------------------------------
        */

        $payment = Payment::where(
            'booking_id',
            $booking->id
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | PAYMENT ALREADY PROCESSED
        |--------------------------------------------------------------------------
        */

        if (
            $payment->status === 'paid'
            &&
            $booking->paid
        ) {
            return redirect()
                ->route('student.bookings')
                ->with(
                    'success',
                    'This payment has already been completed.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | STRIPE TRANSACTION ID
        |--------------------------------------------------------------------------
        */

        $transactionId =
            $session->payment_intent
            ?: $session->id;

        /*
        |--------------------------------------------------------------------------
        | UPDATE PAYMENT
        |--------------------------------------------------------------------------
        */

        $payment->update([

            'status' => 'paid',

            'payment_provider' => 'stripe',

            'transaction_id' =>
                $transactionId,

            'paid_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | MARK BOOKING AS PAID
        |--------------------------------------------------------------------------
        */

        $booking->update([
            'paid' => true,
        ]);

        $booking->load([
            'student.user',
            'teacher.user',
            'danceStyle',
        ]);

        if ($booking->student?->user) {
            $booking->student->user->notify(
                new StudentPaymentConfirmedNotification(
                    $booking,
                    $payment
                )
            );
        }

        if ($booking->teacher?->user) {
            $booking->teacher->user->notify(
                new TeacherPaymentReceivedNotification(
                    $booking,
                    $payment
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('student.bookings')
            ->with(
                'success',
                'Payment completed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT RECEIPT
    |--------------------------------------------------------------------------
    */

    public function receipt(Payment $payment)
    {
        $student = Student::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $payment->student_id === (int) $student->id,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | RECEIPT ONLY FOR PAID PAYMENTS
        |--------------------------------------------------------------------------
        */

        if ($payment->status !== 'paid') {
            return redirect()
                ->route('student.payments.index')
                ->with(
                    'error',
                    'A receipt is available only for completed payments.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD RECEIPT INFORMATION
        |--------------------------------------------------------------------------
        */

        $payment->load([
            'booking.teacher.user',
            'booking.danceStyle',
        ]);

        return view(
            'student.payments.receipt',
            compact(
                'payment'
            )
        );
    }
}