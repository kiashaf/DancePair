<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingMessage;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherAvailability;
use App\Notifications\BookingMessageNotification;
use App\Notifications\NewBookingRequestNotification;
use App\Services\MessageContentFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentBookingRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CREATE STUDENT BOOKING REQUEST
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        TeacherAvailability $availability,
        MessageContentFilter $contentFilter
    ) {
        /*
        |--------------------------------------------------------------------------
        | GET LOGGED-IN STUDENT
        |--------------------------------------------------------------------------
        */

        $student = Student::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | GET TEACHER
        |--------------------------------------------------------------------------
        */

        $teacher = Teacher::findOrFail(
            $availability->teacher_id
        );


        /*
        |--------------------------------------------------------------------------
        | PREVENT SELF BOOKING
        |--------------------------------------------------------------------------
        */

        if (
            (int) $teacher->user_id ===
            (int) $student->user_id
        ) {
            return back()->with(
                'error',
                __('student.cannot_request_own_lesson')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE OPTIONAL MESSAGE
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'message' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ]);

        $messageText = trim(
            $validated['message'] ?? ''
        );


        /*
        |--------------------------------------------------------------------------
        | BLOCK CONTACT INFORMATION
        |--------------------------------------------------------------------------
        |
        | We check BEFORE creating the booking.
        | This prevents creating a booking if the message is rejected.
        |
        */

        if (
            $messageText !== ''
            &&
            $contentFilter->containsForbiddenContactInfo(
                $messageText
            )
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'message' =>
                        __('messages.contact_information_not_allowed'),
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE REQUEST
        |--------------------------------------------------------------------------
        */

        $alreadyRequested = Booking::where(
                'student_id',
                $student->id
            )
            ->where(
                'teacher_id',
                $availability->teacher_id
            )
            ->where(
                'lesson_date',
                $availability->available_date
            )
            ->where(
                'lesson_time',
                $availability->start_time
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
                __('student.already_requested_class')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GET HOURLY RATE FOR THIS DANCE STYLE
        |--------------------------------------------------------------------------
        */

        $hourlyRate = DB::table(
            'dance_style_teacher'
        )
            ->where(
                'teacher_id',
                $availability->teacher_id
            )
            ->where(
                'dance_style_id',
                $availability->dance_style_id
            )
            ->value('hourly_rate');


        /*
        |--------------------------------------------------------------------------
        | RATE MUST EXIST
        |--------------------------------------------------------------------------
        */

        if ($hourlyRate === null) {
            return back()->with(
                'error',
                __('student.no_hourly_rate_for_style')
            );
        }

        $hourlyRate = (float) $hourlyRate;


        /*
        |--------------------------------------------------------------------------
        | CALCULATE LESSON DURATION
        |--------------------------------------------------------------------------
        */

        $startTime = Carbon::parse(
            $availability->start_time
        );

        $endTime = Carbon::parse(
            $availability->end_time
        );

        $duration = $startTime->diffInMinutes(
            $endTime
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDATE DURATION
        |--------------------------------------------------------------------------
        */

        if ($duration <= 0) {
            return back()->with(
                'error',
                __('student.invalid_lesson_duration')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CALCULATE TOTAL LESSON PRICE
        |--------------------------------------------------------------------------
        */

        $price = round(
            $hourlyRate * ($duration / 60),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | CREATE BOOKING + FIRST MESSAGE
        |--------------------------------------------------------------------------
        */

        $booking = DB::transaction(
            function () use (
                $student,
                $availability,
                $duration,
                $price,
                $messageText
            ) {

                /*
                |--------------------------------------------------------------------------
                | CREATE BOOKING
                |--------------------------------------------------------------------------
                */

                $booking = Booking::create([

                    'student_id' =>
                        $student->id,

                    'teacher_id' =>
                        $availability->teacher_id,

                    'dance_style_id' =>
                        $availability->dance_style_id,

                    'lesson_date' =>
                        $availability->available_date,

                    'lesson_time' =>
                        $availability->start_time,

                    'duration' =>
                        $duration,

                    'price' =>
                        $price,

                    'status' =>
                        'pending',

                    'paid' =>
                        false,
                ]);


                /*
                |--------------------------------------------------------------------------
                | CREATE OPTIONAL FIRST MESSAGE
                |--------------------------------------------------------------------------
                */

                if ($messageText !== '') {

                    BookingMessage::create([

                        'booking_id' =>
                            $booking->id,

                        'sender_id' =>
                            Auth::id(),

                        'message' =>
                            $messageText,
                    ]);
                }


                return $booking;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | LOAD RELATIONSHIPS
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'student.user',
            'teacher.user',
            'danceStyle',
        ]);


        /*
        |--------------------------------------------------------------------------
        | TEACHER USER
        |--------------------------------------------------------------------------
        */

        $teacherUser =
            $booking->teacher?->user;


        /*
        |--------------------------------------------------------------------------
        | SEND NEW BOOKING NOTIFICATION
        |--------------------------------------------------------------------------
        |
        | Existing DancePair behaviour stays intact.
        |
        */

        if ($teacherUser) {

            $teacherUser->notify(
                new NewBookingRequestNotification(
                    $booking
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SEND MESSAGE NOTIFICATION + EMAIL
        |--------------------------------------------------------------------------
        |
        | Only when the student actually included a message.
        |
        */

        if (
            $teacherUser
            &&
            $messageText !== ''
        ) {

            $bookingMessage =
                BookingMessage::where(
                    'booking_id',
                    $booking->id
                )
                ->latest('id')
                ->first();


            if ($bookingMessage) {

                $teacherUser->notify(
                    new BookingMessageNotification(
                        $booking,
                        $bookingMessage,
                        Auth::user()->name
                    )
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            __('student.lesson_request_sent')
        );
    }
}