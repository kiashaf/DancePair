@extends('student.layout')

@section('title', __('student.find_teachers'))
@section('page-title', __('student.find_teachers'))

@section('content')


<style>

.teacher-search-page {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding-bottom: 40px;
}


/* =========================================================
   SEARCH PANEL
========================================================= */

.teacher-search-panel {
    padding: 20px;

    background:
        linear-gradient(
            135deg,
            #F7FCFF 0%,
            #EDF8FF 100%
        );

    border: 1px solid #CDE9F8;
    border-radius: 18px;

    box-shadow:
        0 7px 20px rgba(2,132,199,.04);
}


.teacher-search-header {
    margin-bottom: 16px;
}


.teacher-search-header h3 {
    margin: 0;

    font-size: 20px;
    font-weight: 800;

    color: #111827;
}


.teacher-search-header p {
    margin: 4px 0 0;

    color: #64748B;

    font-size: 10px;
}


.teacher-search-panel .form-label {
    margin-bottom: 5px;

    color: #334155;

    font-size: 10px;
    font-weight: 700;
}


.teacher-search-panel .form-control,
.teacher-search-panel .form-select {
    min-height: 42px;

    border: 1px solid #C7DDE9;
    border-radius: 9px;

    background: #FFFFFF;

    color: #1F2937;

    font-size: 11px;
}


.teacher-search-panel .form-control:focus,
.teacher-search-panel .form-select:focus {
    border-color: #0284C7;

    box-shadow:
        0 0 0 3px rgba(2,132,199,.08);
}


.teacher-search-actions {
    margin-top: 15px;

    display: flex;
    justify-content: flex-end;

    gap: 8px;
}


.teacher-search-btn {
    min-width: 135px;
    min-height: 40px;

    border: 0;
    border-radius: 9px;

    background: #0284C7;

    color: #FFFFFF;

    font-size: 10.5px;
    font-weight: 750;

    cursor: pointer;
}


.teacher-search-btn:hover {
    background: #0369A1;
}


.teacher-reset-btn {
    min-height: 40px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 14px;

    border: 1px solid #CBD5E1;
    border-radius: 9px;

    background: #FFFFFF;

    color: #64748B;

    text-decoration: none;

    font-size: 10px;
    font-weight: 700;
}


.teacher-reset-btn:hover {
    background: #F8FAFC;
    color: #334155;
}


/* =========================================================
   RESULTS HEADER
========================================================= */

.teacher-results-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 12px;
}


.teacher-results-title h3 {
    margin: 0;

    color: #111827;

    font-size: 20px;
    font-weight: 800;
}


.teacher-results-title p {
    margin: 3px 0 0;

    color: #64748B;

    font-size: 9px;
}


.teacher-results-count {
    padding: 6px 10px;

    border: 1px solid #CDE9F8;
    border-radius: 999px;

    background: #EAF6FF;

    color: #0369A1;

    font-size: 8.5px;
    font-weight: 750;

    white-space: nowrap;
}


/* =========================================================
   RESULTS GRID
========================================================= */

.teacher-results-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 12px;
}


/* =========================================================
   COMPACT CARD
========================================================= */

.teacher-result-card {
    min-width: 0;

    display: grid;

    grid-template-columns:
        minmax(175px, .85fr)
        minmax(210px, 1.35fr)
        minmax(145px, .75fr);

    align-items: center;

    gap: 14px;

    padding: 15px 16px;

    background: #FFFFFF;

    border: 1px solid #DCE8F0;
    border-radius: 16px;

    box-shadow:
        0 5px 16px rgba(15,23,42,.035);

    transition:
        transform .16s ease,
        border-color .16s ease,
        box-shadow .16s ease;
}


.teacher-result-card:hover {
    transform: translateY(-2px);

    border-color: #A8D6EE;

    box-shadow:
        0 9px 22px rgba(15,23,42,.06);
}


/* =========================================================
   LEFT SIDE - TEACHER
========================================================= */

.teacher-identity {
    min-width: 0;

    display: flex;
    align-items: center;

    gap: 11px;
}


.teacher-avatar {
    width: 58px;
    height: 58px;

    flex: 0 0 58px;

    overflow: hidden;

    border-radius: 15px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #DBF1FC;

    color: #0369A1;

    font-size: 19px;
    font-weight: 850;
}


.teacher-avatar img {
    width: 100%;
    height: 100%;

    object-fit: cover;
}


.teacher-main-info {
    min-width: 0;
}


.teacher-name-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    gap: 5px;
}


.teacher-name {
    margin: 0;

    color: #111827;

    font-size: 15px;
    font-weight: 850;
}


.teacher-verified {
    display: inline-flex;
    align-items: center;

    padding: 3px 6px;

    border-radius: 999px;

    background: #DCFCE7;

    color: #047857;

    font-size: 7px;
    font-weight: 800;
}


/* =========================================================
   RATING
========================================================= */

.teacher-rating-row {
    margin-top: 6px;

    display: flex;
    align-items: center;
    flex-wrap: wrap;

    gap: 5px;
}


.teacher-stars {
    display: inline-flex;
    align-items: center;

    gap: 1px;
}


.teacher-star {
    font-size: 17px;
    line-height: 1;
}


.teacher-star.filled {
    color: #F59E0B;
}


.teacher-star.empty {
    color: #D8E0E7;
}


.teacher-rating-number {
    margin-left: 2px;

    color: #111827;

    font-size: 10px;
    font-weight: 850;
}


.teacher-review-count {
    color: #64748B;

    font-size: 8px;
}


.teacher-new-label {
    display: inline-flex;
    align-items: center;

    padding: 4px 7px;

    border-radius: 999px;

    background: #F1F5F9;

    color: #64748B;

    font-size: 8px;
    font-weight: 750;
}


/* =========================================================
   MIDDLE - DANCE RATES
========================================================= */

.teacher-dances {
    min-width: 0;

    padding: 0 14px;

    border-left: 1px solid #EDF2F6;
    border-right: 1px solid #EDF2F6;
}


.teacher-section-label {
    margin-bottom: 7px;

    color: #64748B;

    font-size: 7.5px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .45px;
}


.teacher-dance-list {
    display: flex;
    flex-wrap: wrap;

    gap: 6px;
}


.teacher-dance-rate {
    min-width: 120px;
    flex: 1 1 120px;

    padding: 7px 8px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 7px;

    border: 1px solid #E2EAF0;
    border-radius: 8px;

    background: #F8FBFD;
}


.teacher-dance-name {
    min-width: 0;

    color: #334155;

    font-size: 8px;
    font-weight: 700;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}


.teacher-dance-price {
    flex: 0 0 auto;

    color: #0284C7;

    font-size: 8px;
    font-weight: 850;

    white-space: nowrap;
}


.teacher-no-rate {
    color: #94A3B8;

    font-size: 7.5px;
}


.teacher-more-styles {
    margin-top: 6px;

    color: #64748B;

    font-size: 8px;
    font-weight: 650;
}


/* =========================================================
   RIGHT SIDE
========================================================= */

.teacher-side-info {
    min-width: 0;

    display: flex;
    flex-direction: column;

    gap: 7px;
}


.teacher-meta {
    display: flex;
    flex-direction: column;

    gap: 4px;

    padding-bottom: 7px;

    border-bottom: 1px solid #EEF2F6;
}


.teacher-location,
.teacher-experience {
    display: flex;
    align-items: center;

    gap: 5px;

    color: #64748B;

    font-size: 8px;

    line-height: 1.3;
}


.teacher-meta-icon {
    width: 15px;

    text-align: center;

    font-size: 10px;
}


.teacher-price-from {
    color: #64748B;

    font-size: 7.5px;
}


.teacher-price-from strong {
    display: block;

    margin-top: 1px;

    color: #111827;

    font-size: 12px;
    font-weight: 850;
}


.teacher-view-profile {
    min-height: 34px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 6px;

    padding: 0 11px;

    border-radius: 8px;

    background: #0284C7;

    color: #FFFFFF;

    text-decoration: none;

    font-size: 8.5px;
    font-weight: 750;
}


.teacher-view-profile:hover {
    background: #0369A1;

    color: #FFFFFF;
}


/* =========================================================
   EMPTY
========================================================= */

.teacher-empty-result {
    padding: 40px 20px;

    text-align: center;

    border: 1px dashed #C7DDE9;
    border-radius: 16px;

    background: #F8FCFE;
}


.teacher-empty-result h4 {
    margin: 0;

    color: #111827;

    font-size: 14px;
    font-weight: 800;
}


.teacher-empty-result p {
    margin: 5px 0 0;

    color: #64748B;

    font-size: 9px;
}


/* =========================================================
   PAGINATION
========================================================= */

.teacher-pagination {
    margin-top: 6px;
}


.teacher-pagination nav {
    display: flex;
    justify-content: center;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1250px) {

    .teacher-results-grid {
        grid-template-columns: 1fr;
    }


    .teacher-result-card {
        grid-template-columns:
            minmax(190px, .9fr)
            minmax(280px, 1.5fr)
            minmax(160px, .7fr);
    }
}


@media(max-width: 850px) {

    .teacher-result-card {
        grid-template-columns: 1fr;
    }


    .teacher-dances {
        padding: 12px 0;

        border-left: 0;
        border-right: 0;
        border-top: 1px solid #EDF2F6;
        border-bottom: 1px solid #EDF2F6;
    }


    .teacher-side-info {
        display: grid;

        grid-template-columns:
            1fr auto auto;

        align-items: center;
    }


    .teacher-meta {
        border-bottom: 0;
        padding-bottom: 0;
    }
}


@media(max-width: 600px) {

    .teacher-results-header {
        align-items: flex-start;
        flex-direction: column;
    }


    .teacher-side-info {
        grid-template-columns: 1fr;
    }


    .teacher-view-profile {
        width: 100%;
    }


    .teacher-search-actions {
        flex-direction: column;
    }


    .teacher-search-btn,
    .teacher-reset-btn {
        width: 100%;
    }
}

</style>



<div class="teacher-search-page">


{{-- =========================================================
   SEARCH PANEL
========================================================= --}}

<div class="teacher-search-panel">


    <div class="teacher-search-header">

        <h3>
            {{ __('student.find_your_dance_teacher') }}
        </h3>


        <p>
            {{ __('student.teacher_search_subtitle') }}
        </p>

    </div>



    <form
        method="GET"
        action="{{ route('student.teachers') }}"
    >


        {{-- =====================================================
           ORIGINAL FILTERS
        ====================================================== --}}

        <div class="row g-3">


            {{-- TEACHER NAME --}}

            <div class="col-md-4">

                <label class="form-label">
                    {{ __('student.teacher_name') }}
                </label>


                <input
                    type="text"
                    name="teacher_name"
                    value="{{ request('teacher_name') }}"
                    class="form-control"
                    placeholder="{{ __('student.teacher_name_placeholder') }}"
                >

            </div>



            {{-- DANCE STYLE --}}

            <div class="col-md-4">

                <label class="form-label">
                    {{ __('student.dance_style') }}
                </label>


                <select
                    name="dance_style_id"
                    class="form-select"
                >

                    <option value="">
                        {{ __('student.all_dance_styles') }}
                    </option>


                    @foreach($danceStyles as $style)

                        <option
                            value="{{ $style->id }}"

                            @selected(
                                (string) request('dance_style_id')
                                ===
                                (string) $style->id
                            )
                        >

                            {{ $style->name }}

                        </option>

                    @endforeach

                </select>

            </div>



            {{-- CITY --}}

            <div class="col-md-4">

                <label class="form-label">
                    {{ __('student.city') }}
                </label>


                <input
                    type="text"
                    name="city"
                    value="{{ request('city') }}"
                    class="form-control"
                    placeholder="{{ __('student.city_placeholder') }}"
                >

            </div>


        </div>



        {{-- =====================================================
           TEACHING TYPE FILTER
        ====================================================== --}}

        <div class="row g-3 mt-1">


            <div class="col-md-4">

                <label class="form-label">

                    {{ app()->getLocale() === 'fr'
                        ? 'Type de cours'
                        : 'Teaching Type'
                    }}

                </label>


                <select
                    name="teaching_type"
                    class="form-select"
                >

                    <option value="">

                        {{ app()->getLocale() === 'fr'
                            ? 'Tous les types'
                            : 'All Teaching Types'
                        }}

                    </option>


                    <option
                        value="face_to_face"
                        @selected(
                            request('teaching_type')
                            ===
                            'face_to_face'
                        )
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'En personne'
                            : 'Face to Face'
                        }}
                    </option>


                    <option
                        value="public_place"
                        @selected(
                            request('teaching_type')
                            ===
                            'public_place'
                        )
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'Lieu public'
                            : 'Public Place'
                        }}
                    </option>


                    <option
                        value="online"
                        @selected(
                            request('teaching_type')
                            ===
                            'online'
                        )
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'En ligne'
                            : 'Online'
                        }}
                    </option>

                </select>

            </div>


        </div>



        {{-- =====================================================
           NEW AVAILABILITY FILTERS
        ====================================================== --}}

        <div class="row g-3 mt-1">


            {{-- DATE --}}

            <div class="col-md-4">

                <label class="form-label">

                    {{ app()->getLocale() === 'fr'
                        ? 'Date'
                        : 'Date'
                    }}

                </label>


                <input
                    type="date"
                    name="availability_date"
                    value="{{ request('availability_date') }}"
                    min="{{ now()->toDateString() }}"
                    class="form-control"
                >

            </div>



            {{-- DAY --}}

            <div class="col-md-4">

                <label class="form-label">

                    {{ app()->getLocale() === 'fr'
                        ? 'Jour'
                        : 'Day'
                    }}

                </label>


                <select
                    name="availability_day"
                    class="form-select"
                >

                    <option value="">

                        {{ app()->getLocale() === 'fr'
                            ? 'Tous les jours'
                            : 'Any Day'
                        }}

                    </option>


                    <option
                        value="monday"
                        @selected(
                            request('availability_day')
                            ===
                            'monday'
                        )
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'Lundi'
                            : 'Monday'
                        }}
                    </option>


                    <option
                        value="tuesday"
                        @selected(
                            request('availability_day')
                            ===
                            'tuesday'
                        )
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'Mardi'
                            : 'Tuesday'
                        }}
                    </option>


                    <option
                        value="wednesday"
                        @selected(
                            request('availability_day')
                            ===
                            'wednesday'
                        )
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'Mercredi'
                            : 'Wednesday'
                        }}
                    </option>


                    <option
                        value="thursday"
                        @selected(
                            request('availability_day')
                            ===
                            'thursday'
                        )
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'Jeudi'
                            : 'Thursday'
                        }}
                    </option>


                    <option
                        value="friday"
                        @selected(
                            request('availability_day')
                            ===
                            'friday'
                        )
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'Vendredi'
                            : 'Friday'
                        }}
                    </option>


                    <option
                        value="saturday"
                        @selected(
                            request('availability_day')
                            ===
                            'saturday'
                        )
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'Samedi'
                            : 'Saturday'
                        }}
                    </option>


                    <option
                        value="sunday"
                        @selected(
                            request('availability_day')
                            ===
                            'sunday'
                        )
                    >
                        {{ app()->getLocale() === 'fr'
                            ? 'Dimanche'
                            : 'Sunday'
                        }}
                    </option>

                </select>

            </div>



            {{-- TIME --}}

            <div class="col-md-4">

                <label class="form-label">

                    {{ app()->getLocale() === 'fr'
                        ? 'Heure'
                        : 'Time'
                    }}

                </label>


                <select
                    name="availability_time"
                    class="form-select"
                >

                    <option value="">

                        {{ app()->getLocale() === 'fr'
                            ? 'Toute heure'
                            : 'Any Time'
                        }}

                    </option>


                    @for($hour = 0; $hour < 24; $hour++)

                        @foreach(
                            [0, 15, 30, 45]
                            as $minute
                        )

                            @php

                                $timeValue =
                                    sprintf(
                                        '%02d:%02d',
                                        $hour,
                                        $minute
                                    );


                                $timeLabel =
                                    \Carbon\Carbon::createFromTime(
                                        $hour,
                                        $minute
                                    )->format(
                                        app()->getLocale() === 'fr'
                                            ? 'H:i'
                                            : 'g:i A'
                                    );

                            @endphp


                            <option
                                value="{{ $timeValue }}"

                                @selected(
                                    request(
                                        'availability_time'
                                    )
                                    ===
                                    $timeValue
                                )
                            >

                                {{ $timeLabel }}

                            </option>

                        @endforeach

                    @endfor

                </select>

            </div>


        </div>



        {{-- =====================================================
           ACTIONS
        ====================================================== --}}

        <div class="teacher-search-actions">


            @if($hasSearch)

                <a
                    href="{{ route('student.teachers') }}"
                    class="teacher-reset-btn"
                >
                    {{ __('student.clear_search') }}
                </a>

            @endif


            <button
                type="submit"
                class="teacher-search-btn"
            >
                {{ __('student.search_teachers') }}
            </button>


        </div>


    </form>


</div>



{{-- =========================================================
   RESULTS
========================================================= --}}

@if($hasSearch)


    <div class="teacher-results-header">


        <div class="teacher-results-title">

            <h3>
                {{ __('student.search_results') }}
            </h3>


            <p>
                {{ __('student.teachers_matching_search') }}
            </p>

        </div>



        <div class="teacher-results-count">

            {{ number_format(
                $teachers->total()
            ) }}


            {{ $teachers->total() === 1
                ? __('student.teacher_singular')
                : __('student.teachers_plural')
            }}

        </div>


    </div>



    @if($teachers->count())


        <div class="teacher-results-grid">


            @foreach($teachers as $teacher)


                @php

                    /*
                    |--------------------------------------------------------------------------
                    | PHOTO
                    |--------------------------------------------------------------------------
                    */

                    $profilePhoto =
                        null;


                    if (
                        $teacher->profile_photo
                    ) {

                        $profilePhoto =
                            asset(
                                'storage/'
                                .
                                ltrim(
                                    $teacher->profile_photo,
                                    '/'
                                )
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | REVIEWS
                    |--------------------------------------------------------------------------
                    */

                    $reviewCount =
                        (int) (
                            $teacher
                                ->approved_reviews_count
                            ??
                            0
                        );


                    $averageRating =
                        (float) (
                            $teacher
                                ->approved_reviews_avg_rating
                            ??
                            0
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | RATES
                    |--------------------------------------------------------------------------
                    */

                    $danceRates =
                        $teacher
                            ->danceStyles
                            ->map(
                                function ($style) {

                                    return
                                        $style
                                            ->pivot
                                            ->hourly_rate
                                        ??
                                        null;
                                }
                            )
                            ->filter(
                                function ($rate) {

                                    return
                                        $rate !== null
                                        &&
                                        (float) $rate > 0;
                                }
                            );


                    $minimumRate =
                        $danceRates->count()
                            ? $danceRates->min()
                            : null;

                @endphp



                {{-- =====================================================
                   EXISTING RESULT CARD
                   NOTHING CHANGED
                ====================================================== --}}

                <div class="teacher-result-card">


                    {{-- =================================================
                       LEFT : TEACHER + RATING
                    ================================================== --}}

                    <div class="teacher-identity">


                        <div class="teacher-avatar">


                            @if($profilePhoto)

                                <img
                                    src="{{ $profilePhoto }}"
                                    alt="{{ $teacher->user?->name ?? __('student.teacher') }}"
                                >

                            @else

                                {{ strtoupper(
                                    substr(
                                        $teacher->user?->name
                                        ?? 'T',
                                        0,
                                        1
                                    )
                                ) }}

                            @endif


                        </div>



                        <div class="teacher-main-info">


                            <div class="teacher-name-row">


                                <h4 class="teacher-name">

                                    {{ $teacher
                                        ->user
                                        ?->name
                                        ?? __('student.teacher')
                                    }}

                                </h4>


                                @if(
                                    $teacher->verified
                                    ??
                                    false
                                )

                                    <span class="teacher-verified">

                                        ✓
                                        {{ __('student.verified') }}

                                    </span>

                                @endif


                            </div>



                            {{-- RATING --}}

                            <div class="teacher-rating-row">


                                @if($reviewCount > 0)


                                    <div class="teacher-stars">


                                        @for(
                                            $star = 1;
                                            $star <= 5;
                                            $star++
                                        )

                                            <span
                                                class="
                                                    teacher-star

                                                    {{ $star <= round($averageRating)
                                                        ? 'filled'
                                                        : 'empty'
                                                    }}
                                                "
                                            >
                                                ★
                                            </span>

                                        @endfor


                                    </div>



                                    <span class="teacher-rating-number">

                                        {{ number_format(
                                            $averageRating,
                                            1
                                        ) }}

                                    </span>



                                    <span class="teacher-review-count">

                                        (

                                        {{ number_format(
                                            $reviewCount
                                        ) }}


                                        {{ $reviewCount === 1
                                            ? __('student.review')
                                            : __('student.reviews_plural')
                                        }}

                                        )

                                    </span>


                                @else


                                    <span class="teacher-new-label">

                                        {{ __('student.new_teacher') }}

                                    </span>


                                @endif


                            </div>


                        </div>


                    </div>



                    {{-- =================================================
                       MIDDLE : DANCE RATES
                    ================================================== --}}

                    <div class="teacher-dances">


                        <div class="teacher-section-label">

                            {{ __('student.dance_styles_rates') }}

                        </div>


                        @if(
                            $teacher
                                ->danceStyles
                                ->count()
                        )


                            <div class="teacher-dance-list">


                                @foreach(
                                    $teacher
                                        ->danceStyles
                                        ->take(3)
                                    as $style
                                )


                                    <div class="teacher-dance-rate">


                                        <span
                                            class="teacher-dance-name"
                                            title="{{ $style->name }}"
                                        >

                                            {{ $style->name }}

                                        </span>



                                        @if(
                                            isset(
                                                $style
                                                    ->pivot
                                                    ->hourly_rate
                                            )
                                            &&
                                            (float)
                                                $style
                                                    ->pivot
                                                    ->hourly_rate
                                            > 0
                                        )


                                            <span class="teacher-dance-price">

                                                ${{ number_format(
                                                    (float)
                                                        $style
                                                            ->pivot
                                                            ->hourly_rate,
                                                    2
                                                ) }}/h

                                            </span>


                                        @else


                                            <span class="teacher-no-rate">

                                                {{ __('student.ask') }}

                                            </span>


                                        @endif


                                    </div>


                                @endforeach


                            </div>



                            @if(
                                $teacher
                                    ->danceStyles
                                    ->count()
                                >
                                3
                            )


                                <div class="teacher-more-styles">

                                    +

                                    {{ $teacher
                                        ->danceStyles
                                        ->count() - 3
                                    }}

                                    {{ __('student.more_styles') }}

                                </div>


                            @endif


                        @else


                            <div class="teacher-no-rate">

                                {{ __('student.no_dance_styles') }}

                            </div>


                        @endif


                    </div>



                    {{-- =================================================
                       RIGHT : LOCATION + EXPERIENCE + PRICE + BUTTON
                    ================================================== --}}

                    <div class="teacher-side-info">


                        <div class="teacher-meta">


                            <div class="teacher-location">


                                <span class="teacher-meta-icon">
                                    📍
                                </span>


                                <span>

                                    {{ $teacher->city
                                        ?? __('student.location_not_set')
                                    }}


                                    @if($teacher->province)

                                        ,
                                        {{ $teacher->province }}

                                    @endif

                                </span>


                            </div>



                            <div class="teacher-experience">


                                <span class="teacher-meta-icon">
                                    ◇
                                </span>


                                <span>

                                    {{ number_format(
                                        $teacher
                                            ->experience_years
                                        ??
                                        0
                                    ) }}


                                    {{ ($teacher->experience_years ?? 0) == 1
                                        ? __('student.year')
                                        : __('student.years')
                                    }}


                                    {{ __('student.experience') }}

                                </span>


                            </div>


                        </div>



                        <div class="teacher-price-from">


                            @if($minimumRate !== null)


                                {{ __('student.from') }}


                                <strong>

                                    ${{ number_format(
                                        (float) $minimumRate,
                                        2
                                    ) }}

                                    /
                                    {{ __('student.hour') }}

                                </strong>


                            @else


                                {{ __('student.rates') }}


                                <strong>

                                    {{ __('student.view_profile') }}

                                </strong>


                            @endif


                        </div>



                        <a
                            href="{{ route(
                                'student.teachers.show',
                                $teacher
                            ) }}"
                            class="teacher-view-profile"
                        >

                            {{ __('student.view_profile') }}

                            <span>
                                →
                            </span>

                        </a>


                    </div>


                </div>


            @endforeach


        </div>



        {{-- =================================================
           PAGINATION
        ================================================== --}}

        @if($teachers->hasPages())


            <div class="teacher-pagination">

                {{ $teachers->links() }}

            </div>


        @endif


    @else


        <div class="teacher-empty-result">


            <h4>

                {{ __('student.no_teachers_found') }}

            </h4>


            <p>

                {{ __('student.change_search_filters') }}

            </p>


        </div>


    @endif


@endif


</div>


@endsection