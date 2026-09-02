<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER OPTIONS
        |--------------------------------------------------------------------------
        */

        $teachers = Teacher::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'teacher');
            })
            ->orderBy('id')
            ->get();


        $students = Student::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | GLOBAL SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalPayments = Payment::count();


        $paidPaymentsCount = Payment::whereNotNull('paid_at')
            ->whereNull('refunded_at')
            ->count();


        $refundedPaymentsCount = Payment::whereNotNull('refunded_at')
            ->count();


        $grossPayments = (float) Payment::whereNotNull('paid_at')
            ->whereNull('refunded_at')
            ->sum('amount');


        $dancePairRevenue = (float) Payment::whereNotNull('paid_at')
            ->whereNull('refunded_at')
            ->sum('platform_fee');


        $teacherEarnings = (float) Payment::whereNotNull('paid_at')
            ->whereNull('refunded_at')
            ->sum('teacher_amount');


        $refundedAmount = (float) Payment::whereNotNull('refunded_at')
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | CHECK IF SEARCH / FILTER EXISTS
        |--------------------------------------------------------------------------
        */

        $hasFilters =
            $request->filled('search') ||
            $request->filled('status') ||
            $request->filled('provider') ||
            $request->filled('teacher_id') ||
            $request->filled('student_id') ||
            $request->filled('date_from') ||
            $request->filled('date_to') ||
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

            if ($request->filled('search')) {

                $search = trim($request->search);

                $query->where(function ($q) use ($search) {

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

                });
            }


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            if ($request->filled('status')) {

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

            if ($request->filled('provider')) {

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

            if ($request->filled('teacher_id')) {

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

            if ($request->filled('student_id')) {

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

            if ($request->filled('refund')) {

                if ($request->refund === 'refunded') {

                    $query->whereNotNull(
                        'refunded_at'
                    );
                }


                if ($request->refund === 'not_refunded') {

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

            if ($request->filled('date_from')) {

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

            if ($request->filled('date_to')) {

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

            $payments = $query
                ->orderByDesc('paid_at')
                ->orderByDesc('id')
                ->paginate(25)
                ->withQueryString();
        }


        /*
        |--------------------------------------------------------------------------
        | PROVIDERS
        |--------------------------------------------------------------------------
        */

        $providers = Payment::whereNotNull('payment_provider')
            ->where('payment_provider', '!=', '')
            ->distinct()
            ->orderBy('payment_provider')
            ->pluck('payment_provider');


        /*
        |--------------------------------------------------------------------------
        | STATUSES
        |--------------------------------------------------------------------------
        */

        $statuses = Payment::whereNotNull('status')
            ->where('status', '!=', '')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');


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

    public function show(Payment $payment)
    {
        $payment->load([
            'student.user',
            'teacher.user',
            'booking.danceStyle',
        ]);


        return view(
            'admin.payments.show',
            compact('payment')
        );
    }
}