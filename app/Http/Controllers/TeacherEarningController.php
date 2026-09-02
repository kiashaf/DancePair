<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class TeacherEarningController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | CURRENT TEACHER
        |--------------------------------------------------------------------------
        */

        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | PAID PAYMENTS
        |--------------------------------------------------------------------------
        |
        | فقط پرداخت‌های موفق این Teacher
        |
        */

        $payments = Payment::with([
            'booking.student.user',
            'booking.danceStyle',
        ])
            ->where(
                'teacher_id',
                $teacher->id
            )
            ->where(
                'status',
                'paid'
            )
            ->orderByDesc(
                'paid_at'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TOTAL GROSS SALES
        |--------------------------------------------------------------------------
        |
        | مبلغی که Studentها در مجموع پرداخت کرده‌اند
        |
        */

        $grossRevenue = (float) $payments->sum(
            'amount'
        );


        /*
        |--------------------------------------------------------------------------
        | TOTAL DANCEPAIR FEES
        |--------------------------------------------------------------------------
        */

        $platformFees = (float) $payments->sum(
            'platform_fee'
        );


        /*
        |--------------------------------------------------------------------------
        | TEACHER NET EARNINGS
        |--------------------------------------------------------------------------
        |
        | درآمد واقعی Teacher بعد از کمیسیون DancePair
        |
        */

        $totalEarnings = (float) $payments->sum(
            'teacher_amount'
        );


        /*
        |--------------------------------------------------------------------------
        | PAID LESSON COUNT
        |--------------------------------------------------------------------------
        */

        $paidLessons = $payments->count();


        return view(
            'teacher.earnings',
            compact(
                'teacher',
                'payments',
                'grossRevenue',
                'platformFees',
                'totalEarnings',
                'paidLessons'
            )
        );
    }
}