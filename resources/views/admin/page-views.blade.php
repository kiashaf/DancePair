@extends('admin.layout')

@section('title', 'Page Views')
@section('page-title', 'Page Views')

@section('content')

@php

    $names = [

        '/' =>
            'Home',

        '/login' =>
            'Login',

        '/register' =>
            'Register',

        '/student/dashboard' =>
            'Student Dashboard',

        '/student/bookings' =>
            'My Bookings',

        '/student/teachers' =>
            'Find Teachers',

        '/student/profile' =>
            'Student Profile',

        '/student/payments' =>
            'My Payments',

        '/teacher/dashboard' =>
            'Teacher Dashboard',

        '/teacher/bookings' =>
            'Teacher Bookings',

        '/teacher/profile' =>
            'Teacher Profile',

        '/teacher/availability' =>
            'Availability',

        '/teacher/earnings' =>
            'Earnings',
    ];


    $pageName =
        function ($path) use ($names) {

            if (
                isset(
                    $names[$path]
                )
            ) {

                return $names[$path];
            }


            $value =
                trim(
                    $path,
                    '/'
                );


            if ($value === '') {

                return 'Home';
            }


            return ucwords(
                str_replace(
                    [
                        '/',
                        '-',
                        '_'
                    ],
                    ' ',
                    $value
                )
            );
        };


    $locationName =
        function ($view) {

            $parts =
                array_filter([
                    $view->city,
                    $view->region_name,
                    $view->country_name,
                ]);


            return count($parts)
                ? implode(
                    ' · ',
                    $parts
                )
                : 'Unknown';
        };

@endphp


<style>

/* =========================================================
   PAGE
========================================================= */

.pv {
    display: flex;
    flex-direction: column;
    gap: 16px;
}


/* =========================================================
   TOP SUMMARY
========================================================= */

.pv-summary {
    display: grid;

    grid-template-columns:
        repeat(
            4,
            minmax(0, 1fr)
        );

    gap: 12px;
}


.pv-summary-item {
    padding: 18px;

    border: 1px solid #DCE7E1;
    border-radius: 14px;

    background: #FFFFFF;
}


.pv-summary-item.primary {
    border-color: #BFDCCA;

    background: #F4FAF6;
}


.pv-label {
    margin-bottom: 8px;

    color: #718096;

    font-size: 9px;
    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .04em;
}


.pv-value {
    color: #111827;

    font-size: 28px;
    font-weight: 800;

    line-height: 1;
}


.pv-summary-item.primary
.pv-value {
    color: #147447;
}


.pv-note {
    margin-top: 7px;

    color: #94A3B8;

    font-size: 9px;
}


/* =========================================================
   TWO COLUMNS
========================================================= */

.pv-columns {
    display: grid;

    grid-template-columns:
        .7fr
        1.3fr;

    gap: 16px;

    align-items: start;
}


/* =========================================================
   CARD
========================================================= */

.pv-card {
    overflow: hidden;

    border: 1px solid #DCE7E1;
    border-radius: 14px;

    background: #FFFFFF;
}


.pv-card-title {
    padding: 15px 18px;

    border-bottom: 1px solid #EDF2EF;

    color: #111827;

    font-size: 12px;
    font-weight: 800;
}


/* =========================================================
   LOCATION
========================================================= */

.pv-location-list {
    padding: 7px 0;
}


.pv-location {
    display: grid;

    grid-template-columns:
        42px
        minmax(0, 1fr)
        auto;

    gap: 12px;

    align-items: center;

    min-height: 55px;

    padding: 7px 18px;
}


.pv-location +
.pv-location {
    border-top: 1px solid #F1F5F3;
}


.pv-country-code {
    display: inline-flex;

    align-items: center;
    justify-content: center;

    width: 40px;
    height: 28px;

    border-radius: 7px;

    background: #EFF6F2;

    color: #167049;

    font-size: 9px;
    font-weight: 800;
}


.pv-country-name {
    color: #1E293B;

    font-size: 10px;
    font-weight: 700;
}


.pv-country-sub {
    margin-top: 3px;

    color: #94A3B8;

    font-size: 8px;
}


.pv-country-count {
    text-align: right;

    color: #111827;

    font-size: 11px;
    font-weight: 800;
}


.pv-country-count span {
    display: block;

    margin-top: 2px;

    color: #94A3B8;

    font-size: 7px;
    font-weight: 500;
}


/* =========================================================
   PAGES
========================================================= */

.pv-pages-head,
.pv-page {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        70px
        80px;

    gap: 12px;

    align-items: center;
}


.pv-pages-head {
    padding: 9px 18px;

    background: #F8FAF9;

    color: #94A3B8;

    font-size: 7px;
    font-weight: 800;

    text-transform: uppercase;
}


.pv-page {
    min-height: 55px;

    padding: 8px 18px;

    border-top: 1px solid #F1F5F3;
}


.pv-page-name {
    color: #1E293B;

    font-size: 10px;
    font-weight: 700;
}


.pv-page-path {
    margin-top: 3px;

    color: #94A3B8;

    font-size: 8px;
}


.pv-number {
    text-align: right;

    color: #111827;

    font-size: 10px;
    font-weight: 800;
}


/* =========================================================
   RECENT
========================================================= */

.pv-recent-head,
.pv-recent {
    display: grid;

    grid-template-columns:
        140px
        minmax(170px, 1fr)
        minmax(240px, 1fr)
        170px;

    gap: 16px;

    align-items: center;
}


.pv-recent-head {
    padding: 9px 18px;

    background: #F8FAF9;

    color: #94A3B8;

    font-size: 7px;
    font-weight: 800;

    text-transform: uppercase;
}


.pv-recent {
    min-height: 57px;

    padding: 8px 18px;

    border-top: 1px solid #F1F5F3;
}


.pv-visitor {
    color: #334155;

    font-size: 10px;
    font-weight: 700;
}


.pv-guest {
    color: #94A3B8;

    font-weight: 500;
}


.pv-recent-page-name {
    color: #1E293B;

    font-size: 10px;
    font-weight: 700;
}


.pv-recent-path {
    margin-top: 3px;

    color: #94A3B8;

    font-size: 8px;
}


.pv-location-text {
    color: #475569;

    font-size: 9px;
}


.pv-location-text strong {
    color: #176F49;

    font-size: 9px;
    font-weight: 700;
}


.pv-time {
    text-align: right;

    color: #64748B;

    font-size: 9px;
}


/* =========================================================
   EMPTY
========================================================= */

.pv-empty {
    padding: 30px 18px;

    text-align: center;

    color: #94A3B8;

    font-size: 9px;
}


/* =========================================================
   FOOT
========================================================= */

.pv-foot {
    display: flex;

    justify-content: flex-end;

    color: #94A3B8;

    font-size: 8px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1050px) {

    .pv-summary {
        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );
    }


    .pv-columns {
        grid-template-columns: 1fr;
    }

}


@media(max-width: 750px) {

    .pv-recent-head,
    .pv-recent {
        grid-template-columns:
            100px
            minmax(140px, 1fr)
            180px
            130px;

        gap: 8px;
    }

}


@media(max-width: 520px) {

    .pv-summary {
        grid-template-columns: 1fr;
    }

}

</style>



<div class="admin-page-card">

    <div class="pv">


        {{-- =================================================
           SUMMARY
        ================================================== --}}

        <div class="pv-summary">


            <div class="pv-summary-item primary">

                <div class="pv-label">
                    Today
                </div>

                <div class="pv-value">
                    {{ number_format($todayViews) }}
                </div>

                <div class="pv-note">

                    {{ number_format(
                        $todayUniqueVisitors
                    ) }}

                    unique visitors

                </div>

            </div>



            <div class="pv-summary-item">

                <div class="pv-label">
                    Last 7 Days
                </div>

                <div class="pv-value">
                    {{ number_format($last7DaysViews) }}
                </div>

                <div class="pv-note">
                    Page views
                </div>

            </div>



            <div class="pv-summary-item">

                <div class="pv-label">
                    Last 30 Days
                </div>

                <div class="pv-value">
                    {{ number_format($last30DaysViews) }}
                </div>

                <div class="pv-note">
                    Page views
                </div>

            </div>



            <div class="pv-summary-item">

                <div class="pv-label">
                    Unique Visitors
                </div>

                <div class="pv-value">
                    {{ number_format($uniqueVisitors) }}
                </div>

                <div class="pv-note">

                    {{ number_format($canadaVisitors) }}

                    from Canada

                </div>

            </div>


        </div>



        {{-- =================================================
           LOCATION + PAGES
        ================================================== --}}

        <div class="pv-columns">


            {{-- LOCATIONS --}}

            <div class="pv-card">

                <div class="pv-card-title">
                    Visitor Locations
                </div>


                @if($visitorLocations->count())


                    <div class="pv-location-list">


                        @foreach($visitorLocations as $location)


                            <div class="pv-location">


                                <div class="pv-country-code">

                                    {{ $location->country_code
                                        ?: '--'
                                    }}

                                </div>


                                <div>

                                    <div class="pv-country-name">

                                        {{ $location->country_name
                                            ?: 'Unknown'
                                        }}

                                    </div>

                                    <div class="pv-country-sub">

                                        {{ number_format(
                                            $location->views
                                        ) }}

                                        page views

                                    </div>

                                </div>


                                <div class="pv-country-count">

                                    {{ number_format(
                                        $location->visitors
                                    ) }}

                                    <span>
                                        visitors
                                    </span>

                                </div>


                            </div>


                        @endforeach


                    </div>


                @else


                    <div class="pv-empty">

                        Location data will appear after
                        real visitors access DancePair.ca.

                    </div>


                @endif


            </div>



            {{-- MOST VIEWED --}}

            <div class="pv-card">

                <div class="pv-card-title">
                    Most Viewed Pages
                </div>


                @if($mostViewedPages->count())


                    <div class="pv-pages-head">

                        <div>
                            Page
                        </div>

                        <div style="text-align:right;">
                            Views
                        </div>

                        <div style="text-align:right;">
                            Visitors
                        </div>

                    </div>


                    @foreach($mostViewedPages as $page)


                        <div class="pv-page">


                            <div>

                                <div class="pv-page-name">

                                    {{ $pageName(
                                        $page->path
                                    ) }}

                                </div>

                                <div class="pv-page-path">

                                    {{ $page->path }}

                                </div>

                            </div>


                            <div class="pv-number">

                                {{ number_format(
                                    $page->views
                                ) }}

                            </div>


                            <div class="pv-number">

                                {{ number_format(
                                    $page->unique_visitors
                                ) }}

                            </div>


                        </div>


                    @endforeach


                @else


                    <div class="pv-empty">
                        No page views yet.
                    </div>


                @endif


            </div>


        </div>



        {{-- =================================================
           RECENT VISITORS
        ================================================== --}}

        <div class="pv-card">


            <div class="pv-card-title">
                Recent Visits
            </div>


            @if($recentViews->count())


                <div class="pv-recent-head">

                    <div>
                        Visitor
                    </div>

                    <div>
                        Page
                    </div>

                    <div>
                        Location
                    </div>

                    <div style="text-align:right;">
                        Time
                    </div>

                </div>


                @foreach($recentViews as $view)


                    <div class="pv-recent">


                        <div
                            class="
                                pv-visitor
                                {{ !$view->user
                                    ? 'pv-guest'
                                    : ''
                                }}
                            "
                        >

                            {{ $view->user
                                ? $view->user->name
                                : 'Guest'
                            }}

                        </div>


                        <div>

                            <div class="pv-recent-page-name">

                                {{ $pageName(
                                    $view->path
                                ) }}

                            </div>

                            <div class="pv-recent-path">

                                {{ $view->path }}

                            </div>

                        </div>


                        <div class="pv-location-text">


                            @if($view->country_name)

                                <strong>

                                    {{ $view->country_name }}

                                </strong>


                                @if(
                                    $view->region_name
                                    ||
                                    $view->city
                                )

                                    ·

                                    {{ collect([
                                            $view->region_name,
                                            $view->city
                                        ])
                                        ->filter()
                                        ->implode(' · ')
                                    }}

                                @endif


                            @else

                                Unknown

                            @endif


                        </div>


                        <div class="pv-time">

                            {{ $view->visited_at
                                ?->format(
                                    'M d, Y · g:i A'
                                )
                                ?? '—'
                            }}

                        </div>


                    </div>


                @endforeach


            @else


                <div class="pv-empty">
                    No visits yet.
                </div>


            @endif


        </div>



        <div class="pv-foot">

            Total tracked page views:
            &nbsp;

            <strong>
                {{ number_format($totalViews) }}
            </strong>

        </div>


    </div>

</div>

@endsection