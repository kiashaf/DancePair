<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\City;
use App\Models\DanceStyle;
use App\Models\Province;
use App\Models\Teacher;
use App\Models\TeacherAvailability;
use App\Notifications\PasswordChangedNotification;
use App\Notifications\StudentAvailabilityChangedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeacherProfileController extends Controller
{
    public function edit()
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $teacher->load(
            'danceStyles'
        );

        $danceStyles =
            DanceStyle::where(
                'active',
                true
            )
            ->orderBy(
                'name'
            )
            ->get();

        $provinces =
            Province::orderBy(
                'name'
            )->get();

        $selectedProvince =
            Province::where(
                'name',
                $teacher->province
            )->first();

        $cities =
            collect();

        if ($selectedProvince) {

            $cities =
                City::where(
                    'province_id',
                    $selectedProvince->id
                )
                ->orderBy(
                    'name'
                )
                ->get();
        }

        return view(
            'teacher.profile',
            compact(
                'teacher',
                'danceStyles',
                'provinces',
                'selectedProvince',
                'cities'
            )
        );
    }


    public function update(
        Request $request
    ) {
        $user =
            Auth::user();

        $teacher =
            Teacher::where(
                'user_id',
                $user->id
            )->firstOrFail();


        $teacher->load(
            'danceStyles'
        );


        /*
        |--------------------------------------------------------------------------
        | OLD DANCE STYLE RATES
        |--------------------------------------------------------------------------
        */

        $oldDanceRates =
            $teacher
                ->danceStyles
                ->mapWithKeys(
                    function ($style) {

                        return [

                            (int) $style->id =>

                                $style
                                    ->pivot
                                    ->hourly_rate
                                    !==
                                    null

                                    ? (float) $style
                                        ->pivot
                                        ->hourly_rate

                                    : null,
                        ];
                    }
                )
                ->all();


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate([

                /*
                |--------------------------------------------------------------------------
                | ACCOUNT
                |--------------------------------------------------------------------------
                */

                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],


                'profile_photo' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120',
                ],


                'intro_video' => [
                    'nullable',
                    'file',
                    'mimes:mp4,mov,webm',
                    'max:51200',
                ],


                'email' => [
                    'required',
                    'email',
                    'max:255',

                    Rule::unique(
                        'users',
                        'email'
                    )->ignore(
                        $user->id
                    ),
                ],


                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'confirmed',
                ],


                /*
                |--------------------------------------------------------------------------
                | TEACHER PROFILE
                |--------------------------------------------------------------------------
                */

                'bio' => [
                    'nullable',
                    'string',
                    'max:3000',
                ],


                'experience_years' => [
                    'nullable',
                    'integer',
                    'min:0',
                    'max:80',
                ],


                'hourly_rate' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],


                'province' => [
                    'nullable',
                    'integer',
                    'exists:provinces,id',
                ],


                'city' => [
                    'nullable',
                    'string',
                    'max:255',
                ],


                'country' => [
                    'nullable',
                    'string',
                    'max:255',
                ],


                /*
                |--------------------------------------------------------------------------
                | DANCE STYLES
                |--------------------------------------------------------------------------
                */

                'dance_styles' => [
                    'nullable',
                    'array',
                ],


                'dance_styles.*' => [
                    'integer',
                    'exists:dance_styles,id',
                ],


                /*
                |--------------------------------------------------------------------------
                | DANCE STYLE PRICES
                |--------------------------------------------------------------------------
                */

                'dance_rates' => [
                    'nullable',
                    'array',
                ],


                'dance_rates.*' => [
                    'nullable',
                    'numeric',
                    'min:0',
                    'max:99999.99',
                ],
            ]);


        /*
        |--------------------------------------------------------------------------
        | PREPARE DANCE STYLES + RATES FIRST
        |--------------------------------------------------------------------------
        |
        | این قسمت قبل از Save انجام می‌شود
        | تا اگر Rate یکی از Styleها وارد نشده بود،
        | Profile نصفه Save نشود.
        |
        */

        $syncData =
            [];

        $selectedDanceStyles =
            $validated[
                'dance_styles'
            ]
            ?? [];

        $danceRates =
            $validated[
                'dance_rates'
            ]
            ?? [];


        foreach (
            $selectedDanceStyles
            as $styleId
        ) {

            $rate =
                $danceRates[
                    $styleId
                ]
                ?? null;


            if (
                $rate === null
                ||
                $rate === ''
            ) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'dance_rates.'
                        . $styleId
                        =>

                        'Please enter an hourly rate for every selected dance style.',
                    ]);
            }


            $syncData[
                (int) $styleId
            ] = [

                'hourly_rate' =>
                    (float) $rate,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | NEW DANCE STYLE RATES
        |--------------------------------------------------------------------------
        */

        $newDanceRates =
            collect(
                $syncData
            )
            ->mapWithKeys(
                function (
                    $data,
                    $styleId
                ) {

                    return [

                        (int) $styleId =>

                            isset(
                                $data[
                                    'hourly_rate'
                                ]
                            )

                                ? (float) $data[
                                    'hourly_rate'
                                ]

                                : null,
                    ];
                }
            )
            ->all();


        /*
        |--------------------------------------------------------------------------
        | UPDATE USER
        |--------------------------------------------------------------------------
        */

        $user->name =
            $validated[
                'name'
            ];

        $user->email =
            $validated[
                'email'
            ];


        $passwordChanged =
            false;


        if (
            !empty(
                $validated[
                    'password'
                ]
            )
        ) {

            $user->password =
                $validated[
                    'password'
                ];

            $passwordChanged =
                true;
        }


        $user->save();


        if ($passwordChanged) {

            $user->notify(

                new PasswordChangedNotification()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PROVINCE
        |--------------------------------------------------------------------------
        */

        $provinceName =
            null;


        if (
            !empty(
                $validated[
                    'province'
                ]
            )
        ) {

            $province =
                Province::find(
                    $validated[
                        'province'
                    ]
                );


            $provinceName =
                $province?->name;
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE TEACHER PROFILE
        |--------------------------------------------------------------------------
        */

        $teacher->bio =
            $validated[
                'bio'
            ]
            ?? null;


        $teacher->experience_years =
            $validated[
                'experience_years'
            ]
            ?? 0;


        /*
         * Default rate فعلاً حفظ می‌شود.
         */

        $teacher->hourly_rate =
            $validated[
                'hourly_rate'
            ]
            ?? null;


        $teacher->province =
            $provinceName;


        $teacher->city =
            $validated[
                'city'
            ]
            ?? null;


        $teacher->country =
            $validated[
                'country'
            ]
            ?? 'Canada';


        /*
        |--------------------------------------------------------------------------
        | PROFILE PHOTO
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'profile_photo'
            )
        ) {

            if (
                $teacher
                    ->profile_photo
            ) {

                Storage::disk(
                    'public'
                )->delete(
                    $teacher
                        ->profile_photo
                );
            }


            $teacher->profile_photo =
                $request
                    ->file(
                        'profile_photo'
                    )
                    ->store(
                        'teachers/profile-photos',
                        'public'
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | INTRO VIDEO
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'intro_video'
            )
        ) {

            if (
                $teacher
                    ->intro_video
            ) {

                Storage::disk(
                    'public'
                )->delete(
                    $teacher
                        ->intro_video
                );
            }


            $teacher->intro_video =
                $request
                    ->file(
                        'intro_video'
                    )
                    ->store(
                        'teachers/intro-videos',
                        'public'
                    );
        }


        $teacher->save();


        /*
        |--------------------------------------------------------------------------
        | SAVE DANCE STYLES + RATES
        |--------------------------------------------------------------------------
        */

        $teacher
            ->danceStyles()
            ->sync(
                $syncData
            );


        /*
        |--------------------------------------------------------------------------
        | RATE CHANGE PROTECTION
        |--------------------------------------------------------------------------
        |
        | اگر Rate تغییر کرد:
        |
        | PAID:
        | هیچ تغییری روی Booking نمی‌دهیم.
        |
        | UNPAID:
        | Request حذف می‌شود.
        | Pending payment حذف می‌شود.
        | Student Email می‌گیرد.
        | Student Notification می‌گیرد.
        | Student باید دوباره Request بدهد.
        |
        */

        $this
            ->invalidateUnpaidRequestsAfterRateChanges(
                $teacher,
                $oldDanceRates,
                $newDanceRates
            );


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'teacher.profile.edit'
            )
            ->with(
                'success',
                'Profile updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | INVALIDATE UNPAID REQUESTS AFTER RATE CHANGE
    |--------------------------------------------------------------------------
    */

    private function invalidateUnpaidRequestsAfterRateChanges(
        Teacher $teacher,
        array $oldRates,
        array $newRates
    ): void {

        /*
        |--------------------------------------------------------------------------
        | FIND WHICH RATES REALLY CHANGED
        |--------------------------------------------------------------------------
        */

        $changedStyleIds =
            collect(
                array_keys(
                    $oldRates
                )
            )
            ->filter(
                function (
                    $styleId
                ) use (
                    $oldRates,
                    $newRates
                ) {

                    /*
                     * Style باید قبل و بعد وجود داشته باشد.
                     *
                     * حذف کامل Style را اینجا به عنوان
                     * Rate Change حساب نمی‌کنیم.
                     */

                    if (
                        !array_key_exists(
                            $styleId,
                            $newRates
                        )
                    ) {

                        return false;
                    }


                    $oldRate =
                        $oldRates[
                            $styleId
                        ];


                    $newRate =
                        $newRates[
                            $styleId
                        ];


                    if (
                        $oldRate === null
                        &&
                        $newRate === null
                    ) {

                        return false;
                    }


                    return

                        round(
                            (float) $oldRate,
                            2
                        )

                        !==

                        round(
                            (float) $newRate,
                            2
                        );
                }
            )
            ->values();


        /*
         * هیچ Rate تغییر نکرده.
         */

        if (
            $changedStyleIds
                ->isEmpty()
        ) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD TEACHER USER FOR NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        $teacher->loadMissing(
            'user'
        );


        /*
         * Notificationها را بعد از Transaction می‌فرستیم.
         */

        $notifications =
            collect();


        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $teacher,
                $oldRates,
                $newRates,
                $changedStyleIds,
                &$notifications
            ) {

                foreach (
                    $changedStyleIds
                    as $styleId
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | FUTURE ACTIVE AVAILABILITIES
                    |--------------------------------------------------------------------------
                    */

                    $availabilities =
                        TeacherAvailability::with(
                            'danceStyle'
                        )
                        ->where(
                            'teacher_id',
                            $teacher->id
                        )
                        ->where(
                            'dance_style_id',
                            $styleId
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
                        ->get();


                    foreach (
                        $availabilities
                        as $availability
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | AVAILABILITY DATE
                        |--------------------------------------------------------------------------
                        */

                        $date =
                            Carbon::parse(
                                $availability
                                    ->available_date
                            )
                            ->format(
                                'Y-m-d'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | AVAILABILITY START TIME
                        |--------------------------------------------------------------------------
                        */

                        $startTime =
                            Carbon::parse(
                                $availability
                                    ->start_time
                            )
                            ->format(
                                'H:i:s'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | DATE + TIME
                        |--------------------------------------------------------------------------
                        */

                        $slotDateTime =
                            Carbon::createFromFormat(
                                'Y-m-d H:i:s',
                                $date
                                . ' '
                                . $startTime
                            );


                        /*
                         * اگر زمان کلاس گذشته،
                         * کاری انجام نمی‌دهیم.
                         */

                        if (
                            $slotDateTime
                                ->lte(
                                    now()
                                )
                        ) {

                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | LESSON START / END
                        |--------------------------------------------------------------------------
                        */

                        $start =
                            Carbon::parse(
                                $availability
                                    ->start_time
                            );


                        $end =
                            Carbon::parse(
                                $availability
                                    ->end_time
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | DURATION
                        |--------------------------------------------------------------------------
                        */

                        $duration =
                            $start
                                ->diffInMinutes(
                                    $end
                                );


                        if (
                            $duration <= 0
                        ) {

                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | OLD / NEW RATE
                        |--------------------------------------------------------------------------
                        */

                        $oldRate =
                            $oldRates[
                                $styleId
                            ]
                            !==
                            null

                                ? (float) $oldRates[
                                    $styleId
                                ]

                                : null;


                        $newRate =
                            (float) $newRates[
                                $styleId
                            ];


                        /*
                        |--------------------------------------------------------------------------
                        | NEW TOTAL PRICE
                        |--------------------------------------------------------------------------
                        */

                        $newPrice =
                            round(
                                $newRate
                                *
                                (
                                    $duration
                                    /
                                    60
                                ),
                                2
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | FIND REQUESTS FOR THIS EXACT SLOT
                        |--------------------------------------------------------------------------
                        */

                        $bookings =
                            Booking::with([

                                'student.user',

                                'payment',
                            ])
                            ->where(
                                'teacher_id',
                                $teacher->id
                            )
                            ->where(
                                'dance_style_id',
                                $styleId
                            )
                            ->whereDate(
                                'lesson_date',
                                $date
                            )
                            ->where(
                                'lesson_time',
                                $startTime
                            )
                            ->whereIn(
                                'status',
                                [
                                    'pending',
                                    'confirmed',
                                ]
                            )
                            ->get();


                        foreach (
                            $bookings
                            as $booking
                        ) {

                            /*
                            |--------------------------------------------------------------------------
                            | CHECK PAID
                            |--------------------------------------------------------------------------
                            |
                            | هر دو را Check می‌کنیم:
                            |
                            | bookings.paid
                            | payments.status
                            |
                            */

                            $isPaid =

                                (bool) $booking
                                    ->paid

                                ||

                                $booking
                                    ->payment
                                    ?->status
                                    ===
                                    'paid';


                            /*
                             * PAID BOOKING:
                             *
                             * به هیچ عنوان حذف نمی‌شود.
                             */

                            if ($isPaid) {

                                continue;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | STUDENT USER
                            |--------------------------------------------------------------------------
                            */

                            $studentUser =
                                $booking
                                    ->student
                                    ?->user;


                            /*
                            |--------------------------------------------------------------------------
                            | OLD TOTAL PRICE
                            |--------------------------------------------------------------------------
                            */

                            $oldPrice =

                                $booking
                                    ->price
                                    !==
                                    null

                                    ? (float) $booking
                                        ->price

                                    : (

                                        $oldRate
                                        !==
                                        null

                                            ? round(

                                                $oldRate
                                                *
                                                (
                                                    $duration
                                                    /
                                                    60
                                                ),

                                                2
                                            )

                                            : null
                                    );


                            /*
                            |--------------------------------------------------------------------------
                            | OLD DETAILS
                            |--------------------------------------------------------------------------
                            */

                            $oldDetails = [

                                'date' =>
                                    $date,


                                'start_time' =>
                                    $start->format(
                                        'H:i'
                                    ),


                                'end_time' =>
                                    $end->format(
                                        'H:i'
                                    ),


                                'duration' =>
                                    $duration,


                                'dance_style_id' =>
                                    (int) $styleId,


                                'dance_style' =>

                                    $availability
                                        ->danceStyle
                                        ?->name

                                    ??
                                    'Dance',


                                'hourly_rate' =>
                                    $oldRate,


                                'price' =>
                                    $oldPrice,
                            ];


                            /*
                            |--------------------------------------------------------------------------
                            | NEW DETAILS
                            |--------------------------------------------------------------------------
                            */

                            $newDetails = [

                                'date' =>
                                    $date,


                                'start_time' =>
                                    $start->format(
                                        'H:i'
                                    ),


                                'end_time' =>
                                    $end->format(
                                        'H:i'
                                    ),


                                'duration' =>
                                    $duration,


                                'dance_style_id' =>
                                    (int) $styleId,


                                'dance_style' =>

                                    $availability
                                        ->danceStyle
                                        ?->name

                                    ??
                                    'Dance',


                                'hourly_rate' =>
                                    $newRate,


                                'price' =>
                                    $newPrice,
                            ];


                            /*
                            |--------------------------------------------------------------------------
                            | DELETE UNPAID PAYMENT
                            |--------------------------------------------------------------------------
                            */

                            if (
                                $booking
                                    ->payment

                                &&

                                $booking
                                    ->payment
                                    ->status
                                    !==
                                    'paid'
                            ) {

                                $booking
                                    ->payment
                                    ->delete();
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | DELETE OLD UNPAID REQUEST
                            |--------------------------------------------------------------------------
                            |
                            | Student باید برای قیمت جدید
                            | دوباره Request بفرستد.
                            |
                            */

                            $booking->delete();


                            /*
                            |--------------------------------------------------------------------------
                            | SAVE NOTIFICATION DATA
                            |--------------------------------------------------------------------------
                            */

                            if ($studentUser) {

                                $notifications
                                    ->push([

                                        'user' =>
                                            $studentUser,


                                        'old_details' =>
                                            $oldDetails,


                                        'new_details' =>
                                            $newDetails,
                                    ]);
                            }
                        }
                    }
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | EMAIL + DATABASE NOTIFICATION
        |--------------------------------------------------------------------------
        |
        | بعد از اینکه DB Transaction موفق شد،
        | Notification ارسال می‌شود.
        |
        */

        foreach (
            $notifications
            as $notificationData
        ) {

            $notificationData[
                'user'
            ]->notify(

                new StudentAvailabilityChangedNotification(

                    teacherName:

                        $teacher
                            ->user
                            ?->name

                        ??
                        'Teacher',


                    teacherId:

                        (int) $teacher
                            ->id,


                    action:

                        'rate_changed',


                    oldDetails:

                        $notificationData[
                            'old_details'
                        ],


                    newDetails:

                        $notificationData[
                            'new_details'
                        ]
                )
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD CITIES BY PROVINCE
    |--------------------------------------------------------------------------
    */

    public function cities(
        $provinceId
    ) {
        $cities =
            City::where(
                'province_id',
                $provinceId
            )
            ->orderBy(
                'name'
            )
            ->get([
                'id',
                'name',
            ]);


        return response()->json(
            $cities
        );
    }
}