<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\DanceStyle;
use App\Models\Review;
use App\Models\Booking;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentTeacherController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FIND TEACHERS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | DANCE STYLES FOR FILTER
        |--------------------------------------------------------------------------
        */

        $danceStyles = DanceStyle::where(
                'active',
                true
            )
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VALIDATE FILTERS
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'teacher_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'dance_style_id' => [
                'nullable',
                'integer',
                'exists:dance_styles,id',
            ],

            'teaching_type' => [
                'nullable',
                'in:face_to_face,public_place,online',
            ],

            'availability_date' => [
                'nullable',
                'date',
            ],

            'availability_day' => [
                'nullable',
                'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            ],

            'availability_time' => [
                'nullable',
                'date_format:H:i',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | CHECK IF SEARCH HAS BEEN USED
        |--------------------------------------------------------------------------
        */

        $hasSearch =
            $request->filled('teacher_name')
            ||
            $request->filled('city')
            ||
            $request->filled('dance_style_id')
            ||
            $request->filled('teaching_type')
            ||
            $request->filled('availability_date')
            ||
            $request->filled('availability_day')
            ||
            $request->filled('availability_time');


        /*
        |--------------------------------------------------------------------------
        | DEFAULT
        |--------------------------------------------------------------------------
        */

        $teachers = null;


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($hasSearch) {

            $query = Teacher::with([

                'user',

                'danceStyles' => function ($query) {

                    $query->orderBy('name');
                },

            ])


            /*
            |--------------------------------------------------------------------------
            | APPROVED REVIEW COUNT
            |--------------------------------------------------------------------------
            */

            ->withCount([

                'reviews as approved_reviews_count' => function ($query) {

                    $query
                        ->where(
                            'reviewer_type',
                            'student'
                        )
                        ->where(
                            'approved',
                            true
                        );
                },

            ])


            /*
            |--------------------------------------------------------------------------
            | APPROVED REVIEW AVERAGE
            |--------------------------------------------------------------------------
            */

            ->withAvg([

                'reviews as approved_reviews_avg_rating' => function ($query) {

                    $query
                        ->where(
                            'reviewer_type',
                            'student'
                        )
                        ->where(
                            'approved',
                            true
                        );
                },

            ], 'rating')


            /*
            |--------------------------------------------------------------------------
            | ONLY TEACHER ACCOUNTS
            |--------------------------------------------------------------------------
            */

            ->whereHas(
                'user',
                function ($query) {

                    $query->where(
                        'role',
                        'teacher'
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | SEARCH BY TEACHER NAME
            |--------------------------------------------------------------------------
            */

            if ($request->filled('teacher_name')) {

                $teacherName =
                    trim(
                        $validated['teacher_name']
                    );


                $query->whereHas(
                    'user',
                    function ($q) use ($teacherName) {

                        $q->where(
                            'name',
                            'like',
                            '%' . $teacherName . '%'
                        );
                    }
                );
            }


            /*
            |--------------------------------------------------------------------------
            | SEARCH BY CITY
            |--------------------------------------------------------------------------
            */

            if ($request->filled('city')) {

                $city =
                    trim(
                        $validated['city']
                    );


                $query->where(
                    'city',
                    'like',
                    '%' . $city . '%'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | SEARCH BY DANCE STYLE
            |--------------------------------------------------------------------------
            */

            if ($request->filled('dance_style_id')) {

                $danceStyleId =
                    (int) $validated['dance_style_id'];


                $query->whereHas(
                    'danceStyles',
                    function ($q) use ($danceStyleId) {

                        $q->where(
                            'dance_styles.id',
                            $danceStyleId
                        );
                    }
                );
            }


            /*
            |--------------------------------------------------------------------------
            | SEARCH BY TEACHING TYPE
            |--------------------------------------------------------------------------
            */

            if ($request->filled('teaching_type')) {

                $teachingType =
                    $validated['teaching_type'];


                $teachingColumn =
                    match ($teachingType) {

                        'face_to_face' =>
                            'teaches_in_person',

                        'public_place' =>
                            'teaches_public_place',

                        'online' =>
                            'teaches_online',

                        default =>
                            null,
                    };


                if ($teachingColumn) {

                    $query->where(
                        $teachingColumn,
                        true
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | AVAILABILITY FILTERS
            |--------------------------------------------------------------------------
            |
            | فقط Teacher را Filter می‌کند.
            |
            | هیچ تغییری در نمایش Result Card ایجاد نمی‌کند.
            |
            */

            $hasAvailabilityFilter =
                $request->filled('availability_date')
                ||
                $request->filled('availability_day')
                ||
                $request->filled('availability_time');


            if ($hasAvailabilityFilter) {

                $availabilityDate =
                    $validated['availability_date']
                    ?? null;


                $availabilityDay =
                    $validated['availability_day']
                    ?? null;


                $availabilityTime =
                    $validated['availability_time']
                    ?? null;


                /*
                 * اگر Dance Style هم انتخاب شده باشد،
                 * Availability باید دقیقاً برای همان Dance Style باشد.
                 */

                $danceStyleId =
                    $request->filled('dance_style_id')
                        ? (int) $validated['dance_style_id']
                        : null;


                /*
                 * MySQL WEEKDAY:
                 *
                 * Monday    = 0
                 * Tuesday   = 1
                 * Wednesday = 2
                 * Thursday  = 3
                 * Friday    = 4
                 * Saturday  = 5
                 * Sunday    = 6
                 */

                $dayNumbers = [

                    'monday' =>
                        0,

                    'tuesday' =>
                        1,

                    'wednesday' =>
                        2,

                    'thursday' =>
                        3,

                    'friday' =>
                        4,

                    'saturday' =>
                        5,

                    'sunday' =>
                        6,
                ];


                $today =
                    now()->toDateString();


                $currentTime =
                    now()->format('H:i:s');


                /*
                |--------------------------------------------------------------------------
                | TEACHER MUST HAVE MATCHING AVAILABILITY
                |--------------------------------------------------------------------------
                */

                $query->whereHas(
                    'availabilities',
                    function ($availabilityQuery) use (
                        $availabilityDate,
                        $availabilityDay,
                        $availabilityTime,
                        $danceStyleId,
                        $dayNumbers,
                        $today,
                        $currentTime
                    ) {

                        /*
                         * Active only
                         */

                        $availabilityQuery
                            ->where(
                                'active',
                                1
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | FUTURE ONLY
                        |--------------------------------------------------------------------------
                        */

                        $availabilityQuery
                            ->where(
                                function ($futureQuery) use (
                                    $today,
                                    $currentTime
                                ) {

                                    /*
                                     * Future days
                                     */

                                    $futureQuery
                                        ->whereDate(
                                            'available_date',
                                            '>',
                                            $today
                                        )


                                        /*
                                         * OR today but start time
                                         * has not passed.
                                         */

                                        ->orWhere(
                                            function ($todayQuery) use (
                                                $today,
                                                $currentTime
                                            ) {

                                                $todayQuery
                                                    ->whereDate(
                                                        'available_date',
                                                        $today
                                                    )
                                                    ->whereTime(
                                                        'start_time',
                                                        '>',
                                                        $currentTime
                                                    );
                                            }
                                        );
                                }
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | EXACT DATE
                        |--------------------------------------------------------------------------
                        */

                        if ($availabilityDate) {

                            $availabilityQuery
                                ->whereDate(
                                    'available_date',
                                    $availabilityDate
                                );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | DAY OF WEEK
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $availabilityDay
                            &&
                            array_key_exists(
                                $availabilityDay,
                                $dayNumbers
                            )
                        ) {

                            $availabilityQuery
                                ->whereRaw(
                                    'WEEKDAY(available_date) = ?',
                                    [
                                        $dayNumbers[
                                            $availabilityDay
                                        ],
                                    ]
                                );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | EXACT START TIME
                        |--------------------------------------------------------------------------
                        */

                        if ($availabilityTime) {

                            $normalizedTime =
                                Carbon::createFromFormat(
                                    'H:i',
                                    $availabilityTime
                                )->format(
                                    'H:i:s'
                                );


                            $availabilityQuery
                                ->whereTime(
                                    'start_time',
                                    $normalizedTime
                                );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SAME DANCE STYLE
                        |--------------------------------------------------------------------------
                        */

                        if ($danceStyleId) {

                            $availabilityQuery
                                ->where(
                                    'dance_style_id',
                                    $danceStyleId
                                );
                        }
                    }
                );
            }


            /*
            |--------------------------------------------------------------------------
            | RESULTS
            |--------------------------------------------------------------------------
            */

            $teachers = $query
                ->orderBy('id')
                ->paginate(12)
                ->withQueryString();
        }


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'student.teachers.index',
            compact(
                'teachers',
                'danceStyles',
                'hasSearch'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VIEW TEACHER PROFILE
    |--------------------------------------------------------------------------
    */

    public function show(Teacher $teacher)
    {
        /*
        |--------------------------------------------------------------------------
        | CURRENT DATE / TIME
        |--------------------------------------------------------------------------
        */

        $today =
            now()->toDateString();


        $currentTime =
            now()->format('H:i:s');


        /*
        |--------------------------------------------------------------------------
        | LOAD TEACHER
        |--------------------------------------------------------------------------
        */

        $teacher->load([

            'user',


            /*
            |--------------------------------------------------------------------------
            | DANCE STYLES
            |--------------------------------------------------------------------------
            */

            'danceStyles' => function ($query) {

                $query->orderBy('name');
            },


            /*
            |--------------------------------------------------------------------------
            | AVAILABLE CLASSES
            |--------------------------------------------------------------------------
            */

            'availabilities' => function ($query) use (
                $today,
                $currentTime
            ) {

                $query
                    ->where(
                        'active',
                        1
                    )


                    /*
                    |--------------------------------------------------------------------------
                    | FUTURE AVAILABILITY ONLY
                    |--------------------------------------------------------------------------
                    */

                    ->where(
                        function ($availabilityQuery) use (
                            $today,
                            $currentTime
                        ) {

                            $availabilityQuery
                                ->whereDate(
                                    'available_date',
                                    '>',
                                    $today
                                )


                                ->orWhere(
                                    function ($todayQuery) use (
                                        $today,
                                        $currentTime
                                    ) {

                                        $todayQuery
                                            ->whereDate(
                                                'available_date',
                                                $today
                                            )
                                            ->whereTime(
                                                'start_time',
                                                '>',
                                                $currentTime
                                            );
                                    }
                                );
                        }
                    )


                    ->orderBy(
                        'available_date'
                    )

                    ->orderBy(
                        'start_time'
                    );
            },


            /*
            |--------------------------------------------------------------------------
            | AVAILABILITY DANCE STYLE
            |--------------------------------------------------------------------------
            */

            'availabilities.danceStyle',

        ]);


        /*
        |--------------------------------------------------------------------------
        | REVIEWS
        |--------------------------------------------------------------------------
        */

        $reviews = Review::with([

            'student.user',
            'booking',

        ])
            ->where(
                'teacher_id',
                $teacher->id
            )
            ->where(
                'reviewer_type',
                'student'
            )
            ->where(
                'approved',
                true
            )
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AVERAGE RATING
        |--------------------------------------------------------------------------
        */

        $averageRating =
            $reviews->count() > 0
                ? $reviews->avg('rating')
                : 0;


        /*
        |--------------------------------------------------------------------------
        | REVIEW COUNT
        |--------------------------------------------------------------------------
        */

        $reviewCount =
            $reviews->count();


        /*
        |--------------------------------------------------------------------------
        | CURRENT STUDENT
        |--------------------------------------------------------------------------
        */

        $student = Student::where(
            'user_id',
            Auth::id()
        )->first();


        /*
        |--------------------------------------------------------------------------
        | BOOKINGS WITH THIS TEACHER
        |--------------------------------------------------------------------------
        */

        $bookings =
            collect();


        if ($student) {

            $bookings = Booking::with([

                'danceStyle',
                'messages.sender',

            ])
                ->where(
                    'student_id',
                    $student->id
                )
                ->where(
                    'teacher_id',
                    $teacher->id
                )
                ->orderBy('id')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | MAP BOOKING TO AVAILABILITY SLOT
        |--------------------------------------------------------------------------
        */

        $bookingsBySlot =
            $bookings->keyBy(
                function ($booking) {

                    $bookingDate =
                        Carbon::parse(
                            $booking->lesson_date
                        )->format(
                            'Y-m-d'
                        );


                    $bookingTime =
                        Carbon::parse(
                            $booking->lesson_time
                        )->format(
                            'H:i:s'
                        );


                    return
                        $bookingDate
                        . '|'
                        . $bookingTime;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'student.teachers.show',
            compact(
                'teacher',
                'reviews',
                'averageRating',
                'reviewCount',
                'student',
                'bookings',
                'bookingsBySlot'
            )
        );
    }
}