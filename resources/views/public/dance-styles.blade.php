@extends('public.layout')

@section('title', 'Dance Styles | DancePair')


@push('styles')
<style>

    /* =========================================================
       HERO
    ========================================================= */

    .styles-hero {
        position: relative;
        min-height: 620px;
        display: flex;
        align-items: center;
        overflow: hidden;

        background:
            linear-gradient(
                90deg,
                rgba(6,5,20,.98) 0%,
                rgba(6,5,20,.90) 44%,
                rgba(6,5,20,.35) 100%
            ),
            url('{{ asset('images/home/hero-dance.jpg') }}');

        background-size: auto 95%;
        background-position: right bottom;
        background-repeat: no-repeat;
    }


    .styles-hero::before {
        content: "";
        position: absolute;
        inset: 0;

        background:
            radial-gradient(
                circle at 25% 35%,
                rgba(247,37,133,.16),
                transparent 30%
            ),
            radial-gradient(
                circle at 75% 50%,
                rgba(121,55,255,.18),
                transparent 35%
            );
    }


    .styles-container {
        position: relative;
        z-index: 2;

        width: min(1450px, calc(100% - 80px));
        margin: 0 auto;
    }


    .styles-copy {
        max-width: 720px;
    }


    .styles-kicker {
        display: inline-flex;
        align-items: center;

        padding: 9px 15px;
        margin-bottom: 22px;

        border: 1px solid rgba(247,37,133,.30);
        border-radius: 999px;

        color: #FF87BD;
        background: rgba(247,37,133,.08);

        font-size: 10px;
        font-weight: 900;
        letter-spacing: .15em;
    }


    .styles-copy h1 {
        margin: 0;

        color: #FFFFFF;

        font-size: clamp(50px, 6vw, 86px);
        line-height: .96;

        font-weight: 950;
        letter-spacing: -4px;
    }


    .styles-copy h1 span {
        display: block;

        color: transparent;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #B72EF0,
                #7937FF
            );

        -webkit-background-clip: text;
        background-clip: text;
    }


    .styles-copy p {
        max-width: 600px;

        margin: 25px 0 0;

        color: #B6B0C0;

        font-size: 17px;
        line-height: 1.75;
    }


    /* =========================================================
       INTRO
    ========================================================= */

    .styles-section {
        padding: 90px 0;
    }


    .styles-section-head {
        display: flex;
        align-items: end;
        justify-content: space-between;

        gap: 30px;

        margin-bottom: 38px;
    }


    .styles-section-head small {
        display: block;

        color: #F72585;

        font-size: 10px;
        font-weight: 900;

        letter-spacing: .15em;
        text-transform: uppercase;
    }


    .styles-section-head h2 {
        margin: 8px 0 0;

        color: #FFFFFF;

        font-size: 40px;
        font-weight: 950;

        letter-spacing: -2px;
    }


    .styles-section-head p {
        max-width: 480px;

        margin: 0;

        color: #8A8495;

        font-size: 13px;
        line-height: 1.7;
    }


    /* =========================================================
       STYLE CARDS
    ========================================================= */

    .styles-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 18px;
    }


    .style-card {
        position: relative;
        overflow: hidden;

        min-height: 270px;

        display: flex;
        align-items: flex-end;

        padding: 26px;

        border: 1px solid rgba(255,255,255,.08);
        border-radius: 22px;

        background:
            linear-gradient(
                145deg,
                #120F29,
                #090817
            );

        transition:
            transform .25s ease,
            border-color .25s ease;
    }


    .style-card::before {
        content: "";

        position: absolute;
        width: 170px;
        height: 170px;

        right: -40px;
        top: -40px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(121,55,255,.30),
                transparent 70%
            );
    }


    .style-card:nth-child(2n)::before {
        background:
            radial-gradient(
                circle,
                rgba(247,37,133,.28),
                transparent 70%
            );
    }


    .style-card:hover {
        transform: translateY(-6px);

        border-color:
            rgba(247,37,133,.28);
    }


    .style-card-content {
        position: relative;
        z-index: 2;
    }


    .style-card-number {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-width: 38px;
        height: 38px;

        margin-bottom: 38px;

        padding: 0 10px;

        border-radius: 10px;

        color: #FFFFFF;

        background:
            linear-gradient(
                135deg,
                #F72585,
                #7937FF
            );

        font-size: 10px;
        font-weight: 900;
    }


    .style-card h3 {
        margin: 0 0 8px;

        color: #FFFFFF;

        font-size: 23px;
        font-weight: 900;
    }


    .style-card p {
        max-width: 330px;

        margin: 0 0 18px;

        color: #817B8E;

        font-size: 12px;
        line-height: 1.7;
    }


    .style-card a {
        color: #F72585;

        text-decoration: none;

        font-size: 11px;
        font-weight: 850;
    }


    /* =========================================================
       DYNAMIC STYLE LIST
    ========================================================= */

    .styles-all {
        padding: 85px 0;

        background:
            radial-gradient(
                circle at 80% 40%,
                rgba(121,55,255,.12),
                transparent 32%
            ),
            #090817;
    }


    .styles-tags {
        display: flex;

        flex-wrap: wrap;

        gap: 11px;

        margin-top: 30px;
    }


    .styles-tag {
        display: inline-flex;

        align-items: center;

        min-height: 46px;

        padding: 0 17px;

        border: 1px solid rgba(255,255,255,.08);
        border-radius: 999px;

        color: #D7D2DE;

        background: rgba(255,255,255,.035);

        text-decoration: none;

        font-size: 12px;
        font-weight: 750;

        transition:
            background .2s ease,
            border-color .2s ease,
            transform .2s ease;
    }


    .styles-tag:hover {
        transform: translateY(-2px);

        border-color: rgba(247,37,133,.30);

        background:
            rgba(247,37,133,.08);
    }


    /* =========================================================
       BOTTOM CTA
    ========================================================= */

    .styles-final {
        padding: 90px 0;
    }


    .styles-final-box {
        padding: 65px;

        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 35px;

        border: 1px solid rgba(255,255,255,.08);
        border-radius: 26px;

        background:
            radial-gradient(
                circle at 80% 30%,
                rgba(121,55,255,.28),
                transparent 35%
            ),
            radial-gradient(
                circle at 20% 100%,
                rgba(247,37,133,.16),
                transparent 35%
            ),
            #100D25;
    }


    .styles-final-box h2 {
        margin: 0 0 10px;

        color: #FFFFFF;

        font-size: 38px;
        font-weight: 950;

        letter-spacing: -2px;
    }


    .styles-final-box p {
        max-width: 580px;

        margin: 0;

        color: #938D9F;

        font-size: 14px;
        line-height: 1.7;
    }


    .styles-final-button {
        min-height: 50px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        padding: 0 25px;

        border-radius: 12px;

        color: #FFFFFF;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #7937FF
            );

        text-decoration: none;

        font-size: 12px;
        font-weight: 900;

        white-space: nowrap;
    }


    @media(max-width:1000px) {

        .styles-grid {
            grid-template-columns:
                repeat(2,1fr);
        }

    }


    @media(max-width:650px) {

        .styles-container {
            width: calc(100% - 28px);
        }

        .styles-hero {
            min-height: 580px;

            background-size: auto 70%;
            background-position: 80% bottom;
        }

        .styles-copy h1 {
            letter-spacing: -2px;
        }

        .styles-grid {
            grid-template-columns: 1fr;
        }

        .styles-section-head,
        .styles-final-box {
            align-items: flex-start;
            flex-direction: column;
        }

        .styles-final-box {
            padding: 35px 24px;
        }

    }

</style>
@endpush


@section('content')


<section class="styles-hero">

    <div class="styles-container">

        <div class="styles-copy">

            <div class="styles-kicker">
                MOVE • EXPRESS • DISCOVER
            </div>

            <h1>
                Find the Style
                <span>That Moves You.</span>
            </h1>

            <p>
                Every dance has its own rhythm, energy and personality.
                Explore different styles and discover the one that feels
                right for you.
            </p>

        </div>

    </div>

</section>



<section class="styles-section">

    <div class="styles-container">

        <div class="styles-section-head">

            <div>

                <small>
                    Explore Dance
                </small>

                <h2>
                    Popular Ways to Move
                </h2>

            </div>


            <p>
                Whether you're looking for connection, confidence,
                fitness, performance or fun, there's a dance style
                waiting for you.
            </p>

        </div>


        <div class="styles-grid">


            <article class="style-card">

                <div class="style-card-content">

                    <div class="style-card-number">
                        01
                    </div>

                    <h3>
                        Latin Dance
                    </h3>

                    <p>
                        Discover social styles full of rhythm,
                        connection and energy.
                    </p>

                    <a href="{{ route('public.find-teacher') }}">
                        Find Latin Teachers →
                    </a>

                </div>

            </article>


            <article class="style-card">

                <div class="style-card-content">

                    <div class="style-card-number">
                        02
                    </div>

                    <h3>
                        Hip Hop
                    </h3>

                    <p>
                        Build confidence, musicality and movement
                        through high-energy urban styles.
                    </p>

                    <a href="{{ route('public.find-teacher') }}">
                        Find Hip Hop Teachers →
                    </a>

                </div>

            </article>


            <article class="style-card">

                <div class="style-card-content">

                    <div class="style-card-number">
                        03
                    </div>

                    <h3>
                        Ballroom
                    </h3>

                    <p>
                        Learn elegant partner dancing, technique
                        and confidence for any occasion.
                    </p>

                    <a href="{{ route('public.find-teacher') }}">
                        Find Ballroom Teachers →
                    </a>

                </div>

            </article>


            <article class="style-card">

                <div class="style-card-content">

                    <div class="style-card-number">
                        04
                    </div>

                    <h3>
                        Ballet
                    </h3>

                    <p>
                        Develop balance, posture, control and
                        beautiful classical technique.
                    </p>

                    <a href="{{ route('public.find-teacher') }}">
                        Find Ballet Teachers →
                    </a>

                </div>

            </article>


            <article class="style-card">

                <div class="style-card-content">

                    <div class="style-card-number">
                        05
                    </div>

                    <h3>
                        Contemporary
                    </h3>

                    <p>
                        Explore expressive movement, musicality
                        and creative freedom.
                    </p>

                    <a href="{{ route('public.find-teacher') }}">
                        Find Contemporary Teachers →
                    </a>

                </div>

            </article>


            <article class="style-card">

                <div class="style-card-content">

                    <div class="style-card-number">
                        06
                    </div>

                    <h3>
                        Wedding Dance
                    </h3>

                    <p>
                        Create a memorable first dance with
                        personalized private instruction.
                    </p>

                    <a href="{{ route('public.find-teacher') }}">
                        Find Wedding Dance Teachers →
                    </a>

                </div>

            </article>


        </div>

    </div>

</section>



<section class="styles-all">

    <div class="styles-container">

        <div class="styles-section-head">

            <div>

                <small>
                    All Styles
                </small>

                <h2>
                    Explore What DancePair Offers
                </h2>

            </div>


            <p>
                DancePair grows with its teachers.
                As new teachers join, more styles become available
                for students to discover.
            </p>

        </div>


        @isset($danceStyles)

            @if($danceStyles->count())

                <div class="styles-tags">

                    @foreach($danceStyles as $style)

                        <a
                            href="{{ route('public.find-teacher', ['dance_style_id' => $style->id]) }}"
                            class="styles-tag"
                        >
                            {{ $style->name }}
                        </a>

                    @endforeach

                </div>

            @endif

        @endisset

    </div>

</section>



<section class="styles-final">

    <div class="styles-container">

        <div class="styles-final-box">

            <div>

                <h2>
                    Found a Style You Love?
                </h2>

                <p>
                    Find a teacher who specializes in it,
                    compare profiles and start learning at your own pace.
                </p>

            </div>


            <a
                href="{{ route('public.find-teacher') }}"
                class="styles-final-button"
            >
                Find a Teacher
            </a>

        </div>

    </div>

</section>

@endsection