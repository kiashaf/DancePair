<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Booking;

use App\Notifications\BookingAcceptedNotification;
use App\Notifications\BookingRejectedNotification;

use Illuminate\Support\Facades\Auth;

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
        ])
            ->where(
                'teacher_id',
                $teacher->id
            )

            // فقط Requestهایی که واقعاً از Student آمده
            ->whereHas(
                'student.user',
                function ($query) {
                    $query->where(
                        'role',
                        'student'
                    );
                }
            )

            // تاریخ جدیدتر بالا
            ->orderBy(
                'lesson_date',
                'desc'
            )

            // اگر تاریخ یکی بود، ساعت دیرتر بالا
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

    public function accept(Booking $booking)
    {
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

        if ($booking->status !== 'pending') {
            return back()->with(
                'error',
                'This request can no longer be accepted.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD STUDENT USER
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
            'status' => 'confirmed',
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFY STUDENT
        |--------------------------------------------------------------------------
        |
        | Database notification
        | +
        | Email
        |
        */

        if ($booking->student?->user) {
            $booking->student->user->notify(
                new BookingAcceptedNotification(
                    $booking
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Lesson request accepted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT BOOKING
    |--------------------------------------------------------------------------
    */

    public function reject(Booking $booking)
    {
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

        if ($booking->status !== 'pending') {
            return back()->with(
                'error',
                'This request can no longer be refused.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD STUDENT USER
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
            'status' => 'cancelled',
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFY STUDENT
        |--------------------------------------------------------------------------
        */

        if ($booking->student?->user) {
            $booking->student->user->notify(
                new BookingRejectedNotification(
                    $booking
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Lesson request refused.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT PROFILE
    |--------------------------------------------------------------------------
    */

    public function studentProfile(Booking $booking)
    {
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

        if (is_null($booking->teacher_viewed_at)) {
            $booking->update([
                'teacher_viewed_at' => now(),
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

        $student = $booking->student;

        return view(
            'teacher.students.show',
            compact(
                'student',
                'booking'
            )
        );
    }
}