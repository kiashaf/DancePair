<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $student = Student::where(
            'user_id',
            $user->id
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | UPCOMING BOOKINGS
        |--------------------------------------------------------------------------
        */

        $upcomingBookingList = Booking::with([
            'teacher.user',
            'danceStyle',
        ])
            ->where(
                'student_id',
                $student->id
            )
            ->whereIn(
                'status',
                [
                    'pending',
                    'confirmed',
                ]
            )
            ->whereDate(
                'lesson_date',
                '>=',
                today()
            )
            ->orderBy(
                'lesson_date'
            )
            ->orderBy(
                'lesson_time'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | COMPLETED LESSONS
        |--------------------------------------------------------------------------
        */

        $completedLessonList = Booking::with([
            'teacher.user',
            'danceStyle',
        ])
            ->where(
                'student_id',
                $student->id
            )
            ->where(
                'status',
                'completed'
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
        | PAYMENT REQUIRED
        |--------------------------------------------------------------------------
        */

        $paymentRequiredList = Booking::with([
            'teacher.user',
            'danceStyle',
        ])
            ->where(
                'student_id',
                $student->id
            )
            ->where(
                'status',
                'confirmed'
            )
            ->where(
                'paid',
                false
            )
            ->orderBy(
                'lesson_date'
            )
            ->orderBy(
                'lesson_time'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | COUNTS
        |--------------------------------------------------------------------------
        */

        $upcomingBookings =
            $upcomingBookingList->count();

        $completedLessons =
            $completedLessonList->count();

        $pendingPayments =
            $paymentRequiredList->count();


        /*
        |--------------------------------------------------------------------------
        | UNREAD NOTIFICATIONS ONLY
        |--------------------------------------------------------------------------
        |
        | فقط Notificationهای جدید نمایش داده می‌شوند.
        |
        */

        $notifications = $user
            ->unreadNotifications()
            ->latest()
            ->take(12)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NUMBER SHOWN IN BADGE
        |--------------------------------------------------------------------------
        */

        $unreadNotificationCount =
            $notifications->count();


        /*
        |--------------------------------------------------------------------------
        | MARK AS READ AFTER THEY ARE DISPLAYED
        |--------------------------------------------------------------------------
        |
        | کاربر در همین Dashboard آنها را می‌بیند.
        | بنابراین بعد از این request به حالت read می‌روند.
        |
        | Refresh بعدی:
        | دیگر نمایش داده نمی‌شوند.
        |
        */

        if ($notifications->isNotEmpty()) {

            $notifications->markAsRead();
        }


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'student.dashboard',
            compact(
                'upcomingBookings',
                'completedLessons',
                'pendingPayments',
                'upcomingBookingList',
                'completedLessonList',
                'paymentRequiredList',
                'notifications',
                'unreadNotificationCount'
            )
        );
    }
}