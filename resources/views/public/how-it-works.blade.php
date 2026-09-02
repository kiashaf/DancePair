@extends('public.layout')

@section('title', 'Partnerships | DancePair')


@push('styles')

<style>

    /* =========================================================
       PARTNERSHIPS PAGE
    ========================================================= */

    .partnerships-page {
        background: #070615;
    }


    /* =========================================================
       HERO
    ========================================================= */

    .partnerships-hero {
        position: relative;

        overflow: hidden;

        padding:
            55px
            0
            65px;

        border-bottom:
            1px solid rgba(255,255,255,.06);

        background:
            radial-gradient(
                circle at 82% 30%,
                rgba(121,55,255,.18),
                transparent 30%
            ),
            radial-gradient(
                circle at 18% 35%,
                rgba(247,37,133,.09),
                transparent 28%
            ),
            #070615;
    }


    .partnerships-hero::after {
        content: "";

        position: absolute;

        width: 420px;
        height: 420px;

        right: -120px;
        top: -140px;

        border-radius: 50%;

        background:
            rgba(247,37,133,.06);

        filter: blur(60px);

        pointer-events: none;
    }


    .partnerships-container {
        position: relative;

        z-index: 2;

        width: min(
            1200px,
            calc(100% - 80px)
        );

        margin: 0 auto;
    }


    .partnerships-hero-content {
        max-width: 850px;
    }


    .partnerships-eyebrow {
        display: inline-flex;

        align-items: center;

        min-height: 34px;

        padding: 0 15px;

        margin-bottom: 20px;

        border:
            1px solid rgba(255,255,255,.10);

        border-radius: 999px;

        color: #F2B8D9;

        background:
            rgba(255,255,255,.06);

        font-size: 11px;
        font-weight: 850;

        letter-spacing: .11em;

        text-transform: uppercase;
    }


    .partnerships-hero h1 {
        max-width: 850px;

        margin: 0;

        color: #FFFFFF;

        font-size:
            clamp(
                48px,
                5vw,
                74px
            );

        line-height: 1;

        font-weight: 950;

        letter-spacing: -3px;
    }


    .partnerships-gradient {
        color: transparent;

        background:
            linear-gradient(
                90deg,
                #FF238C,
                #C52EF0,
                #7937FF
            );

        background-clip: text;
        -webkit-background-clip: text;
    }


    .partnerships-hero-description {
        max-width: 700px;

        margin: 22px 0 0;

        color: #AAA4B8;

        font-size: 16px;
        line-height: 1.75;
    }


    .partnerships-hero-action {
        margin-top: 27px;
    }


    .partnerships-primary-btn {
        min-height: 48px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        padding: 0 23px;

        border-radius: 12px;

        color: #FFFFFF;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #7937FF
            );

        box-shadow:
            0 12px 28px
            rgba(247,37,133,.20);

        text-decoration: none;

        font-size: 13px;
        font-weight: 850;

        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }


    .partnerships-primary-btn:hover {
        color: #FFFFFF;

        transform: translateY(-2px);

        box-shadow:
            0 16px 34px
            rgba(247,37,133,.26);
    }


    /* =========================================================
       PARTNERSHIP OPTIONS
    ========================================================= */

    .partnerships-section {
        padding:
            60px
            0
            70px;
    }


    .partnerships-section-header {
        max-width: 680px;

        margin-bottom: 32px;
    }


    .partnerships-section-header span {
        color: #F72585;

        font-size: 11px;
        font-weight: 850;

        letter-spacing: .10em;

        text-transform: uppercase;
    }


    .partnerships-section-header h2 {
        margin:
            7px
            0
            0;

        color: #FFFFFF;

        font-size: 34px;
        font-weight: 900;

        letter-spacing: -1px;
    }


    .partnerships-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 16px;
    }


    .partnership-card {
        min-height: 180px;

        padding: 25px;

        border:
            1px solid rgba(255,255,255,.075);

        border-radius: 18px;

        background:
            linear-gradient(
                145deg,
                rgba(255,255,255,.035),
                rgba(255,255,255,.012)
            );

        transition:
            transform .18s ease,
            border-color .18s ease;
    }


    .partnership-card:hover {
        transform: translateY(-3px);

        border-color:
            rgba(247,37,133,.28);
    }


    .partnership-card-icon {
        width: 44px;
        height: 44px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 18px;

        border-radius: 13px;

        color: #FFFFFF;

        background:
            linear-gradient(
                135deg,
                rgba(247,37,133,.25),
                rgba(121,55,255,.22)
            );

        font-size: 20px;
    }


    .partnership-card h3 {
        margin: 0;

        color: #FFFFFF;

        font-size: 17px;
        font-weight: 850;
    }


    .partnership-card p {
        margin:
            9px
            0
            0;

        color: #928C9E;

        font-size: 13px;
        line-height: 1.65;
    }


    /* =========================================================
       WHO CAN PARTNER
    ========================================================= */

    .partnership-types {
        padding:
            0
            0
            70px;
    }


    .partnership-types-box {
        padding: 30px;

        border:
            1px solid rgba(255,255,255,.07);

        border-radius: 20px;

        background: #0D0B20;
    }


    .partnership-types-box h2 {
        margin: 0;

        color: #FFFFFF;

        font-size: 28px;
        font-weight: 900;
    }


    .partnership-types-list {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 12px;

        margin-top: 24px;
    }


    .partnership-type {
        min-height: 54px;

        display: flex;

        align-items: center;

        padding: 0 16px;

        border:
            1px solid rgba(255,255,255,.065);

        border-radius: 12px;

        color: #D7D2DF;

        background:
            rgba(255,255,255,.022);

        font-size: 12px;
        font-weight: 750;
    }


    .partnership-type::before {
        content: "✓";

        margin-right: 10px;

        color: #F72585;

        font-weight: 900;
    }


    /* =========================================================
       CTA
    ========================================================= */

    .partnerships-cta {
        padding:
            0
            0
            75px;
    }


    .partnerships-cta-box {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 30px;

        padding:
            32px
            35px;

        border:
            1px solid rgba(247,37,133,.18);

        border-radius: 20px;

        background:
            linear-gradient(
                120deg,
                rgba(247,37,133,.10),
                rgba(121,55,255,.10)
            );
    }


    .partnerships-cta-copy h2 {
        margin: 0;

        color: #FFFFFF;

        font-size: 26px;
        font-weight: 900;
    }


    .partnerships-cta-copy p {
        margin:
            8px
            0
            0;

        color: #9791A5;

        font-size: 13px;
        line-height: 1.6;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width: 950px) {

        .partnerships-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }


        .partnership-types-list {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }


    @media(max-width: 720px) {

        .partnerships-container {
            width:
                calc(100% - 28px);
        }


        .partnerships-hero {
            padding:
                35px
                0
                45px;
        }


        .partnerships-hero h1 {
            letter-spacing: -2px;
        }


        .partnerships-grid {
            grid-template-columns: 1fr;
        }


        .partnership-types-list {
            grid-template-columns: 1fr;
        }


        .partnerships-cta-box {
            align-items: flex-start;

            flex-direction: column;
        }


        .partnerships-primary-btn {
            width: 100%;
        }

    }

</style>

@endpush



@section('content')

<div class="partnerships-page">


    {{-- =========================================================
       HERO
    ========================================================= --}}

    <section class="partnerships-hero">

        <div class="partnerships-container">

            <div class="partnerships-hero-content">


                <div class="partnerships-eyebrow">
                    Partnerships
                </div>


                <h1>

                    Grow Your Brand

                    <span class="partnerships-gradient">
                        With DancePair.
                    </span>

                </h1>


                <p class="partnerships-hero-description">

                    Promote your dance-related business,
                    event, product or service directly to
                    the DancePair community.

                </p>


                <div class="partnerships-hero-action">

                    <a
                        href="{{ route('public.contact') }}"
                        class="partnerships-primary-btn"
                    >
                        Contact Us
                    </a>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
       ADVERTISING OPTIONS
    ========================================================= --}}

    <section class="partnerships-section">

        <div class="partnerships-container">


            <div class="partnerships-section-header">

                <span>
                    Advertising & Collaboration
                </span>

                <h2>
                    Promote Your Business on DancePair
                </h2>

            </div>



            <div class="partnerships-grid">


                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ▣
                    </div>

                    <h3>
                        Website Advertising
                    </h3>

                    <p>
                        Promote your dance-related business,
                        product or service through advertising
                        placements on DancePair.
                    </p>

                </div>



                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ★
                    </div>

                    <h3>
                        Featured Promotion
                    </h3>

                    <p>
                        Give your business or service additional
                        visibility through featured placements
                        across the platform.
                    </p>

                </div>



                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ◉
                    </div>

                    <h3>
                        Event Promotion
                    </h3>

                    <p>
                        Promote dance events, workshops,
                        competitions, festivals and other
                        dance-related activities.
                    </p>

                </div>



                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ♫
                    </div>

                    <h3>
                        Dance Studios & Schools
                    </h3>

                    <p>
                        Introduce your studio, academy or
                        dance school to people actively
                        interested in dance.
                    </p>

                </div>



                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ◆
                    </div>

                    <h3>
                        Products & Services
                    </h3>

                    <p>
                        Promote dancewear, shoes, accessories,
                        photography, video services and other
                        businesses connected to dance.
                    </p>

                </div>



                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ↗
                    </div>

                    <h3>
                        Business Partnerships
                    </h3>

                    <p>
                        Work with DancePair on partnership
                        opportunities that connect your brand
                        with the dance community.
                    </p>

                </div>


            </div>

        </div>

    </section>



    {{-- =========================================================
       WHO CAN PARTNER
    ========================================================= --}}

    <section class="partnership-types">

        <div class="partnerships-container">

            <div class="partnership-types-box">


                <h2>
                    Dance-Related Businesses
                </h2>


                <div class="partnership-types-list">


                    <div class="partnership-type">
                        Dance Studios
                    </div>


                    <div class="partnership-type">
                        Dance Schools
                    </div>


                    <div class="partnership-type">
                        Dancewear & Shoes
                    </div>


                    <div class="partnership-type">
                        Dance Events
                    </div>


                    <div class="partnership-type">
                        Competitions
                    </div>


                    <div class="partnership-type">
                        Workshops
                    </div>


                    <div class="partnership-type">
                        Dance Photography
                    </div>


                    <div class="partnership-type">
                        Dance Video Services
                    </div>


                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
       CTA
    ========================================================= --}}

    <section class="partnerships-cta">

        <div class="partnerships-container">

            <div class="partnerships-cta-box">


                <div class="partnerships-cta-copy">

                    <h2>
                        Interested in working with DancePair?
                    </h2>

                    <p>
                        Contact us about advertising or partnership opportunities.
                    </p>

                </div>


                <a
                    href="{{ route('public.contact') }}"
                    class="partnerships-primary-btn"
                >
                    Contact Us
                </a>


            </div>

        </div>

    </section>


</div>

@endsection