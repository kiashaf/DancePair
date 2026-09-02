<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT REVIEWS PAGE
    |--------------------------------------------------------------------------
    |
    | Reviewهایی که Teacherها برای این Student نوشته‌اند
    |
    */

    public function index()
    {
        $student = Student::where('user_id', Auth::id())
            ->firstOrFail();

        $reviews = Review::with([
            'teacher.user',
            'booking.danceStyle',
        ])
            ->where('student_id', $student->id)
            ->where('reviewer_type', 'teacher')
            ->where('approved', true)
            ->latest()
            ->get();

        $averageRating = $reviews->avg('rating') ?? 0;

        $reviewCount = $reviews->count();

        return view(
            'student.reviews',
            compact(
                'student',
                'reviews',
                'averageRating',
                'reviewCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT REVIEWS TEACHER
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Booking $booking)
    {
        $student = Student::where('user_id', Auth::id())
            ->firstOrFail();

        abort_unless(
            (int) $booking->student_id === (int) $student->id,
            403
        );

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

        Review::updateOrCreate(
            [
                'booking_id' => $booking->id,
                'student_id' => $student->id,
                'teacher_id' => $booking->teacher_id,
                'reviewer_type' => 'student',
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'approved' => true,
            ]
        );

        return back()->with(
            'success',
            'Your review for the teacher has been saved.'
        );
    }
}