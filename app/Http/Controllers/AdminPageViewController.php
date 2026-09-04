<?php

namespace App\Http\Controllers;

use App\Models\PageView;

class AdminPageViewController extends Controller
{
    public function index()
    {
        $now =
            now();


        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalViews =
            PageView::count();


        $todayViews =
            PageView::where(
                'visited_at',
                '>=',
                $now->copy()->startOfDay()
            )
                ->count();


        $last7DaysViews =
            PageView::where(
                'visited_at',
                '>=',
                $now
                    ->copy()
                    ->subDays(6)
                    ->startOfDay()
            )
                ->count();


        $last30DaysViews =
            PageView::where(
                'visited_at',
                '>=',
                $now
                    ->copy()
                    ->subDays(29)
                    ->startOfDay()
            )
                ->count();


        $uniqueVisitors =
            PageView::distinct()
                ->count(
                    'visitor_hash'
                );


        $todayUniqueVisitors =
            PageView::where(
                'visited_at',
                '>=',
                $now->copy()->startOfDay()
            )
                ->distinct()
                ->count(
                    'visitor_hash'
                );


        /*
        |--------------------------------------------------------------------------
        | MOST VIEWED PAGES
        |--------------------------------------------------------------------------
        */

        $mostViewedPages =
            PageView::query()

                ->selectRaw(
                    '
                    path,
                    COUNT(*) AS views,
                    COUNT(DISTINCT visitor_hash) AS unique_visitors
                    '
                )

                ->groupBy(
                    'path'
                )

                ->orderByDesc(
                    'views'
                )

                ->limit(8)

                ->get();


        /*
        |--------------------------------------------------------------------------
        | LOCATIONS
        |--------------------------------------------------------------------------
        */

        $visitorLocations =
            PageView::query()

                ->selectRaw(
                    '
                    country_code,
                    country_name,
                    COUNT(*) AS views,
                    COUNT(DISTINCT visitor_hash) AS visitors
                    '
                )

                ->whereNotNull(
                    'country_name'
                )

                ->groupBy(
                    'country_code',
                    'country_name'
                )

                ->orderByDesc(
                    'visitors'
                )

                ->limit(8)

                ->get();


        /*
        |--------------------------------------------------------------------------
        | CANADA
        |--------------------------------------------------------------------------
        */

        $canadaVisitors =
            PageView::query()

                ->where(
                    'country_code',
                    'CA'
                )

                ->distinct()

                ->count(
                    'visitor_hash'
                );


        /*
        |--------------------------------------------------------------------------
        | RECENT
        |--------------------------------------------------------------------------
        */

        $recentViews =
            PageView::query()

                ->with([
                    'user:id,name,email,role',
                ])

                ->latest(
                    'visited_at'
                )

                ->limit(12)

                ->get();


        return view(
            'admin.page-views',
            compact(
                'totalViews',
                'todayViews',
                'last7DaysViews',
                'last30DaysViews',
                'uniqueVisitors',
                'todayUniqueVisitors',
                'mostViewedPages',
                'visitorLocations',
                'canadaVisitors',
                'recentViews'
            )
        );
    }
}