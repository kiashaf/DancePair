<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Teacher;
use App\Models\Student;

use App\Services\BookingActivityNotifier;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Refund;
use Stripe\Stripe;

class AdminPaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PAYMENT LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        $this->authorizeAdmin();


        /*
        |--------------------------------------------------------------------------
        | FILTER OPTIONS
        |--------------------------------------------------------------------------
        */

        $teachers = Teacher::with('user')
            ->whereHas(
                'user',
                function ($query) {

                    $query->where(
                        'role',
                        'teacher'
                    );
                }
            )
            ->orderBy('id')
            ->get();


        $students = Student::with('user')
            ->whereHas(
                'user',
                function ($query) {

                    $query->where(
                        'role',
                        'student'
                    );
                }
            )
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | GLOBAL SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalPayments =
            Payment::count();


        $paidPaymentsCount =
            Payment::whereNotNull(
                'paid_at'
            )
                ->whereNull(
                    'refunded_at'
                )
                ->count();


        $refundedPaymentsCount =
            Payment::whereNotNull(
                'refunded_at'
            )
                ->count();


        $grossPayments =
            (float) Payment::whereNotNull(
                'paid_at'
            )
                ->whereNull(
                    'refunded_at'
                )
                ->sum(
                    'amount'
                );


        $dancePairRevenue =
            (float) Payment::whereNotNull(
                'paid_at'
            )
                ->whereNull(
                    'refunded_at'
                )
                ->sum(
                    'platform_fee'
                );


        $teacherEarnings =
            (float) Payment::whereNotNull(
                'paid_at'
            )
                ->whereNull(
                    'refunded_at'
                )
                ->sum(
                    'teacher_amount'
                );


        $refundedAmount =
            (float) Payment::whereNotNull(
                'refunded_at'
            )
                ->sum(
                    'amount'
                );


        /*
        |--------------------------------------------------------------------------
        | CHECK IF SEARCH / FILTER EXISTS
        |--------------------------------------------------------------------------
        */

        $hasFilters =
            $request->filled('search')
            ||
            $request->filled('status')
            ||
            $request->filled('provider')
            ||
            $request->filled('teacher_id')
            ||
            $request->filled('student_id')
            ||
            $request->filled('date_from')
            ||
            $request->filled('date_to')
            ||
            $request->filled('refund');


        /*
        |--------------------------------------------------------------------------
        | DO NOT LOAD PAYMENT LIST BY DEFAULT
        |--------------------------------------------------------------------------
        */

        $payments = null;


        if ($hasFilters) {

            $query = Payment::with([
                'student.user',
                'teacher.user',
                'booking.danceStyle',
            ]);


            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'search'
                )
            ) {

                $search =
                    trim(
                        $request->search
                    );


                $query->where(
                    function ($q) use ($search) {

                        $q->where(
                            'transaction_id',
                            'like',
                            '%' . $search . '%'
                        );


                        $q->orWhereHas(
                            'student.user',
                            function ($studentQuery) use ($search) {

                                $studentQuery
                                    ->where(
                                        'name',
                                        'like',
                                        '%' . $search . '%'
                                    )
                                    ->orWhere(
                                        'email',
                                        'like',
                                        '%' . $search . '%'
                                    );
                            }
                        );


                        $q->orWhereHas(
                            'teacher.user',
                            function ($teacherQuery) use ($search) {

                                $teacherQuery
                                    ->where(
                                        'name',
                                        'like',
                                        '%' . $search . '%'
                                    )
                                    ->orWhere(
                                        'email',
                                        'like',
                                        '%' . $search . '%'
                                    );
                            }
                        );


                        $q->orWhereHas(
                            'booking.danceStyle',
                            function ($danceQuery) use ($search) {

                                $danceQuery->where(
                                    'name',
                                    'like',
                                    '%' . $search . '%'
                                );
                            }
                        );
                    }
                );
            }


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'status'
                )
            ) {

                $query->where(
                    'status',
                    $request->status
                );
            }


            /*
            |--------------------------------------------------------------------------
            | PROVIDER
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'provider'
                )
            ) {

                $query->where(
                    'payment_provider',
                    $request->provider
                );
            }


            /*
            |--------------------------------------------------------------------------
            | TEACHER
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'teacher_id'
                )
            ) {

                $query->where(
                    'teacher_id',
                    $request->teacher_id
                );
            }


            /*
            |--------------------------------------------------------------------------
            | STUDENT
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'student_id'
                )
            ) {

                $query->where(
                    'student_id',
                    $request->student_id
                );
            }


            /*
            |--------------------------------------------------------------------------
            | REFUND
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'refund'
                )
            ) {

                if (
                    $request->refund ===
                    'refunded'
                ) {

                    $query->whereNotNull(
                        'refunded_at'
                    );
                }


                if (
                    $request->refund ===
                    'not_refunded'
                ) {

                    $query->whereNull(
                        'refunded_at'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | DATE FROM
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'date_from'
                )
            ) {

                $query->whereDate(
                    'paid_at',
                    '>=',
                    $request->date_from
                );
            }


            /*
            |--------------------------------------------------------------------------
            | DATE TO
            |--------------------------------------------------------------------------
            */

            if (
                $request->filled(
                    'date_to'
                )
            ) {

                $query->whereDate(
                    'paid_at',
                    '<=',
                    $request->date_to
                );
            }


            /*
            |--------------------------------------------------------------------------
            | RESULTS
            |--------------------------------------------------------------------------
            */

            $payments =
                $query
                    ->orderByDesc(
                        'paid_at'
                    )
                    ->orderByDesc(
                        'id'
                    )
                    ->paginate(
                        25
                    )
                    ->withQueryString();
        }


        /*
        |--------------------------------------------------------------------------
        | PROVIDERS
        |--------------------------------------------------------------------------
        */

        $providers =
            Payment::whereNotNull(
                'payment_provider'
            )
                ->where(
                    'payment_provider',
                    '!=',
                    ''
                )
                ->distinct()
                ->orderBy(
                    'payment_provider'
                )
                ->pluck(
                    'payment_provider'
                );


        /*
        |--------------------------------------------------------------------------
        | STATUSES
        |--------------------------------------------------------------------------
        */

        $statuses =
            Payment::whereNotNull(
                'status'
            )
                ->where(
                    'status',
                    '!=',
                    ''
                )
                ->distinct()
                ->orderBy(
                    'status'
                )
                ->pluck(
                    'status'
                );


        return view(
            'admin.payments.index',
            compact(
                'payments',
                'teachers',
                'students',
                'providers',
                'statuses',
                'hasFilters',
                'totalPayments',
                'paidPaymentsCount',
                'refundedPaymentsCount',
                'grossPayments',
                'dancePairRevenue',
                'teacherEarnings',
                'refundedAmount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT DETAIL
    |--------------------------------------------------------------------------
    */

    public function show(
        Payment $payment
    ) {
        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        $this->authorizeAdmin();


        /*
        |--------------------------------------------------------------------------
        | LOAD PAYMENT
        |--------------------------------------------------------------------------
        */

        $payment->load([
            'student.user',
            'teacher.user',
            'booking.danceStyle',
        ]);


        return view(
            'admin.payments.show',
            compact(
                'payment'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN FULL REFUND
    |--------------------------------------------------------------------------
    |
    | Admin override:
    |
    | - No 2h / 6h / 24h restriction.
    | - Full 100% refund.
    | - Refund to original payment method.
    | - Reverse Teacher destination transfer.
    | - Refund DancePair application fee.
    | - Mark Payment as refunded when Stripe succeeds.
    | - Cancel associated Booking.
    |
    */

    public function refund(
        Payment $payment
    ) {
        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        $this->authorizeAdmin();


        /*
        |--------------------------------------------------------------------------
        | LOAD RELATED DATA
        |--------------------------------------------------------------------------
        */

        $payment->load([
            'student.user',
            'teacher.user',
            'booking.teacher.user',
            'booking.student.user',
            'booking.danceStyle',
        ]);


        $booking =
            $payment->booking;


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

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Ce paiement a déjà été remboursé.'
                    : 'This payment has already been refunded.'
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

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Le remboursement de ce paiement est déjà en cours de traitement.'
                    : 'The refund for this payment is already being processed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT MUST HAVE BEEN COMPLETED
        |--------------------------------------------------------------------------
        */

        if (
            !$payment->paid_at
            &&
            $payment->status !==
            'paid'
        ) {

            return back()->with(
                'error',
                app()->getLocale() === 'fr'
                    ? 'Seul un paiement complété peut être remboursé.'
                    : 'Only a completed payment can be refunded.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT PROVIDER
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
                    ? 'Ce paiement ne peut pas être remboursé automatiquement par Stripe.'
                    : 'This payment cannot be refunded automatically through Stripe.'
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
                    ? 'La transaction Stripe associée à ce paiement est introuvable.'
                    : 'The Stripe transaction associated with this payment could not be found.'
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


        try {

            /*
            |--------------------------------------------------------------------------
            | PAYMENT INTENT
            |--------------------------------------------------------------------------
            |
            | New DancePair payments normally store:
            |
            | pi_...
            |
            | Older payments may contain:
            |
            | cs_...
            |
            */

            $paymentIntentId =
                (string) $payment
                    ->transaction_id;


            /*
            |--------------------------------------------------------------------------
            | OLD CHECKOUT SESSION SUPPORT
            |--------------------------------------------------------------------------
            */

            if (
                str_starts_with(
                    $paymentIntentId,
                    'cs_'
                )
            ) {

                $stripeSession =
                    Session::retrieve(
                        $paymentIntentId
                    );


                $paymentIntentId =
                    (string) $stripeSession
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
                    $paymentIntentId,
                    'pi_'
                )
            ) {

                return back()->with(
                    'error',
                    app()->getLocale() === 'fr'
                        ? 'L’identifiant de paiement Stripe associé à cette transaction est invalide.'
                        : 'The Stripe PaymentIntent associated with this transaction is invalid.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | CREATE 100% REFUND
            |--------------------------------------------------------------------------
            |
            | No amount is supplied.
            |
            | Stripe therefore refunds the remaining refundable
            | amount of the payment.
            |
            | reverse_transfer:
            | Reverse funds transferred to the Teacher.
            |
            | refund_application_fee:
            | Refund DancePair's application fee.
            |
            */

            $refund =
                Refund::create(
                    [

                        'payment_intent' =>
                            $paymentIntentId,

                        'reverse_transfer' =>
                            true,

                        'refund_application_fee' =>
                            true,

                        'metadata' => [

                            'booking_id' =>
                                (string) (
                                    $booking?->id
                                    ?? ''
                                ),

                            'payment_id' =>
                                (string) $payment->id,

                            'student_id' =>
                                (string) (
                                    $payment->student_id
                                    ?? ''
                                ),

                            'teacher_id' =>
                                (string) (
                                    $payment->teacher_id
                                    ?? ''
                                ),

                            'refunded_by' =>
                                'admin',

                            'admin_user_id' =>
                                (string) Auth::id(),

                            'refund_type' =>
                                'full',

                            'refund_percentage' =>
                                '100',

                            'refund_reason' =>
                                'admin_override',
                        ],
                    ],
                    [

                        /*
                        |--------------------------------------------------------------------------
                        | IDEMPOTENCY
                        |--------------------------------------------------------------------------
                        |
                        | Prevent duplicate refunds if:
                        |
                        | - Admin double-clicks
                        | - Browser retries
                        | - Request is submitted twice
                        |
                        */

                        'idempotency_key' =>
                            'dancepair_admin_refund_payment_'
                            . $payment->id,
                    ]
                );


            /*
            |--------------------------------------------------------------------------
            | FAILED / CANCELED
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
                        ? 'Stripe n’a pas pu effectuer le remboursement. Aucune modification n’a été apportée à la réservation.'
                        : 'Stripe could not complete the refund. No changes were made to the booking.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | PENDING / REQUIRES ACTION
            |--------------------------------------------------------------------------
            |
            | Do NOT set refunded_at yet.
            |
            | The money has not yet been confirmed as refunded.
            |
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
                        $payment,
                        $booking
                    ) {

                        $payment->update([
                            'status' =>
                                'refund_pending',
                        ]);


                        if ($booking) {

                            $booking->update([
                                'status' =>
                                    'cancelled',
                            ]);
                        }
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | NOTIFY STUDENT + TEACHER
                |--------------------------------------------------------------------------
                */

                if ($booking) {

                    app(BookingActivityNotifier::class)
                        ->notifyBoth(
                            booking: $booking,
                            action: 'admin_refund_pending',
                            actorRole: 'admin',
                            actorName: 'DancePair Support',
                            payment: $payment
                        );
                }


                return back()->with(
                    'success',
                    app()->getLocale() === 'fr'
                        ? 'La réservation a été annulée et le remboursement complet de 100 % est maintenant en cours de traitement.'
                        : 'The booking has been cancelled and the 100% full refund is now being processed.'
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
                        $payment,
                        $booking
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | PAYMENT
                        |--------------------------------------------------------------------------
                        */

                        $payment->update([

                            'status' =>
                                'refunded',

                            'refunded_at' =>
                                now(),
                        ]);


                        /*
                        |--------------------------------------------------------------------------
                        | BOOKING
                        |--------------------------------------------------------------------------
                        */

                        if ($booking) {

                            $booking->update([

                                'status' =>
                                    'cancelled',

                                'paid' =>
                                    false,
                            ]);
                        }
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | NOTIFY STUDENT + TEACHER
                |--------------------------------------------------------------------------
                */

                if ($booking) {

                    app(BookingActivityNotifier::class)
                        ->notifyBoth(
                            booking: $booking,
                            action: 'admin_refunded',
                            actorRole: 'admin',
                            actorName: 'DancePair Support',
                            payment: $payment
                        );
                }


                return back()->with(
                    'success',
                    app()->getLocale() === 'fr'
                        ? 'Le remboursement complet de 100 % a été effectué avec succès vers le mode de paiement d’origine.'
                        : 'The 100% full refund was completed successfully to the original payment method.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | UNKNOWN STRIPE STATUS
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
                    ? 'Stripe a retourné un statut de remboursement inattendu. Veuillez vérifier la transaction avant de réessayer.'
                    : 'Stripe returned an unexpected refund status. Please verify the transaction before trying again.'
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
                    ? 'Le remboursement Stripe n’a pas pu être effectué. Aucune modification locale n’a été apportée.'
                    : 'The Stripe refund could not be completed. No local changes were made.'
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
                    ? 'Une erreur est survenue pendant le remboursement. Veuillez réessayer.'
                    : 'An error occurred while processing the refund. Please try again.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN SECURITY
    |--------------------------------------------------------------------------
    */

    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check()
            &&
            Auth::user()?->role ===
            'admin',
            403
        );
    }
}