<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | FILTER OPTIONS
    |--------------------------------------------------------------------------
    */

    $teachers = Teacher::with('user')
        ->whereHas('user', function ($q) {
            $q->where('role', 'teacher');
        })
        ->orderBy('id')
        ->get();

    $students = Student::with('user')
        ->whereHas('user', function ($q) {
            $q->where('role', 'student');
        })
        ->orderBy('id')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

    $totalBookings =
        Booking::count();

    $pendingCount =
        Booking::where('status', 'pending')->count();

    $confirmedCount =
        Booking::where('status', 'confirmed')->count();

    $completedCount =
        Booking::where('status', 'completed')->count();

    $cancelledCount =
        Booking::where('status', 'cancelled')->count();

    $paidCount =
        Booking::where('paid', true)->count();

    $unpaidCount =
        Booking::where('paid', false)->count();


    /*
    |--------------------------------------------------------------------------
    | CHECK IF USER ACTUALLY SEARCHED / FILTERED
    |--------------------------------------------------------------------------
    */

    $hasFilters =
        $request->filled('search')
        || $request->filled('status')
        || $request->filled('payment')
        || $request->filled('teacher_id')
        || $request->filled('student_id')
        || $request->filled('date_from')
        || $request->filled('date_to');


    /*
    |--------------------------------------------------------------------------
    | DEFAULT: DO NOT LOAD BOOKING LIST
    |--------------------------------------------------------------------------
    */

    $bookings = null;


    /*
    |--------------------------------------------------------------------------
    | ONLY SEARCH WHEN FILTERS EXIST
    |--------------------------------------------------------------------------
    */

    if ($hasFilters) {

        $query = Booking::with([
            'student.user',
            'teacher.user',
            'danceStyle',
            'payment',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q
                    ->whereHas(
                        'student.user',
                        function ($studentQuery) use ($search) {

                            $studentQuery
                                ->where(
                                    'name',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    '%' . $search . '%'
                                );
                        }
                    )

                    ->orWhereHas(
                        'teacher.user',
                        function ($teacherQuery) use ($search) {

                            $teacherQuery
                                ->where(
                                    'name',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    '%' . $search . '%'
                                );
                        }
                    )

                    ->orWhereHas(
                        'danceStyle',
                        function ($danceQuery) use ($search) {

                            $danceQuery->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment')) {

            if ($request->payment === 'paid') {
                $query->where('paid', true);
            }

            if ($request->payment === 'unpaid') {
                $query->where('paid', false);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | TEACHER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('teacher_id')) {

            $query->where(
                'teacher_id',
                $request->teacher_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('student_id')) {

            $query->where(
                'student_id',
                $request->student_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FROM
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'lesson_date',
                '>=',
                $request->date_from
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_to')) {

            $query->whereDate(
                'lesson_date',
                '<=',
                $request->date_to
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RESULTS
        |--------------------------------------------------------------------------
        */

        $bookings = $query
            ->orderByDesc('lesson_date')
            ->orderByDesc('lesson_time')
            ->paginate(25)
            ->withQueryString();
    }


    return view(
        'admin.bookings.index',
        compact(
            'bookings',
            'teachers',
            'students',
            'totalBookings',
            'pendingCount',
            'confirmedCount',
            'completedCount',
            'cancelledCount',
            'paidCount',
            'unpaidCount',
            'hasFilters'
        )
    );
}


    /*
    |--------------------------------------------------------------------------
    | SHOW BOOKING DETAILS
    |--------------------------------------------------------------------------
    */

    public function show(Booking $booking)
    {
        $booking->load([
            'student.user',
            'teacher.user',
            'danceStyle',
            'payment',
        ]);

        return view(
            'admin.bookings.show',
            compact('booking')
        );
    }
}