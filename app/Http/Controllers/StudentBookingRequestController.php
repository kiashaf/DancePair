<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingMessage;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherAvailability;

use App\Notifications\BookingMessageNotification;

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
        | TEACHING TYPES OFFERED BY THIS AVAILABILITY
        |--------------------------------------------------------------------------
        |
        | Teacher decides which lesson type(s) are available for this slot.
        | Student can never choose a type that was not selected by teacher.
        |
        */

        $allowedTeachingTypes =
            collect(
                $availability->teaching_types ?? []
            )
                ->filter(
                    fn ($type) =>
                        in_array(
                            $type,
                            [
                                'online',
                                'face_to_face',
                                'public_place',
                            ],
                            true
                        )
                )
                ->unique()
                ->values()
                ->all();


        /*
        |--------------------------------------------------------------------------
        | NO TYPE CONFIGURED
        |--------------------------------------------------------------------------
        */

        if (count($allowedTeachingTypes) === 0) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    __('student.lesson_type_not_configured')
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        |
        | ONE TYPE:
        | Student does not choose anything.
        | Teacher's type is automatically used.
        |
        | MULTIPLE TYPES:
        | Student may choose only from the types offered by teacher.
        |
        */

        $validationRules = [

            'message' => [
                'nullable',
                'string',
                'max:3000',
            ],

        ];


        if (count($allowedTeachingTypes) > 1) {

            $validationRules['teaching_type'] = [
                'required',
                'string',

                function (
                    $attribute,
                    $value,
                    $fail
                ) use (
                    $allowedTeachingTypes
                ) {

                    if (
                        !in_array(
                            $value,
                            $allowedTeachingTypes,
                            true
                        )
                    ) {

                        $fail(
                            __('student.invalid_lesson_type')
                        );
                    }
                },
            ];
        }


        $validated =
            $request->validate(
                $validationRules,
                [
                    'teaching_type.required' =>
                        __('student.select_lesson_type_required'),
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | FINAL TEACHING TYPE
        |--------------------------------------------------------------------------
        */

        if (count($allowedTeachingTypes) === 1) {

            /*
             * Teacher offered only one type.
             *
             * IMPORTANT:
             * We do NOT trust anything sent by Student.
             * Backend itself chooses teacher's only available type.
             */

            $teachingType =
                $allowedTeachingTypes[0];

        } else {

            /*
             * Teacher offered two or three types.
             * Student selected one of teacher's allowed types.
             */

            $teachingType =
                $validated['teaching_type'];
        }


        /*
        |--------------------------------------------------------------------------
        | OPTIONAL MESSAGE
        |--------------------------------------------------------------------------
        */

        $messageText = trim(
            $validated['message'] ?? ''
        );


        /*
        |--------------------------------------------------------------------------
        | BLOCK CONTACT INFORMATION
        |--------------------------------------------------------------------------
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
        | GET HOURLY RATE
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
            ->value(
                'hourly_rate'
            );


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


        $hourlyRate =
            (float) $hourlyRate;


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
        | CALCULATE PRICE
        |--------------------------------------------------------------------------
        */

        $price = round(
            $hourlyRate * ($duration / 60),
            2
        );


        /*
        |--------------------------------------------------------------------------
        | CREATE BOOKING + OPTIONAL FIRST MESSAGE
        |--------------------------------------------------------------------------
        */

        $booking = DB::transaction(
            function () use (
                $student,
                $availability,
                $duration,
                $price,
                $teachingType,
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

                    'teaching_type' =>
                        $teachingType,

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
        | USERS
        |--------------------------------------------------------------------------
        */

        $studentUser =
            $booking->student?->user;


        $teacherUser =
            $booking->teacher?->user;


        /*
        |--------------------------------------------------------------------------
        | BOOKING ACTIVITY NOTIFICATION
        |--------------------------------------------------------------------------
        |
        | Your old controller required BookingActivityNotifier directly.
        |
        | Because that class currently does not exist, Laravel crashed before
        | the booking request could even run.
        |
        | Now:
        | - If the service exists, use it.
        | - If it does not exist yet, booking still works.
        |
        */

        $activityNotifierClass =
            'App\\Services\\BookingActivityNotifier';


        if (class_exists($activityNotifierClass)) {

            try {

                app($activityNotifierClass)->notifyBoth(
                    booking: $booking,
                    action: 'request_created',
                    actorRole: 'student',
                    actorName: $studentUser?->name
                        ?? Auth::user()?->name
                );

            } catch (\Throwable $exception) {

                report(
                    $exception
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | OPTIONAL MESSAGE NOTIFICATION
        |--------------------------------------------------------------------------
        */

        if (
            $teacherUser
            &&
            $messageText !== ''
        ) {

            $bookingMessage = BookingMessage::where(
                    'booking_id',
                    $booking->id
                )
                ->latest(
                    'id'
                )
                ->first();


            if ($bookingMessage) {

                try {

                    $teacherUser->notify(
                        new BookingMessageNotification(
                            $booking,
                            $bookingMessage,
                            $studentUser?->name
                                ?? Auth::user()?->name
                                ?? 'Student'
                        )
                    );

                } catch (\Throwable $exception) {

                    report(
                        $exception
                    );
                }
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