<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | TOP TEACHERS
        |--------------------------------------------------------------------------
        |
        | - فقط Userهایی که واقعاً Teacher هستند
        | - Rating و Review Count
        | - Dance Styles + prices
        | - حداکثر 10 نفر
        |
        */

        $topTeachers = Teacher::with([
            'user',

            'danceStyles' => function ($query) {
                $query->orderBy('name');
            },
        ])
            ->whereHas('user', function ($query) {

                $query->where(
                    'role',
                    'teacher'
                );
            })

            ->withAvg(
                'reviews',
                'rating'
            )

            ->withCount(
                'reviews'
            )

            ->orderByDesc(
                'reviews_avg_rating'
            )

            ->orderByDesc(
                'reviews_count'
            )

            ->orderBy(
                'id'
            )

            ->take(10)

            ->get();


        return view(
            'home',
            compact(
                'topTeachers'
            )
        );
    }
}