<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\DanceStyle;

use Illuminate\Http\Request;


class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HOME
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | DANCE STYLES FOR SEARCH COMBO
        |--------------------------------------------------------------------------
        */

        $danceStyles = DanceStyle::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TEACHERS QUERY
        |--------------------------------------------------------------------------
        */

        $query = Teacher::query()

            ->with([
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
                'reviews' => function ($query) {

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
                'reviews' => function ($query) {

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
            | ONLY REAL + ACTIVE TEACHER ACCOUNTS
            |--------------------------------------------------------------------------
            */

            ->whereHas(
                'user',
                function ($query) {

                    $query
                        ->where(
                            'role',
                            'teacher'
                        )
                        ->where(
                            'active',
                            true
                        );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | CITY / LOCATION SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('location')) {

            $location =
                trim(
                    $request->input('location')
                );


            $query->where(
                function ($locationQuery) use ($location) {

                    $locationQuery
                        ->where(
                            'city',
                            'like',
                            '%' . $location . '%'
                        )

                        ->orWhere(
                            'province',
                            'like',
                            '%' . $location . '%'
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DANCE STYLE SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('dance_style_id')) {

            $danceStyleId =
                (int) $request->input(
                    'dance_style_id'
                );


            $query->whereHas(
                'danceStyles',
                function ($danceStyleQuery) use ($danceStyleId) {

                    $danceStyleQuery->where(
                        'dance_styles.id',
                        $danceStyleId
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RESULTS
        |--------------------------------------------------------------------------
        */

        $topTeachers = $query
            ->orderByDesc(
                'reviews_avg_rating'
            )
            ->orderByDesc(
                'reviews_count'
            )
            ->orderByDesc(
                'id'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'home',
            compact(
                'topTeachers',
                'danceStyles'
            )
        );
    }
}