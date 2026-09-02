<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $teacher = Teacher::where(
            'user_id',
            $user->id
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | NEW LESSON REQUESTS
        |--------------------------------------------------------------------------
        */

        $pendingRequestList = Booking::with([
            'student.user',
            'danceStyle',
        ])
            ->where(
                'teacher_id',
                $teacher->id
            )
            ->where(
                'status',
                'pending'
            )
            ->whereDate(
                'lesson_date',
                '>=',
                today()
            )
            ->whereHas(
                'student.user',
                function ($query) {

                    $query->where(
                        'role',
                        'student'
                    );
                }
            )
            ->orderBy(
                'lesson_date',
                'asc'
            )
            ->orderBy(
                'lesson_time',
                'asc'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | UPCOMING ACCEPTED LESSONS
        |--------------------------------------------------------------------------
        */

        $upcomingLessonList = Booking::with([
            'student.user',
            'danceStyle',
        ])
            ->where(
                'teacher_id',
                $teacher->id
            )
            ->where(
                'status',
                'confirmed'
            )
            ->whereDate(
                'lesson_date',
                '>=',
                today()
            )
            ->whereHas(
                'student.user',
                function ($query) {

                    $query->where(
                        'role',
                        'student'
                    );
                }
            )
            ->orderBy(
                'lesson_date',
                'asc'
            )
            ->orderBy(
                'lesson_time',
                'asc'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PAYMENTS RECEIVED
        |--------------------------------------------------------------------------
        */

        $paymentReceivedList = Payment::with([
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
        | COUNTS
        |--------------------------------------------------------------------------
        */

        $pendingRequests =
            $pendingRequestList->count();

        $upcomingLessons =
            $upcomingLessonList->count();

        $paymentsReceived =
            $paymentReceivedList->count();


        /*
        |--------------------------------------------------------------------------
        | TEACHER EARNINGS
        |--------------------------------------------------------------------------
        */

        $totalTeacherEarnings =
            $paymentReceivedList->sum(
                'teacher_amount'
            );


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
        | UNREAD COUNT
        |--------------------------------------------------------------------------
        */

        $unreadNotificationCount =
            $notifications->count();


        /*
        |--------------------------------------------------------------------------
        | MARK NOTIFICATIONS AS READ
        |--------------------------------------------------------------------------
        |
        | Teacher همین الان Dashboard را دیده.
        |
        | بنابراین Notificationهایی که در همین صفحه نمایش داده می‌شوند
        | read می‌شوند.
        |
        | در Refresh بعدی دیگر نمایش داده نمی‌شوند.
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
            'teacher.dashboard',
            compact(
                'teacher',

                'pendingRequests',
                'upcomingLessons',
                'paymentsReceived',

                'totalTeacherEarnings',

                'pendingRequestList',
                'upcomingLessonList',
                'paymentReceivedList',

                'notifications',
                'unreadNotificationCount'
            )
        );
    }
}