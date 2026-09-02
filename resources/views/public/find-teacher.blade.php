@extends('public.layout')

@section('title', 'Find a Teacher | DancePair')


@push('styles')
<style>

    /* =========================================================
       HERO
    ========================================================= */

    .find-hero {
        position: relative;
        min-height: 640px;
        display: flex;
        align-items: center;
        overflow: hidden;

        background:
            linear-gradient(
                90deg,
                rgba(6,5,20,.98) 0%,
                rgba(6,5,20,.92) 40%,
                rgba(6,5,20,.48) 72%,
                rgba(6,5,20,.15) 100%
            ),
            url('{{ asset('images/home/hero-dance.jpg') }}');

        background-size: auto 95%;
        background-position: right bottom;
        background-repeat: no-repeat;
    }


    .find-hero::before {
        content: "";
        position: absolute;
        inset: 0;

        background:
            radial-gradient(
                circle at 25% 35%,
                rgba(247,37,133,.15),
                transparent 27%
            ),
            radial-gradient(
                circle at 70% 55%,
                rgba(121,55,255,.15),
                transparent 35%
            );

        pointer-events: none;
    }


    .find-hero-inner {
        position: relative;
        z-index: 2;

        width: min(1450px, calc(100% - 80px));
        margin: 0 auto;
    }


    .find-hero-copy {
        max-width: 710px;
    }


    .find-kicker {
        display: inline-flex;
        align-items: center;

        padding: 9px 15px;

        margin-bottom: 22px;

        border: 1px solid rgba(247,37,133,.30);
        border-radius: 999px;

        background: rgba(247,37,133,.08);

        color: #FF85BC;

        font-size: 11px;
        font-weight: 900;
        letter-spacing: .15em;
    }


    .find-hero h1 {
        margin: 0;

        color: #FFFFFF;

        font-size: clamp(50px, 6vw, 88px);
        line-height: .96;

        font-weight: 950;

        letter-spacing: -4px;
    }


    .find-hero h1 span {
        display: block;

        color: transparent;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #B52CF1,
                #7937FF
            );

        -webkit-background-clip: text;
        background-clip: text;
    }


    .find-hero-copy > p {
        max-width: 600px;

        margin: 25px 0 0;

        color: #BAB5C5;

        font-size: 17px;
        line-height: 1.75;
    }


    /* =========================================================
       SEARCH BOX
    ========================================================= */

    .find-search-wrap {
        position: relative;

        z-index: 10;

        width: min(1450px, calc(100% - 80px));

        margin: -54px auto 0;
    }


    .find-search-box {
        display: grid;

        grid-template-columns:
            1.1fr
            1.1fr
            1fr
            auto;

        align-items: end;

        gap: 12px;

        padding: 20px;

        border: 1px solid rgba(255,255,255,.10);

        border-radius: 22px;

        background:
            rgba(18,15,38,.96);

        box-shadow:
            0 25px 70px
            rgba(0,0,0,.35);

        backdrop-filter: blur(18px);
    }


    .find-field label {
        display: block;

        margin-bottom: 8px;

        color: #8C879A;

        font-size: 10px;
        font-weight: 850;

        letter-spacing: .08em;

        text-transform: uppercase;
    }


    .find-field input,
    .find-field select {
        width: 100%;
        height: 50px;

        padding: 0 15px;

        border:
            1px solid rgba(255,255,255,.09);

        border-radius: 12px;

        outline: none;

        color: #FFFFFF;

        background: #0A0919;

        font-size: 13px;
    }


    .find-field select option {
        background: #0A0919;
    }


    .find-search-button {
        height: 50px;

        padding: 0 28px;

        border: 0;

        border-radius: 12px;

        color: #FFFFFF;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #7937FF
            );

        font-weight: 900;

        cursor: pointer;

        white-space: nowrap;

        box-shadow:
            0 12px 28px
            rgba(247,37,133,.20);
    }


    /* =========================================================
       CONTENT
    ========================================================= */

    .find-content {
        padding: 105px 0 85px;
    }


    .find-container {
        width: min(1450px, calc(100% - 80px));
        margin: auto;
    }


    .find-heading {
        display: flex;

        align-items: end;
        justify-content: space-between;

        gap: 30px;

        margin-bottom: 32px;
    }


    .find-heading small {
        display: block;

        margin-bottom: 7px;

        color: #F72585;

        font-size: 10px;
        font-weight: 900;

        letter-spacing: .14em;

        text-transform: uppercase;
    }


    .find-heading h2 {
        margin: 0;

        color: white;

        font-size: 37px;
        font-weight: 950;

        letter-spacing: -1.5px;
    }


    .find-heading p {
        max-width: 440px;

        margin: 0;

        color: #898496;

        font-size: 13px;
        line-height: 1.7;
    }


    /* =========================================================
       TEACHER CARDS
    ========================================================= */

    .teacher-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 18px;
    }


    .teacher-card {
        overflow: hidden;

        border:
            1px solid rgba(255,255,255,.08);

        border-radius: 20px;

        background:
            linear-gradient(
                145deg,
                #100E25,
                #0A0918
            );

        transition:
            transform .25s ease,
            border-color .25s ease;
    }


    .teacher-card:hover {
        transform: translateY(-6px);

        border-color:
            rgba(247,37,133,.30);
    }


    .teacher-photo {
        position: relative;

        height: 260px;

        overflow: hidden;

        background:
            linear-gradient(
                135deg,
                #18152C,
                #0A0918
            );
    }


    .teacher-photo img {
        width: 100%;
        height: 100%;

        object-fit: cover;
    }


    .teacher-badge {
        position: absolute;

        left: 14px;
        top: 14px;

        padding: 7px 11px;

        border-radius: 999px;

        color: #FFFFFF;

        background:
            rgba(8,7,23,.82);

        backdrop-filter: blur(10px);

        font-size: 10px;
        font-weight: 800;
    }


    .teacher-body {
        padding: 19px;
    }


    .teacher-name-row {
        display: flex;

        justify-content: space-between;

        gap: 10px;
    }


    .teacher-name {
        color: #FFFFFF;

        font-size: 17px;
        font-weight: 900;
    }


    .teacher-rating {
        color: #FFC857;

        font-size: 12px;
        font-weight: 800;
    }


    .teacher-location {
        margin-top: 5px;

        color: #777184;

        font-size: 11px;
    }


    .teacher-styles {
        display: flex;

        flex-wrap: wrap;

        gap: 6px;

        margin-top: 14px;
    }


    .teacher-style {
        padding: 6px 9px;

        border-radius: 999px;

        color: #C5BDD5;

        background:
            rgba(255,255,255,.06);

        font-size: 9px;
        font-weight: 750;
    }


    .teacher-footer {
        display: flex;

        align-items: center;
        justify-content: space-between;

        margin-top: 18px;

        padding-top: 15px;

        border-top:
            1px solid rgba(255,255,255,.06);
    }


    .teacher-price {
        color: #FFFFFF;

        font-size: 14px;
        font-weight: 900;
    }


    .teacher-price span {
        color: #726D80;

        font-size: 10px;
        font-weight: 600;
    }


    .teacher-view {
        color: #F72585;

        text-decoration: none;

        font-size: 10px;
        font-weight: 850;
    }


    /* =========================================================
       BOTTOM CTA
    ========================================================= */

    .find-bottom-cta {
        margin-top: 80px;

        padding: 55px;

        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 30px;

        border:
            1px solid rgba(255,255,255,.08);

        border-radius: 24px;

        background:
            radial-gradient(
                circle at 80% 50%,
                rgba(121,55,255,.25),
                transparent 35%
            ),
            linear-gradient(
                120deg,
                #100D25,
                #090817
            );
    }


    .find-bottom-cta h3 {
        margin: 0 0 10px;

        color: #FFFFFF;

        font-size: 30px;
        font-weight: 950;
    }


    .find-bottom-cta p {
        margin: 0;

        color: #8E8998;

        font-size: 13px;
    }


    .find-bottom-cta a {
        min-height: 48px;

        padding: 0 23px;

        display: inline-flex;

        align-items: center;

        border-radius: 11px;

        color: white;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #7937FF
            );

        text-decoration: none;

        font-size: 12px;
        font-weight: 850;

        white-space: nowrap;
    }


    @media(max-width: 1050px) {

        .teacher-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }

        .find-search-box {
            grid-template-columns:
                1fr 1fr;
        }

    }


    @media(max-width: 650px) {

        .find-hero-inner,
        .find-search-wrap,
        .find-container {
            width: calc(100% - 28px);
        }

        .find-hero {
            min-height: 580px;

            background-size: auto 70%;
            background-position: 80% bottom;
        }

        .find-hero h1 {
            letter-spacing: -2px;
        }

        .find-search-box {
            grid-template-columns: 1fr;
        }

        .teacher-grid {
            grid-template-columns: 1fr;
        }

        .find-heading,
        .find-bottom-cta {
            align-items: flex-start;
            flex-direction: column;
        }

        .find-bottom-cta {
            padding: 30px;
        }

    }

</style>
@endpush


@section('content')


<section class="find-hero">

    <div class="find-hero-inner">

        <div class="find-hero-copy">

            <div class="find-kicker">
                FIND • LEARN • DANCE
            </div>

            <h1>
                Find the Teacher
                <span>Made for You.</span>
            </h1>

            <p>
                Discover dance teachers who match your style,
                experience, location and goals.
                Compare real profiles, ratings and rates before
                choosing who you want to learn with.
            </p>

        </div>

    </div>

</section>



<div class="find-search-wrap">

    <form class="find-search-box" method="GET">

        <div class="find-field">

            <label>Location</label>

            <input
                type="text"
                name="city"
                value="{{ request('city') }}"
                placeholder="Montreal, Brossard..."
            >

        </div>


        <div class="find-field">

            <label>Dance Style</label>

            <select name="dance_style_id">

                <option value="">
                    All Dance Styles
                </option>

                @isset($danceStyles)

                    @foreach($danceStyles as $style)

                        <option
                            value="{{ $style->id }}"
                            @selected(
                                request('dance_style_id')
                                == $style->id
                            )
                        >
                            {{ $style->name }}
                        </option>

                    @endforeach

                @endisset

            </select>

        </div>


        <div class="find-field">

            <label>Lesson Type</label>

            <select name="lesson_type">

                <option value="">
                    All Lessons
                </option>

                <option value="in_person">
                    In-Person
                </option>

                <option value="online">
                    Online
                </option>

            </select>

        </div>


        <button
            type="submit"
            class="find-search-button"
        >
            Find My Teacher
        </button>

    </form>

</div>



<section class="find-content">

    <div class="find-container">


        <div class="find-heading">

            <div>

                <small>
                    DancePair Teachers
                </small>

                <h2>
                    Find Your Perfect Match
                </h2>

            </div>

            <p>
                Explore teachers, compare styles and rates,
                and choose the person who feels right for
                your dance journey.
            </p>

        </div>



        @isset($teachers)

            @if($teachers->count())

                <div class="teacher-grid">

                    @foreach($teachers as $teacher)

                        <article class="teacher-card">

                            <div class="teacher-photo">

                                @if($teacher->profile_photo)

                                    <img
                                        src="{{ asset('storage/' . $teacher->profile_photo) }}"
                                        alt="{{ $teacher->user->name }}"
                                    >

                                @endif


                                @if($teacher->experience_years)

                                    <span class="teacher-badge">

                                        {{ $teacher->experience_years }}
                                        years experience

                                    </span>

                                @endif

                            </div>


                            <div class="teacher-body">

                                <div class="teacher-name-row">

                                    <div class="teacher-name">

                                        {{ $teacher->user->name }}

                                    </div>


                                    @if(isset($teacher->reviews_avg_rating))

                                        <div class="teacher-rating">

                                            ★
                                            {{ number_format($teacher->reviews_avg_rating, 1) }}

                                        </div>

                                    @endif

                                </div>


                                <div class="teacher-location">

                                    {{ $teacher->city }}

                                    @if($teacher->province)
                                        • {{ $teacher->province }}
                                    @endif

                                </div>


                                <div class="teacher-styles">

                                    @foreach(
                                        $teacher->danceStyles->take(4)
                                        as $style
                                    )

                                        <span class="teacher-style">

                                            {{ $style->name }}

                                        </span>

                                    @endforeach

                                </div>


                                <div class="teacher-footer">

                                    <div class="teacher-price">

                                        @php

                                            $rates =
                                                $teacher
                                                    ->danceStyles
                                                    ->pluck('pivot.hourly_rate')
                                                    ->filter();

                                            $minimumRate =
                                                $rates->min()
                                                ?? $teacher->hourly_rate;

                                        @endphp


                                        @if($minimumRate)

                                            From
                                            ${{ number_format($minimumRate, 0) }}

                                            <span>/hour</span>

                                        @else

                                            <span>
                                                View rates
                                            </span>

                                        @endif

                                    </div>


                                    <a
                                        href="{{ route('student.teachers.show', $teacher) }}"
                                        class="teacher-view"
                                    >
                                        View Profile →
                                    </a>

                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            @else

                <div
                    style="
                        padding:45px;
                        text-align:center;
                        color:#817C8E;
                        border:1px solid rgba(255,255,255,.07);
                        border-radius:20px;
                        background:#0E0C20;
                    "
                >

                    No teachers matched your search.

                </div>

            @endif

        @else

            <div
                style="
                    padding:45px;
                    text-align:center;
                    color:#817C8E;
                    border:1px solid rgba(255,255,255,.07);
                    border-radius:20px;
                    background:#0E0C20;
                "
            >

                Teacher results will appear here.

            </div>

        @endisset



        <div class="find-bottom-cta">

            <div>

                <h3>
                    Are You a Dance Teacher?
                </h3>

                <p>
                    Create your DancePair profile and let
                    new students discover you.
                </p>

            </div>


            <a href="{{ route('register') }}">
                Become a Teacher
            </a>

        </div>

    </div>

</section>

@endsection