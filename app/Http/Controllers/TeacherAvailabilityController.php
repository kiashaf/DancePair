<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\DanceStyle;
use App\Models\Teacher;
use App\Models\TeacherAvailability;
use App\Notifications\StudentAvailabilityChangedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherAvailabilityController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $teacher->load([
            'user',
            'danceStyles',
        ]);

        $availabilities = TeacherAvailability::with('danceStyle')
            ->where('teacher_id', $teacher->id)
            ->orderBy('available_date')
            ->orderBy('start_time')
            ->get();

        foreach ($availabilities as $availability) {

            $slotBookings = $this->getSlotBookings(
                $teacher,
                $availability
            );

            $hasPaidBooking = $slotBookings->contains(
                fn (Booking $booking) =>
                    $this->bookingIsPaid($booking)
            );

            $availabilityDateTime =
                $this->getAvailabilityDateTime(
                    $availability
                );

            $availability->setAttribute(
                'has_paid_booking',
                $hasPaidBooking
            );

            $availability->setAttribute(
                'can_edit',
                !$hasPaidBooking
                &&
                $availabilityDateTime->isFuture()
            );

            $availability->setAttribute(
                'can_delete',
                !$hasPaidBooking
            );
        }

        return view(
            'teacher.availability',
            compact(
                'teacher',
                'availabilities'
            )
        );
    }


    public function store(Request $request)
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        $validated = $request->validate([

            'available_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'dance_style_id' => [
                'required',
                'exists:dance_styles,id',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',

                $this->quarterHourRule(
                    __('teacher.start_time_15_minute_error')
                ),
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',

                $this->quarterHourRule(
                    __('teacher.end_time_15_minute_error')
                ),
            ],
        ]);


        if (
            !$this->teacherHasDanceStyle(
                $teacher,
                (int) $validated['dance_style_id']
            )
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    __('teacher.invalid_dance_style')
                );
        }


        $newDateTime =
            Carbon::createFromFormat(
                'Y-m-d H:i',
                $validated['available_date']
                . ' '
                . $validated['start_time']
            );


        if ($newDateTime->lte(now())) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    __('teacher.availability_must_be_future')
                );
        }


        TeacherAvailability::create([

            'teacher_id' =>
                $teacher->id,

            'dance_style_id' =>
                $validated['dance_style_id'],

            'available_date' =>
                $validated['available_date'],

            'start_time' =>
                $validated['start_time'],

            'end_time' =>
                $validated['end_time'],

            'active' =>
                true,
        ]);


        return back()->with(
            'success',
            __('teacher.availability_added_successfully')
        );
    }


    public function update(
        Request $request,
        TeacherAvailability $availability
    ) {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        $teacher->loadMissing('user');


        abort_unless(
            (int) $availability->teacher_id
            ===
            (int) $teacher->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | PAST AVAILABILITY CANNOT BE EDITED
        |--------------------------------------------------------------------------
        */

        if (
            $this
                ->getAvailabilityDateTime(
                    $availability
                )
                ->lte(now())
        ) {

            return back()->with(
                'error',
                __('teacher.past_availability_cannot_be_edited')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FIND BOOKINGS ON OLD SLOT
        |--------------------------------------------------------------------------
        */

        $slotBookings =
            $this->getSlotBookings(
                $teacher,
                $availability
            );


        /*
        |--------------------------------------------------------------------------
        | PAID = LOCKED
        |--------------------------------------------------------------------------
        */

        if (
            $slotBookings->contains(
                fn (Booking $booking) =>
                    $this->bookingIsPaid($booking)
            )
        ) {

            return back()->with(
                'error',
                __('teacher.paid_availability_cannot_be_edited')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE EDIT
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'edit_availability_id' => [
                'required',
                'integer',
            ],

            'available_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'dance_style_id' => [
                'required',
                'exists:dance_styles,id',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',

                $this->quarterHourRule(
                    __('teacher.start_time_15_minute_error')
                ),
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',

                $this->quarterHourRule(
                    __('teacher.end_time_15_minute_error')
                ),
            ],
        ]);


        abort_unless(
            (int) $validated['edit_availability_id']
            ===
            (int) $availability->id,
            403
        );


        if (
            !$this->teacherHasDanceStyle(
                $teacher,
                (int) $validated['dance_style_id']
            )
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    __('teacher.invalid_dance_style')
                );
        }


        /*
        |--------------------------------------------------------------------------
        | NEW TIME MUST ALSO BE FUTURE
        |--------------------------------------------------------------------------
        */

        $newDateTime =
            Carbon::createFromFormat(
                'Y-m-d H:i',
                $validated['available_date']
                . ' '
                . $validated['start_time']
            );


        if ($newDateTime->lte(now())) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    __('teacher.availability_must_be_future')
                );
        }


        /*
        |--------------------------------------------------------------------------
        | OLD VALUES
        |--------------------------------------------------------------------------
        */

        $oldDate =
            Carbon::parse(
                $availability->available_date
            )->format('Y-m-d');


        $oldStart =
            Carbon::parse(
                $availability->start_time
            )->format('H:i');


        $oldEnd =
            Carbon::parse(
                $availability->end_time
            )->format('H:i');


        /*
        |--------------------------------------------------------------------------
        | DID SOMETHING CHANGE?
        |--------------------------------------------------------------------------
        */

        $somethingChanged =
            $oldDate
                !==
                $validated['available_date']

            ||

            (int) $availability->dance_style_id
                !==
                (int) $validated['dance_style_id']

            ||

            $oldStart
                !==
                $validated['start_time']

            ||

            $oldEnd
                !==
                $validated['end_time'];


        if (!$somethingChanged) {

            return back()->with(
                'success',
                __('teacher.no_changes_made')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | OLD DETAILS
        |--------------------------------------------------------------------------
        */

        $oldRate =
            $this->getStyleRate(
                $teacher->id,
                (int) $availability->dance_style_id
            );


        $oldDetails =
            $this->makeLessonDetails(
                $availability->available_date,
                $availability->start_time,
                $availability->end_time,
                (int) $availability->dance_style_id,
                $oldRate
            );


        /*
        |--------------------------------------------------------------------------
        | NEW DETAILS
        |--------------------------------------------------------------------------
        */

        $newRate =
            $this->getStyleRate(
                $teacher->id,
                (int) $validated['dance_style_id']
            );


        $newDetails =
            $this->makeLessonDetails(
                $validated['available_date'],
                $validated['start_time'],
                $validated['end_time'],
                (int) $validated['dance_style_id'],
                $newRate
            );


        $notifications =
            collect();


        /*
        |--------------------------------------------------------------------------
        | UPDATE + REMOVE OLD UNPAID REQUESTS
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $availability,
                $validated,
                $slotBookings,
                $oldDetails,
                &$notifications
            ) {

                $availability->update([

                    'available_date' =>
                        $validated['available_date'],

                    'dance_style_id' =>
                        $validated['dance_style_id'],

                    'start_time' =>
                        $validated['start_time'],

                    'end_time' =>
                        $validated['end_time'],
                ]);


                foreach ($slotBookings as $booking) {

                    /*
                     * Only pending / confirmed AND unpaid
                     */

                    if (
                        !$this->bookingCanBeInvalidated(
                            $booking
                        )
                    ) {
                        continue;
                    }


                    $studentUser =
                        $booking
                            ->student
                            ?->user;


                    $bookingOldDetails =
                        $oldDetails;


                    /*
                     * Use actual booking price
                     */

                    $bookingOldDetails['price'] =
                        $booking->price !== null
                            ? (float) $booking->price
                            : $oldDetails['price'];


                    /*
                     * Remove pending/failed payment record
                     */

                    if (
                        $booking->payment
                        &&
                        $booking->payment->status !== 'paid'
                    ) {

                        $booking
                            ->payment
                            ->delete();
                    }


                    /*
                     * Remove request completely
                     *
                     * Student must request again
                     */

                    $booking->delete();


                    if ($studentUser) {

                        $notifications->push([

                            'user' =>
                                $studentUser,

                            'old_details' =>
                                $bookingOldDetails,
                        ]);
                    }
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | EMAIL + DATABASE NOTIFICATION
        |--------------------------------------------------------------------------
        */

        foreach (
            $notifications
            as $notificationData
        ) {

            $notificationData['user']->notify(

                new StudentAvailabilityChangedNotification(

                    teacherName:
                        $teacher->user?->name
                        ?? 'Teacher',

                    teacherId:
                        (int) $teacher->id,

                    action:
                        'updated',

                    oldDetails:
                        $notificationData['old_details'],

                    newDetails:
                        $newDetails
                )
            );
        }


        return back()->with(
            'success',
            __('teacher.availability_updated_successfully')
        );
    }


    public function destroy(
        TeacherAvailability $availability
    ) {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        $teacher->loadMissing('user');


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $availability->teacher_id
            ===
            (int) $teacher->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | BOOKINGS
        |--------------------------------------------------------------------------
        */

        $slotBookings =
            $this->getSlotBookings(
                $teacher,
                $availability
            );


        /*
        |--------------------------------------------------------------------------
        | PAID = NEVER DELETE
        |--------------------------------------------------------------------------
        */

        if (
            $slotBookings->contains(
                fn (Booking $booking) =>
                    $this->bookingIsPaid($booking)
            )
        ) {

            return back()->with(
                'error',
                __('teacher.paid_availability_cannot_be_deleted')
            );
        }


        $oldRate =
            $this->getStyleRate(
                $teacher->id,
                (int) $availability->dance_style_id
            );


        $oldDetails =
            $this->makeLessonDetails(
                $availability->available_date,
                $availability->start_time,
                $availability->end_time,
                (int) $availability->dance_style_id,
                $oldRate
            );


        $notifications =
            collect();


        /*
        |--------------------------------------------------------------------------
        | REMOVE REQUESTS + DELETE AVAILABILITY
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $slotBookings,
                $availability,
                $oldDetails,
                &$notifications
            ) {

                foreach ($slotBookings as $booking) {

                    if (
                        !$this->bookingCanBeInvalidated(
                            $booking
                        )
                    ) {
                        continue;
                    }


                    $studentUser =
                        $booking
                            ->student
                            ?->user;


                    $bookingOldDetails =
                        $oldDetails;


                    $bookingOldDetails['price'] =
                        $booking->price !== null
                            ? (float) $booking->price
                            : $oldDetails['price'];


                    if (
                        $booking->payment
                        &&
                        $booking->payment->status !== 'paid'
                    ) {

                        $booking
                            ->payment
                            ->delete();
                    }


                    $booking->delete();


                    if ($studentUser) {

                        $notifications->push([

                            'user' =>
                                $studentUser,

                            'old_details' =>
                                $bookingOldDetails,
                        ]);
                    }
                }


                $availability->delete();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NOTIFY STUDENTS
        |--------------------------------------------------------------------------
        */

        foreach (
            $notifications
            as $notificationData
        ) {

            $notificationData['user']->notify(

                new StudentAvailabilityChangedNotification(

                    teacherName:
                        $teacher->user?->name
                        ?? 'Teacher',

                    teacherId:
                        (int) $teacher->id,

                    action:
                        'deleted',

                    oldDetails:
                        $notificationData['old_details'],

                    newDetails:
                        null
                )
            );
        }


        return back()->with(
            'success',
            __('teacher.availability_deleted_successfully')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BOOKINGS FOR EXACT SLOT
    |--------------------------------------------------------------------------
    */

    private function getSlotBookings(
        Teacher $teacher,
        TeacherAvailability $availability
    ) {
        $date =
            Carbon::parse(
                $availability->available_date
            )->format('Y-m-d');


        $time =
            Carbon::parse(
                $availability->start_time
            )->format('H:i:s');


        return Booking::with([

                'student.user',
                'payment',

            ])
            ->where(
                'teacher_id',
                $teacher->id
            )
            ->where(
                'dance_style_id',
                $availability->dance_style_id
            )
            ->whereDate(
                'lesson_date',
                $date
            )
            ->where(
                'lesson_time',
                $time
            )
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | PAID?
    |--------------------------------------------------------------------------
    */

    private function bookingIsPaid(
        Booking $booking
    ): bool {

        return
            (bool) $booking->paid

            ||

            $booking
                ->payment
                ?->status
                ===
                'paid';
    }


    /*
    |--------------------------------------------------------------------------
    | CAN OLD REQUEST BE REMOVED?
    |--------------------------------------------------------------------------
    */

    private function bookingCanBeInvalidated(
        Booking $booking
    ): bool {

        return
            in_array(
                $booking->status,
                [
                    'pending',
                    'confirmed',
                ],
                true
            )

            &&

            !$this->bookingIsPaid(
                $booking
            );
    }


    /*
    |--------------------------------------------------------------------------
    | AVAILABILITY DATE + START TIME
    |--------------------------------------------------------------------------
    */

    private function getAvailabilityDateTime(
        TeacherAvailability $availability
    ): Carbon {

        $date =
            Carbon::parse(
                $availability->available_date
            )->format('Y-m-d');


        $time =
            Carbon::parse(
                $availability->start_time
            )->format('H:i:s');


        return Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $date . ' ' . $time
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TEACHER OFFERS STYLE?
    |--------------------------------------------------------------------------
    */

    private function teacherHasDanceStyle(
        Teacher $teacher,
        int $danceStyleId
    ): bool {

        return $teacher
            ->danceStyles()
            ->where(
                'dance_styles.id',
                $danceStyleId
            )
            ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | HOURLY RATE
    |--------------------------------------------------------------------------
    */

    private function getStyleRate(
        int $teacherId,
        int $danceStyleId
    ): ?float {

        $rate =
            DB::table(
                'dance_style_teacher'
            )
            ->where(
                'teacher_id',
                $teacherId
            )
            ->where(
                'dance_style_id',
                $danceStyleId
            )
            ->value(
                'hourly_rate'
            );


        return $rate !== null
            ? (float) $rate
            : null;
    }


    /*
    |--------------------------------------------------------------------------
    | LESSON DETAILS + PRICE
    |--------------------------------------------------------------------------
    */

    private function makeLessonDetails(
        $date,
        $startTime,
        $endTime,
        int $danceStyleId,
        ?float $hourlyRate
    ): array {

        $start =
            Carbon::parse(
                $startTime
            );


        $end =
            Carbon::parse(
                $endTime
            );


        $duration =
            $start->diffInMinutes(
                $end
            );


        $price =
            $hourlyRate !== null
                ? round(
                    $hourlyRate
                    *
                    ($duration / 60),
                    2
                )
                : null;


        return [

            'date' =>
                Carbon::parse(
                    $date
                )->format('Y-m-d'),

            'start_time' =>
                $start->format('H:i'),

            'end_time' =>
                $end->format('H:i'),

            'duration' =>
                $duration,

            'dance_style_id' =>
                $danceStyleId,

            'dance_style' =>
                DanceStyle::find(
                    $danceStyleId
                )?->name
                ?? 'Dance',

            'hourly_rate' =>
                $hourlyRate,

            'price' =>
                $price,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | 15 MINUTE VALIDATION
    |--------------------------------------------------------------------------
    */

    private function quarterHourRule(
        string $message
    ): \Closure {

        return function (
            $attribute,
            $value,
            $fail
        ) use ($message) {

            $time =
                Carbon::createFromFormat(
                    'H:i',
                    $value
                );


            if (
                $time->minute % 15 !== 0
            ) {

                $fail(
                    $message
                );
            }
        };
    }
}