@extends('public.layout')

@section(
    'title',
    app()->getLocale() === 'fr'
        ? 'Trouver un professeur | DancePair'
        : 'Find a Teacher | DancePair'
)


@push('styles')
<style>

    /* =========================================================
       HERO
    ========================================================= */

    .find-hero {
        position: relative;
        min-height: 430px;
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

        background-size: auto 100%;
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
        max-width: 640px;
    }


    .find-kicker {
        display: inline-flex;
        align-items: center;

        padding: 7px 13px;

        margin-bottom: 16px;

        border: 1px solid rgba(247,37,133,.30);
        border-radius: 999px;

        background: rgba(247,37,133,.08);

        color: #FF85BC;

        font-size: 10px;
        font-weight: 900;
        letter-spacing: .15em;
    }


    .find-hero h1 {
        margin: 0;

        color: #FFFFFF;

        font-size: clamp(44px, 5vw, 68px);
        line-height: .96;

        font-weight: 950;

        letter-spacing: -3px;
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

        margin: 18px 0 0;

        color: #BAB5C5;

        font-size: 15px;
        line-height: 1.65;
    }


    /* =========================================================
       SEARCH BOX
    ========================================================= */

    .find-search-wrap {
        position: relative;

        z-index: 10;

        width: min(1450px, calc(100% - 80px));

        margin: -38px auto 0;
    }


    .find-search-box {
        display: grid;

        grid-template-columns:
            1.1fr
            1.1fr
            1fr
            auto;

        align-items: end;

        gap: 10px;

        padding: 15px;

        border: 1px solid rgba(255,255,255,.10);

        border-radius: 18px;

        background:
            rgba(18,15,38,.96);

        box-shadow:
            0 25px 70px
            rgba(0,0,0,.35);

        backdrop-filter: blur(18px);
    }


    .find-field label {
        display: block;

        margin-bottom: 6px;

        color: #8C879A;

        font-size: 10px;
        font-weight: 850;

        letter-spacing: .08em;

        text-transform: uppercase;
    }


    .find-field input,
    .find-field select {
        width: 100%;
        height: 46px;

        padding: 0 14px;

        border:
            1px solid rgba(255,255,255,.09);

        border-radius: 10px;

        outline: none;

        color: #FFFFFF;

        background: #0A0919;

        font-size: 13px;
    }


    .find-field select option {
        background: #0A0919;
    }


    .find-search-button {
        height: 46px;

        padding: 0 24px;

        border: 0;

        border-radius: 10px;

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
        padding: 58px 0 52px;
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

        margin-bottom: 24px;
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

        font-size: 32px;
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

        height: 210px;

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
        padding: 16px;
    }


    .teacher-name-row {
        display: flex;

        justify-content: space-between;

        gap: 10px;
    }


    .teacher-name {
        color: #FFFFFF;

        font-size: 15px;
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

        font-size: 10px;
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

        margin-top: 14px;

        padding-top: 12px;

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
        margin-top: 42px;

        padding: 32px 36px;

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

        font-size: 26px;
        font-weight: 950;
    }


    .find-bottom-cta p {
        margin: 0;

        color: #8E8998;

        font-size: 13px;
    }


    .find-bottom-cta a {
        min-height: 44px;

        padding: 0 20px;

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
            min-height: 470px;

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
            padding: 26px;
        }

    }

</style>
@endpush


@section('content')


<section class="find-hero">

    <div class="find-hero-inner">

        <div class="find-hero-copy">

            <div class="find-kicker">
                {{ app()->getLocale() === 'fr'
                    ? 'TROUVEZ • APPRENEZ • DANSEZ'
                    : 'FIND • LEARN • DANCE'
                }}
            </div>

            <h1>

                {{ app()->getLocale() === 'fr'
                    ? 'Trouvez le professeur de danse'
                    : 'Find the Dance Teacher'
                }}

                <span>
                    {{ app()->getLocale() === 'fr'
                        ? 'qui vous correspond.'
                        : 'That’s Right for You.'
                    }}
                </span>

            </h1>

            <p>
                {{ app()->getLocale() === 'fr'
                    ? 'Découvrez des professeurs de danse selon votre style, votre niveau, votre localisation et vos objectifs. Comparez les profils, les évaluations et les tarifs afin de trouver le professeur qui vous convient.'
                    : 'Discover dance teachers based on your style, experience, location, and goals. Compare profiles, ratings, and rates to find the teacher who best fits your needs.'
                }}
            </p>

        </div>

    </div>

</section>



<div class="find-search-wrap">

    <form
        class="find-search-box"
        method="GET"
        action="{{ route('public.find-teacher') }}"
    >

        <div class="find-field">

            <label>
                {{ app()->getLocale() === 'fr'
                    ? 'Localisation'
                    : 'Location'
                }}
            </label>

            <input
                type="text"
                name="location"
                value="{{ request('location') }}"
                placeholder="{{ app()->getLocale() === 'fr'
                    ? 'Montréal, Brossard...'
                    : 'Montreal, Brossard...'
                }}"
            >

        </div>


        <div class="find-field">

            <label>
                {{ app()->getLocale() === 'fr'
                    ? 'Style de danse'
                    : 'Dance Style'
                }}
            </label>

            <select name="dance_style_id">

                <option value="">
                    {{ app()->getLocale() === 'fr'
                        ? 'Tous les styles de danse'
                        : 'All Dance Styles'
                    }}
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

            <label>
                {{ app()->getLocale() === 'fr'
                    ? 'Type de cours'
                    : 'Lesson Type'
                }}
            </label>

            <select name="lesson_type">

                <option value="">
                    {{ app()->getLocale() === 'fr'
                        ? 'Tous les types de cours'
                        : 'All Lesson Types'
                    }}
                </option>

                <option
                    value="face_to_face"
                    @selected(request('lesson_type') === 'face_to_face')
                >
                    {{ app()->getLocale() === 'fr'
                        ? 'En personne'
                        : 'In Person'
                    }}
                </option>

                <option
                    value="online"
                    @selected(request('lesson_type') === 'online')
                >
                    {{ app()->getLocale() === 'fr'
                        ? 'En ligne'
                        : 'Online'
                    }}
                </option>

                <option
                    value="public_place"
                    @selected(request('lesson_type') === 'public_place')
                >
                    {{ app()->getLocale() === 'fr'
                        ? 'Dans un lieu public'
                        : 'Public Place'
                    }}
                </option>

            </select>

        </div>


        <button
            type="submit"
            class="find-search-button"
        >
            {{ app()->getLocale() === 'fr'
                ? 'Trouver un professeur'
                : 'Find a Teacher'
            }}
        </button>

    </form>

</div>



<section class="find-content">

    <div class="find-container">


        <div class="find-heading">

            <div>

                <small>
                    {{ app()->getLocale() === 'fr'
                        ? 'PROFESSEURS DANCEPAIR'
                        : 'DANCEPAIR TEACHERS'
                    }}
                </small>

                <h2>
                    {{ app()->getLocale() === 'fr'
                        ? 'Trouvez le professeur idéal'
                        : 'Find Your Perfect Match'
                    }}
                </h2>

            </div>

            <p>
                {{ app()->getLocale() === 'fr'
                    ? 'Explorez les profils, comparez les styles et les tarifs, puis choisissez le professeur qui correspond le mieux à vos objectifs.'
                    : 'Explore teacher profiles, compare styles and rates, and choose the teacher who best matches your goals.'
                }}
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

                                        {{ app()->getLocale() === 'fr'
                                            ? (
                                                (int) $teacher->experience_years === 1
                                                    ? 'an d’expérience'
                                                    : 'ans d’expérience'
                                            )
                                            : (
                                                (int) $teacher->experience_years === 1
                                                    ? 'year of experience'
                                                    : 'years of experience'
                                            )
                                        }}

                                    </span>

                                @endif

                            </div>


                            <div class="teacher-body">

                                <div class="teacher-name-row">

                                    <div class="teacher-name">

                                        {{ $teacher->user->name }}

                                    </div>


                                    @php
                                        $teacherRating =
                                            $teacher->average_rating
                                            ?? $teacher->reviews_avg_rating
                                            ?? null;
                                    @endphp

                                    @if($teacherRating)

                                        <div class="teacher-rating">

                                            ★
                                            {{ number_format($teacherRating, 1) }}

                                        </div>

                                    @endif

                                </div>


                                <div class="teacher-location">

                                    @if($teacher->city)

                                        {{ $teacher->city }}

                                    @else

                                        {{ app()->getLocale() === 'fr'
                                            ? 'Localisation non indiquée'
                                            : 'Location not provided'
                                        }}

                                    @endif


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

                                            {{ app()->getLocale() === 'fr'
                                                ? 'À partir de'
                                                : 'From'
                                            }}

                                            ${{ number_format($minimumRate, 0) }}

                                            <span>
                                                {{ app()->getLocale() === 'fr'
                                                    ? '/ heure'
                                                    : '/ hour'
                                                }}
                                            </span>

                                        @else

                                            <span>
                                                {{ app()->getLocale() === 'fr'
                                                    ? 'Voir les tarifs'
                                                    : 'View Rates'
                                                }}
                                            </span>

                                        @endif

                                    </div>


                                    <a
                                        href="{{ route('student.teachers.show', $teacher) }}"
                                        class="teacher-view"
                                    >
                                        {{ app()->getLocale() === 'fr'
                                            ? 'Voir le profil'
                                            : 'View Profile'
                                        }}
                                        →
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

                    {{ app()->getLocale() === 'fr'
                        ? 'Aucun professeur ne correspond à vos critères de recherche.'
                        : 'No teachers match your current search criteria.'
                    }}

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

                {{ app()->getLocale() === 'fr'
                    ? 'Les professeurs disponibles apparaîtront ici.'
                    : 'Available teachers will appear here.'
                }}

            </div>

        @endisset



        <div class="find-bottom-cta">

            <div>

                <h3>
                    {{ app()->getLocale() === 'fr'
                        ? 'Vous êtes professeur de danse ?'
                        : 'Are You a Dance Teacher?'
                    }}
                </h3>

                <p>
                    {{ app()->getLocale() === 'fr'
                        ? 'Créez votre profil DancePair, présentez votre expérience et faites-vous découvrir par de nouveaux élèves.'
                        : 'Create your DancePair profile, showcase your experience, and connect with new students.'
                    }}
                </p>

            </div>


            <a href="{{ route('register') }}">
                {{ app()->getLocale() === 'fr'
                    ? 'Devenir professeur'
                    : 'Become a Teacher'
                }}
            </a>

        </div>

    </div>

</section>

@endsection