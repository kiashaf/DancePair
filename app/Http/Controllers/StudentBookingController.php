<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Booking;
use App\Models\TeacherAvailability;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

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

            // جدیدترین کلاس بالاتر
            ->orderBy(
                'lesson_date',
                'desc'
            )

            // اگر تاریخ یکی بود، ساعت جدیدتر بالاتر
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
                'Only pending requests can be edited.'
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
                'This time slot is not available for the selected dance style.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE BOOKING
        |--------------------------------------------------------------------------
        |
        | Student نباید برای همان Teacher
        | همان Date + Time درخواست دیگری داشته باشد.
        |
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
                'You already have a request for this class time.'
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
                'Invalid lesson duration.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GET HOURLY RATE
        |--------------------------------------------------------------------------
        |
        | نرخ واقعی همان Dance Style برای همان Teacher
        | از dance_style_teacher
        |
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
                'No hourly rate is configured for this dance style.'
            );
        }


        $hourlyRate = (float) $hourlyRate;


        /*
        |--------------------------------------------------------------------------
        | CALCULATE NEW PRICE
        |--------------------------------------------------------------------------
        |
        | Hourly Rate × Duration
        |
        | مثال:
        |
        | $66 / hour
        | 120 minutes
        | = $132
        |
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


        return back()->with(
            'success',
            'Your booking request has been updated.'
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
                'Only pending requests can be deleted.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE BOOKING
        |--------------------------------------------------------------------------
        */

        $booking->delete();


        return back()->with(
            'success',
            'Booking request deleted successfully.'
        );
    }
}