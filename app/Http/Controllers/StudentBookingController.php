<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Student;
use App\Models\TeacherAvailability;

use App\Services\BookingActivityNotifier;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Refund;
use Stripe\Stripe;

class StudentBookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MY BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $student = Student::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | STUDENT BOOKINGS
        |--------------------------------------------------------------------------
        */

        $bookings = Booking::with([
            'teacher.user',
            'danceStyle',
            'reviews',
            'payment',
        ])
            ->where(
                'student_id',
                $student->id
            )
            ->orderBy(
                'lesson_date',
                'desc'
            )
            ->orderBy(
                'lesson_time',
                'desc'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TEACHERS USED IN BOOKINGS
        |--------------------------------------------------------------------------
        */

        $teacherIds = $bookings
            ->pluck('teacher_id')
            ->unique()
            ->filter()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | AVAILABLE TIMES FOR EDITING PENDING REQUESTS
        |--------------------------------------------------------------------------
        */

        $availabilities = TeacherAvailability::whereIn(
                'teacher_id',
                $teacherIds
            )
            ->where(
                'active',
                1
            )
            ->whereDate(
                'available_date',
                '>=',
                today()
            )
            ->orderBy(
                'available_date',
                'asc'
            )
            ->orderBy(
                'start_time',
                'asc'
            )
            ->get()
            ->groupBy(
                'teacher_id'
            );


        return view(
            'student.bookings.index',
            compact(
                'bookings',
                'availabilities'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PENDING BOOKING
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Booking $booking
    ) {
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
            (int) $booking->student_id
            ===
            (int) $student->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | ONLY PENDING REQUEST CAN BE EDITED
        |--------------------------------------------------------------------------
        */

        if ($booking->status !== 'pending') {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Seules les demandes en attente peuvent être modifiées.'
                    : 'Only pending requests can be edited.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'availability_id' => [
                'required',
                'integer',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | FIND AVAILABILITY
        |--------------------------------------------------------------------------
        */

        $availability = TeacherAvailability::where(
                'id',
                $validated['availability_id']
            )
            ->where(
                'teacher_id',
                $booking->teacher_id
            )
            ->where(
                'active',
                1
            )
            ->whereDate(
                'available_date',
                '>=',
                today()
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | DANCE STYLE MUST MATCH
        |--------------------------------------------------------------------------
        */

        if (
            $availability->dance_style_id
            &&
            (int) $availability->dance_style_id
            !==
            (int) $booking->dance_style_id
        ) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Ce créneau n’est pas disponible pour le style de danse sélectionné.'
                    : 'This time slot is not available for the selected dance style.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE BOOKING
        |--------------------------------------------------------------------------
        */

        $alreadyRequested = Booking::where(
                'student_id',
                $student->id
            )
            ->where(
                'teacher_id',
                $booking->teacher_id
            )
            ->where(
                'lesson_date',
                $availability->available_date
            )
            ->where(
                'lesson_time',
                $availability->start_time
            )
            ->where(
                'id',
                '!=',
                $booking->id
            )
            ->whereIn(
                'status',
                [
                    'pending',
                    'confirmed',
                ]
            )
            ->exists();


        if ($alreadyRequested) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Vous avez déjà une demande pour ce créneau.'
                    : 'You already have a request for this class time.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CALCULATE DURATION
        |--------------------------------------------------------------------------
        */

        $start = Carbon::parse(
            $availability->start_time
        );

        $end = Carbon::parse(
            $availability->end_time
        );


        $duration = $start->diffInMinutes(
            $end
        );


        if ($duration <= 0) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'La durée du cours est invalide.'
                    : 'Invalid lesson duration.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GET HOURLY RATE
        |--------------------------------------------------------------------------
        */

        $hourlyRate = DB::table(
                'dance_style_teacher'
            )
            ->where(
                'teacher_id',
                $booking->teacher_id
            )
            ->where(
                'dance_style_id',
                $booking->dance_style_id
            )
            ->value(
                'hourly_rate'
            );


        if ($hourlyRate === null) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Aucun tarif horaire n’est configuré pour ce style de danse.'
                    : 'No hourly rate is configured for this dance style.'
            );
        }


        $hourlyRate = (float) $hourlyRate;


        /*
        |--------------------------------------------------------------------------
        | CALCULATE NEW PRICE
        |--------------------------------------------------------------------------
        */

        $price = round(
            $hourlyRate * ($duration / 60),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE BOOKING
        |--------------------------------------------------------------------------
        */

        $booking->update([

            'lesson_date' =>
                $availability->available_date,

            'lesson_time' =>
                $availability->start_time,

            'duration' =>
                $duration,

            'price' =>
                $price,
        ]);


        /*
        |--------------------------------------------------------------------------
        | REMOVE OLD PENDING PAYMENT
        |--------------------------------------------------------------------------
        |
        | If payment was prepared before the student changed the lesson
        | time, we remove the pending payment so amount/commission will be
        | recalculated correctly.
        |
        */

        $booking->load(
            'payment'
        );

        if (
            $booking->payment
            &&
            $booking->payment->status === 'pending'
        ) {

            $booking->payment->delete();
        }


        return back()->with(
            'success',
            app()->getLocale() === 'fr'
                ? 'Votre demande de réservation a été mise à jour.'
                : 'Your booking request has been updated.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE PENDING BOOKING
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Booking $booking
    ) {
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
            (int) $booking->student_id
            ===
            (int) $student->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | ONLY PENDING REQUEST CAN BE DELETED
        |--------------------------------------------------------------------------
        */

        if ($booking->status !== 'pending') {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Seules les demandes en attente peuvent être supprimées.'
                    : 'Only pending requests can be deleted.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD DATA BEFORE DELETE
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'payment',
            'student.user',
            'teacher.user',
            'danceStyle',
        ]);


        /*
        |--------------------------------------------------------------------------
        | DELETE PENDING PAYMENT IF ONE EXISTS
        |--------------------------------------------------------------------------
        */

        if (
            $booking->payment
            &&
            $booking->payment->status === 'pending'
        ) {

            $booking->payment->delete();
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE BOOKING
        |--------------------------------------------------------------------------
        */

        $booking->delete();


        /*
        |--------------------------------------------------------------------------
        | NOTIFY STUDENT + TEACHER
        |--------------------------------------------------------------------------
        |
        | Email
        | +
        | Database notification
        |
        */

        app(BookingActivityNotifier::class)
            ->notifyBoth(
                booking: $booking,
                action: 'request_withdrawn',
                actorRole: 'student',
                actorName: Auth::user()?->name
            );


        return back()->with(
            'success',
            app()->getLocale() === 'fr'
                ? 'La demande de réservation a été supprimée.'
                : 'Booking request deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL CONFIRMED LESSON
    |--------------------------------------------------------------------------
    |
    | STUDENT CANCELLATION POLICY
    |
    | Online:
    | Full refund when cancelled at least 2 hours before.
    |
    | Face to Face:
    | Full refund when cancelled at least 6 hours before.
    |
    | Public Place:
    | Full refund when cancelled at least 24 hours before.
    |
    | After the deadline:
    | The booking may still be cancelled but there is no automatic refund.
    |
    */

    public function cancel(
        Booking $booking
    ) {
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
            (int) $booking->student_id
            ===
            (int) $student->id,
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

        if ($booking->status === 'cancelled') {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Ce cours est déjà annulé.'
                    : 'This lesson is already cancelled.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ONLY CONFIRMED LESSONS
        |--------------------------------------------------------------------------
        */

        if ($booking->status !== 'confirmed') {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Seul un cours confirmé peut être annulé.'
                    : 'Only a confirmed lesson can be cancelled.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BUILD LESSON START DATETIME
        |--------------------------------------------------------------------------
        */

        $lessonDate = Carbon::parse(
            $booking->lesson_date
        )->format(
            'Y-m-d'
        );


        $lessonTime = Carbon::parse(
            $booking->lesson_time
        )->format(
            'H:i:s'
        );


        $lessonStart = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $lessonDate . ' ' . $lessonTime,
            config('app.timezone')
        );


        /*
        |--------------------------------------------------------------------------
        | LESSON ALREADY STARTED
        |--------------------------------------------------------------------------
        */

        if (
            now()->gte(
                $lessonStart
            )
        ) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Ce cours ne peut plus être annulé puisqu’il a déjà commencé.'
                    : 'This lesson can no longer be cancelled because it has already started.'
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
                        $payment->status === 'pending'
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
            | NOTIFY BOTH
            |--------------------------------------------------------------------------
            */

            app(BookingActivityNotifier::class)
                ->notifyBoth(
                    booking: $booking,
                    action: 'student_cancelled_unpaid',
                    actorRole: 'student',
                    actorName: Auth::user()?->name,
                    payment: $payment
                );


            return back()->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'Le cours a été annulé. Aucun paiement n’avait été effectué.'
                    : 'The lesson has been cancelled. No payment had been completed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAID BOOKING MUST HAVE PAYMENT
        |--------------------------------------------------------------------------
        */

        if (!$payment) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Impossible de trouver le paiement associé à cette réservation. Veuillez contacter le soutien DancePair.'
                    : 'The payment associated with this booking could not be found. Please contact DancePair support.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REFUND ALREADY COMPLETED
        |--------------------------------------------------------------------------
        */

        if (
            $payment->status === 'refunded'
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
            | NOTIFY BOTH
            |--------------------------------------------------------------------------
            */

            app(BookingActivityNotifier::class)
                ->notifyBoth(
                    booking: $booking,
                    action: 'student_cancelled_refunded',
                    actorRole: 'student',
                    actorName: Auth::user()?->name,
                    payment: $payment
                );


            return back()->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'Ce cours est annulé et le paiement a déjà été remboursé.'
                    : 'This lesson is cancelled and the payment has already been refunded.'
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
            | NOTIFY BOTH
            |--------------------------------------------------------------------------
            */

            app(BookingActivityNotifier::class)
                ->notifyBoth(
                    booking: $booking,
                    action: 'student_cancelled_refund_pending',
                    actorRole: 'student',
                    actorName: Auth::user()?->name,
                    payment: $payment
                );


            return back()->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'Le cours est annulé. Votre remboursement est déjà en cours de traitement.'
                    : 'The lesson is cancelled. Your refund is already being processed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DETERMINE CANCELLATION DEADLINE
        |--------------------------------------------------------------------------
        */

        $requiredHours = match (
            $booking->teaching_type
        ) {

            'online' =>
                2,

            'face_to_face' =>
                6,

            'public_place' =>
                24,

            default =>
                null,
        };


        /*
        |--------------------------------------------------------------------------
        | UNKNOWN LESSON TYPE
        |--------------------------------------------------------------------------
        |
        | Older bookings may not have teaching_type.
        | We do NOT guess which refund policy applies.
        |
        */

        if ($requiredHours === null) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Le type de cours de cette réservation est introuvable. Le remboursement automatique ne peut pas être calculé. Veuillez contacter le soutien DancePair.'
                    : 'The lesson type for this booking could not be determined. The automatic refund cannot be calculated. Please contact DancePair support.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REFUND CUTOFF
        |--------------------------------------------------------------------------
        */

        $refundCutoff =
            $lessonStart
                ->copy()
                ->subHours(
                    $requiredHours
                );


        $eligibleForRefund =
            now()->lte(
                $refundCutoff
            );


        /*
        |--------------------------------------------------------------------------
        | LATE CANCELLATION
        |--------------------------------------------------------------------------
        |
        | Student can cancel the lesson,
        | but no automatic refund applies.
        |
        | Payment remains PAID because the funds are not returned.
        |
        */

        if (!$eligibleForRefund) {

            $booking->update([
                'status' =>
                    'cancelled',
            ]);


            /*
            |--------------------------------------------------------------------------
            | NOTIFY BOTH
            |--------------------------------------------------------------------------
            */

            app(BookingActivityNotifier::class)
                ->notifyBoth(
                    booking: $booking,
                    action: 'student_cancelled_no_refund',
                    actorRole: 'student',
                    actorName: Auth::user()?->name,
                    payment: $payment
                );


            return back()->with(
                'success',
                app()->getLocale() === 'fr'
                    ? 'Le cours a été annulé. Le délai d’annulation applicable est dépassé; cette annulation n’est donc pas admissible à un remboursement automatique.'
                    : 'The lesson has been cancelled. The applicable cancellation deadline has passed, so this cancellation is not eligible for an automatic refund.'
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
                    : 'This payment cannot be refunded automatically. Please contact DancePair support.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STRIPE TRANSACTION ID
        |--------------------------------------------------------------------------
        */

        if (
            !$payment->transaction_id
        ) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'La transaction Stripe associée à ce paiement est introuvable. Veuillez contacter le soutien DancePair.'
                    : 'The Stripe transaction associated with this payment could not be found. Please contact DancePair support.'
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
            | PAYMENT INTENT
            |--------------------------------------------------------------------------
            |
            | Current DancePair payment flow normally stores a PaymentIntent
            | ID (pi_...) in transaction_id.
            |
            | This also supports an old Checkout Session ID (cs_...)
            | if one exists.
            |
            */

            $paymentIntentId =
                $payment->transaction_id;


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
                        : 'The Stripe transaction associated with this payment is invalid. Please contact DancePair support.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | CREATE FULL REFUND
            |--------------------------------------------------------------------------
            |
            | reverse_transfer = true
            |
            | Because DancePair uses a destination charge, the teacher's
            | transferred portion is pulled back from the connected account.
            |
            |
            | refund_application_fee = true
            |
            | DancePair's application fee is also refunded.
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
                            (string) $student->id,

                        'teacher_id' =>
                            (string) $booking->teacher_id,

                        'cancelled_by' =>
                            'student',

                        'teaching_type' =>
                            (string) $booking->teaching_type,

                        'refund_policy_hours' =>
                            (string) $requiredHours,
                    ],
                ],
                [

                    /*
                    |--------------------------------------------------------------------------
                    | IDEMPOTENCY
                    |--------------------------------------------------------------------------
                    |
                    | Prevent accidental duplicate refunds.
                    |
                    */

                    'idempotency_key' =>
                        'dancepair_student_refund_booking_'
                        . $booking->id
                        . '_payment_'
                        . $payment->id,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | REFUND FAILED OR CANCELLED
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
                        ? 'Stripe n’a pas pu effectuer le remboursement. La réservation n’a pas été annulée. Veuillez réessayer ou contacter le soutien DancePair.'
                        : 'Stripe could not complete the refund. The booking was not cancelled. Please try again or contact DancePair support.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | REFUND PENDING
            |--------------------------------------------------------------------------
            |
            | Some payment methods do not complete a refund immediately.
            |
            */

            if (
                $refund->status ===
                'pending'
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
                | NOTIFY BOTH
                |--------------------------------------------------------------------------
                */

                app(BookingActivityNotifier::class)
                    ->notifyBoth(
                        booking: $booking,
                        action: 'student_cancelled_refund_pending',
                        actorRole: 'student',
                        actorName: Auth::user()?->name,
                        payment: $payment
                    );


                return back()->with(
                    'success',
                    app()->getLocale() === 'fr'
                        ? 'Le cours a été annulé. Votre remboursement complet de 100 % est maintenant en cours de traitement vers le mode de paiement d’origine.'
                        : 'The lesson has been cancelled. Your 100% full refund is now being processed to the original payment method.'
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
                | NOTIFY BOTH
                |--------------------------------------------------------------------------
                */

                app(BookingActivityNotifier::class)
                    ->notifyBoth(
                        booking: $booking,
                        action: 'student_cancelled_refunded',
                        actorRole: 'student',
                        actorName: Auth::user()?->name,
                        payment: $payment
                    );


                return back()->with(
                    'success',
                    app()->getLocale() === 'fr'
                        ? 'Le cours a été annulé et un remboursement complet de 100 % a été envoyé au mode de paiement d’origine.'
                        : 'The lesson has been cancelled and a 100% full refund has been sent to the original payment method.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | UNKNOWN STRIPE REFUND STATUS
            |--------------------------------------------------------------------------
            */

            report(
                new \RuntimeException(
                    'Unexpected Stripe refund status: '
                    . ($refund->status ?? 'unknown')
                )
            );


            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Stripe a retourné un statut de remboursement inattendu. Veuillez contacter le soutien DancePair.'
                    : 'Stripe returned an unexpected refund status. Please contact DancePair support.'
            );

        } catch (ApiErrorException $exception) {

            report(
                $exception
            );


            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Le remboursement Stripe n’a pas pu être effectué. La réservation n’a pas été annulée. Veuillez réessayer ou contacter le soutien DancePair.'
                    : 'The Stripe refund could not be processed. The booking was not cancelled. Please try again or contact DancePair support.'
            );

        } catch (\Throwable $exception) {

            report(
                $exception
            );


            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Une erreur est survenue pendant l’annulation. Veuillez réessayer ou contacter le soutien DancePair.'
                    : 'An error occurred while cancelling the lesson. Please try again or contact DancePair support.'
            );
        }
    }
}