<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Teacher;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | TEACHERS
        |--------------------------------------------------------------------------
        */

        $teachers = Teacher::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'teacher');
            })
            ->orderByDesc('created_at')
            ->get();

        $teachersCount = $teachers->count();


        /*
        |--------------------------------------------------------------------------
        | PENDING TEACHERS
        |--------------------------------------------------------------------------
        */

        $pendingTeachers = $teachers
            ->where('verified', false)
            ->values();

        $pendingTeachersCount =
            $pendingTeachers->count();


        /*
        |--------------------------------------------------------------------------
        | STUDENTS
        |--------------------------------------------------------------------------
        */

        $students = Student::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->orderByDesc('created_at')
            ->get();

        $studentsCount =
            $students->count();


        /*
        |--------------------------------------------------------------------------
        | ALL BOOKINGS
        |--------------------------------------------------------------------------
        */

        $bookings = Booking::with([
            'student.user',
            'teacher.user',
            'danceStyle',
            'payment',
        ])
            ->orderByDesc('lesson_date')
            ->orderByDesc('lesson_time')
            ->get();

        $bookingsCount =
            $bookings->count();


        /*
        |--------------------------------------------------------------------------
        | PENDING REQUESTS
        |--------------------------------------------------------------------------
        */

        $pendingBookings = $bookings
            ->where('status', 'pending')
            ->values();

        $pendingBookingsCount =
            $pendingBookings->count();


        /*
        |--------------------------------------------------------------------------
        | UPCOMING LESSONS
        |--------------------------------------------------------------------------
        */

        $upcomingBookings = $bookings
            ->filter(function ($booking) {

                return
                    $booking->status === 'confirmed'
                    &&
                    \Carbon\Carbon::parse(
                        $booking->lesson_date
                    )->startOfDay()
                    >=
                    today();
            })
            ->sortBy([
                ['lesson_date', 'asc'],
                ['lesson_time', 'asc'],
            ])
            ->values();

        $upcomingBookingsCount =
            $upcomingBookings->count();


        /*
        |--------------------------------------------------------------------------
        | COMPLETED LESSONS
        |--------------------------------------------------------------------------
        */

        $completedBookings = $bookings
            ->where('status', 'completed')
            ->values();

        $completedBookingsCount =
            $completedBookings->count();


        /*
        |--------------------------------------------------------------------------
        | CANCELLED / REFUSED
        |--------------------------------------------------------------------------
        */

        $cancelledBookings = $bookings
            ->where('status', 'cancelled')
            ->values();

        $cancelledBookingsCount =
            $cancelledBookings->count();


        /*
        |--------------------------------------------------------------------------
        | AWAITING PAYMENT
        |--------------------------------------------------------------------------
        */

        $awaitingPaymentBookings = $bookings
            ->filter(function ($booking) {

                return
                    $booking->status === 'confirmed'
                    &&
                    !$booking->paid;
            })
            ->values();

        $awaitingPaymentCount =
            $awaitingPaymentBookings->count();


        /*
        |--------------------------------------------------------------------------
        | PAID BOOKINGS
        |--------------------------------------------------------------------------
        */

        $paidBookings = $bookings
            ->where('paid', true)
            ->values();

        $paidBookingsCount =
            $paidBookings->count();


        /*
        |--------------------------------------------------------------------------
        | PAYMENTS
        |--------------------------------------------------------------------------
        */

        $payments = Payment::with([
            'booking.student.user',
            'booking.teacher.user',
            'booking.danceStyle',
        ])
            ->where('status', 'paid')
            ->orderByDesc('paid_at')
            ->get();

        $paymentsCount =
            $payments->count();


        /*
        |--------------------------------------------------------------------------
        | FINANCIAL TOTALS
        |--------------------------------------------------------------------------
        */

        $grossRevenue = (float) $payments
            ->sum('amount');

        $dancePairRevenue = (float) $payments
            ->sum('platform_fee');

        $teacherEarnings = (float) $payments
            ->sum('teacher_amount');


        /*
        |--------------------------------------------------------------------------
        | CURRENT COMMISSION %
        |--------------------------------------------------------------------------
        */

        $commissionPercent = (float) Setting::getValue(
            'platform_commission_percent',
            0
        );


        /*
        |--------------------------------------------------------------------------
        | REVIEWS
        |--------------------------------------------------------------------------
        */

        $reviews = Review::with([
            'student.user',
            'teacher.user',
            'booking.danceStyle',
        ])
            ->orderByDesc('created_at')
            ->get();

        $reviewsCount =
            $reviews->count();


        /*
        |--------------------------------------------------------------------------
        | RECENT BOOKINGS
        |--------------------------------------------------------------------------
        */

        $recentBookings = $bookings
            ->sortByDesc('created_at')
            ->take(8)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RECENT PAYMENTS
        |--------------------------------------------------------------------------
        */

        $recentPayments = $payments
            ->take(8);


        return view(
            'admin.dashboard',
            compact(
                'teachers',
                'teachersCount',

                'pendingTeachers',
                'pendingTeachersCount',

                'students',
                'studentsCount',

                'bookings',
                'bookingsCount',

                'pendingBookings',
                'pendingBookingsCount',

                'upcomingBookings',
                'upcomingBookingsCount',

                'completedBookings',
                'completedBookingsCount',

                'cancelledBookings',
                'cancelledBookingsCount',

                'awaitingPaymentBookings',
                'awaitingPaymentCount',

                'paidBookings',
                'paidBookingsCount',

                'payments',
                'paymentsCount',

                'grossRevenue',
                'dancePairRevenue',
                'teacherEarnings',
                'commissionPercent',

                'reviews',
                'reviewsCount',

                'recentBookings',
                'recentPayments'
            )
        );
    }
}