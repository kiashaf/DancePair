<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REVIEWS PAGE
    |--------------------------------------------------------------------------
    |
    | Reviewهایی که Studentها برای این Teacher نوشته‌اند
    |
    */

    public function index()
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        $reviews = Review::with([
            'student.user',
            'booking.danceStyle',
        ])
            ->where('teacher_id', $teacher->id)
            ->where('reviewer_type', 'student')
            ->where('approved', true)
            ->latest()
            ->get();


        $averageRating = $reviews->avg('rating') ?? 0;

        $reviewCount = $reviews->count();


        return view(
            'teacher.reviews',
            compact(
                'teacher',
                'reviews',
                'averageRating',
                'reviewCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TEACHER REVIEWS A STUDENT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Booking $booking)
    {
        $teacher = Teacher::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        |
        | Teacher فقط می‌تواند Student مربوط به Booking خودش را Review کند.
        |
        */

        abort_unless(
            (int) $booking->teacher_id === (int) $teacher->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'comment' => [
                'nullable',
                'string',
                'max:1500',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | CREATE OR UPDATE REVIEW
        |--------------------------------------------------------------------------
        |
        | اگر قبلاً Review نوشته باشد، همان Review آپدیت می‌شود.
        |
        */

        Review::updateOrCreate(

            [
                'booking_id' => $booking->id,

                'student_id' => $booking->student_id,

                'teacher_id' => $teacher->id,

                'reviewer_type' => 'teacher',
            ],

            [
                'rating' => $validated['rating'],

                'comment' => $validated['comment'] ?? null,

                'approved' => true,
            ]

        );


        return back()->with(
            'success',
            'Your review for the student has been saved.'
        );
    }
}