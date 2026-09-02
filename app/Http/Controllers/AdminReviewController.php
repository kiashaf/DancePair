<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with([
            'student.user',
            'teacher.user',
            'booking.danceStyle',
        ]);

        $hasFilters =
            $request->filled('search') ||
            $request->filled('rating') ||
            $request->filled('reviewer_type') ||
            $request->filled('approved');

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->whereHas(
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
                );

                $q->orWhereHas(
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
                );

                $q->orWhere(
                    'comment',
                    'like',
                    '%' . $search . '%'
                );
            });
        }

        if ($request->filled('rating')) {
            $query->where(
                'rating',
                $request->rating
            );
        }

        if ($request->filled('reviewer_type')) {
            $query->where(
                'reviewer_type',
                $request->reviewer_type
            );
        }

        if ($request->filled('approved')) {

            if ($request->approved === 'yes') {
                $query->where('approved', true);
            }

            if ($request->approved === 'no') {
                $query->where('approved', false);
            }
        }

        $reviews = $query
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $totalReviews =
            Review::count();

        $averageRating =
            Review::avg('rating') ?? 0;

        $fiveStarReviews =
            Review::where('rating', 5)->count();

        $studentReviews =
            Review::where(
                'reviewer_type',
                'student'
            )->count();

        $teacherReviews =
            Review::where(
                'reviewer_type',
                'teacher'
            )->count();

        $approvedReviews =
            Review::where(
                'approved',
                true
            )->count();

        $pendingReviews =
            Review::where(
                'approved',
                false
            )->count();

        return view(
            'admin.reviews.index',
            compact(
                'reviews',
                'hasFilters',
                'totalReviews',
                'averageRating',
                'fiveStarReviews',
                'studentReviews',
                'teacherReviews',
                'approvedReviews',
                'pendingReviews'
            )
        );
    }


    public function show(Review $review)
    {
        $review->load([
            'student.user',
            'teacher.user',
            'booking.danceStyle',
            'booking.payment',
        ]);

        return view(
            'admin.reviews.show',
            compact('review')
        );
    }
}