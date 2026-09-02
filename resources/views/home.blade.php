<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>DancePair</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>

        /* =========================================================
           GLOBAL
        ========================================================= */

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body.home-page {
            margin: 0;

            background: #080717;
            color: #FFFFFF;

            font-family:
                Inter,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        a {
            text-decoration: none;
        }

        button,
        input,
        select {
            font-family: inherit;
        }


        /* =========================================================
           NAVBAR
        ========================================================= */

        .home-navbar {
            position: relative;
            z-index: 100;

            height: 92px;

            display: flex;
            align-items: center;

            background:
                rgba(5, 4, 19, .98);

            border-bottom:
                1px solid rgba(255,255,255,.07);
        }

        .home-navbar-inner {
            width: min(
                1580px,
                calc(100% - 64px)
            );

            margin: 0 auto;

            display: flex;
            align-items: center;

            gap: 32px;
        }


        /* =========================================================
           BRAND
        ========================================================= */

        .home-brand {
            flex: 0 0 auto;

            display: flex;
            align-items: center;

            gap: 12px;

            min-width: 245px;

            color: #FFFFFF;
        }

        .home-brand-logo {
            width: 74px;
            height: 74px;

            flex: 0 0 74px;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .home-brand-logo img {
            width: 74px;
            height: 74px;

            max-width: none;
            max-height: none;

            object-fit: contain;

            filter:
                drop-shadow(
                    0 0 8px rgba(247,37,133,.20)
                );
        }

        .home-brand-name {
            color: #FFFFFF;

            font-size: 29px;
            line-height: 1;

            font-weight: 900;

            letter-spacing: -1.2px;

            white-space: nowrap;
        }

        .home-brand-name span {
            background:
                linear-gradient(
                    90deg,
                    #FF2B91,
                    #C23CFF
                );

            -webkit-background-clip: text;
            background-clip: text;

            color: transparent;
        }

        .home-language-switch {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-shrink: 0;

            color: #777184;

            font-size: 11px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .home-language-switch a {
            color: #9B95A8;
            text-decoration: none;
            transition: color .2s ease;
        }

        .home-language-switch a:hover {
            color: #FFFFFF;
        }

        .home-language-switch a.active {
            color: #F72585;
        }


        /* =========================================================
           NAV LINKS
        ========================================================= */

        .home-nav-links {
            flex: 1;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 20px;
        }

        .home-nav-links a {
            position: relative;

            padding: 8px 0;

            color: #E8E5F0;

            font-size: 12px;
            font-weight: 700;

            white-space: nowrap;

            transition:
                color .2s ease;
        }

        .home-nav-links a:hover {
            color: #FFFFFF;
        }

        .home-nav-links a.active {
            color: #FFFFFF;
        }

        .home-nav-links a.active::after {
            content: "";

            position: absolute;

            left: 0;
            right: 0;
            bottom: -10px;

            height: 3px;

            border-radius: 999px;

            background:
                linear-gradient(
                    90deg,
                    #FF268E,
                    #853AFF
                );

            box-shadow:
                0 0 14px rgba(247,37,133,.65);
        }


        /* =========================================================
           NAV ACTIONS
        ========================================================= */

        .home-nav-actions {
            flex: 0 0 auto;

            display: flex;
            align-items: center;

            gap: 10px;
        }

        .home-dashboard-btn,
        .home-register-btn,
        .home-login-btn,
        .home-logout-btn {
            min-height: 42px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 0 18px;

            border-radius: 11px;

            font-size: 12px;
            font-weight: 800;

            cursor: pointer;

            transition:
                transform .18s ease,
                box-shadow .18s ease,
                border-color .18s ease;
        }

        .home-dashboard-btn,
        .home-register-btn {
            border: 0;

            color: #FFFFFF;

            background:
                linear-gradient(
                    90deg,
                    #F72585,
                    #7437FF
                );

            box-shadow:
                0 9px 24px rgba(247,37,133,.20);
        }

        .home-login-btn,
        .home-logout-btn {
            color: #FFFFFF;

            border:
                1px solid rgba(255,255,255,.20);

            background:
                rgba(255,255,255,.025);
        }

        .home-dashboard-btn:hover,
        .home-register-btn:hover,
        .home-login-btn:hover,
        .home-logout-btn:hover {
            transform: translateY(-2px);
        }

        .home-user-name {
            max-width: 110px;

            color: #E0DCE8;

            font-size: 11px;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        /* =========================================================
           HERO
        ========================================================= */

        .home-hero {
            position: relative;

            min-height: 610px;

            overflow: hidden;

            background-color: #080619;

            background-image:
                url('/images/home/hero-dance.jpg');

            background-repeat: no-repeat;

            background-size: 66% auto;

            background-position: right center;
        }

        .home-hero::before {
            content: "";

            position: absolute;
            inset: 0;

            z-index: 1;

            background:
                linear-gradient(
                    90deg,
                    rgba(5,3,18,1) 0%,
                    rgba(6,4,21,.98) 25%,
                    rgba(8,5,30,.88) 42%,
                    rgba(8,5,30,.42) 62%,
                    rgba(7,4,24,.12) 100%
                );
        }

        .home-hero::after {
            content: "";

            position: absolute;
            inset: 0;

            z-index: 2;

            pointer-events: none;

            background:
                radial-gradient(
                    circle at 79% 32%,
                    rgba(129,53,255,.14),
                    transparent 31%
                ),

                radial-gradient(
                    circle at 75% 82%,
                    rgba(247,37,133,.13),
                    transparent 30%
                );
        }

        .home-hero-inner {
            position: relative;
            z-index: 3;

            width: min(
                1580px,
                calc(100% - 64px)
            );

            min-height: 610px;

            margin: 0 auto;

            display: flex;
            align-items: center;
        }
        .home-hero-copy {
    width: 880px;
    max-width: none;
    padding-bottom: 0;
    transform: translateY(-80px);
}
       /*  .home-hero-copy {
            width: 590px;
            padding-bottom: 0;
            transform: translateY(-80px);
        } */


        /* =========================================================
           HERO EYEBROW
        ========================================================= */

        .home-hero-eyebrow {
            display: inline-flex;
            align-items: center;

            padding: 8px 15px;

            margin-bottom: 23px;

            border:
                1px solid rgba(255,255,255,.08);

            border-radius: 999px;

            background:
                rgba(255,255,255,.09);

            color: #F7B9DD;

            font-size: 10px;
            font-weight: 850;

            letter-spacing: .12em;

            text-transform: uppercase;
        }


        /* =========================================================
           HERO TITLE
        ========================================================= */

        .home-hero h1 {
            margin: 0;

            width: 650px;
            max-width: 100%;

            font-size:
                clamp(
                    54px,
                    5vw,
                    78px
                );

            line-height: .98;

            font-weight: 950;

            letter-spacing: -3.8px;
        }

        .home-hero h1 .white-line {
            display: block;

            color: #FFFFFF;

            white-space: nowrap;
        }

        .home-hero h1 .gradient-line {
    display: inline-block;

    width: auto;
    max-width: none;

    margin-top: 11px;

    padding-right: 20px;

    white-space: nowrap;
    overflow: visible;

    background:
        linear-gradient(
            90deg,
            #FF218B 0%,
            #F72585 28%,
            #C337F2 58%,
            #783AFF 100%
        );

    -webkit-background-clip: text;
    background-clip: text;

    color: transparent;
}

        /* =========================================================
           HERO DESCRIPTION
        ========================================================= */

        .home-hero-description {
            max-width: 500px;

            margin: 28px 0 0;

            color: #E5E1EB;

            font-size: 17px;
            line-height: 1.7;
        }


        /* =========================================================
           HERO BUTTONS
        ========================================================= */

        .home-hero-actions {
            margin-top: 29px;

            display: flex;
            align-items: center;

            gap: 13px;
        }

        .home-primary-btn,
        .home-secondary-btn {
            min-height: 49px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 9px;

            padding: 0 23px;

            border-radius: 12px;

            color: #FFFFFF;

            font-size: 12px;
            font-weight: 850;

            transition:
                transform .18s ease,
                box-shadow .18s ease;
        }

        .home-primary-btn {
            background:
                linear-gradient(
                    90deg,
                    #F72585,
                    #7437FF
                );

            box-shadow:
                0 12px 30px rgba(247,37,133,.22);
        }

        .home-secondary-btn {
            border:
                1px solid rgba(198,67,255,.68);

            background:
                rgba(8,5,26,.55);
        }

        .home-primary-btn:hover,
        .home-secondary-btn:hover {
            transform: translateY(-2px);

            color: #FFFFFF;
        }


        /* =========================================================
           TRUST ROW
        ========================================================= */

        .home-trust-row {
            margin-top: 34px;

            display: flex;
            align-items: center;

            gap: 26px;
        }

        .home-trust-item {
            display: flex;
            align-items: center;

            gap: 9px;

            padding-right: 25px;

            border-right:
                1px solid rgba(255,255,255,.09);
        }

        .home-trust-item:last-child {
            padding-right: 0;
            border-right: 0;
        }

        .home-trust-icon {
            width: 38px;
            height: 38px;

            flex: 0 0 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            background:
                rgba(255,216,77,.08);

            color: #FFD84D;

            font-size: 19px;
        }

        .home-trust-number {
            color: #FFFFFF;

            font-size: 14px;
            font-weight: 850;
        }

        .home-trust-label {
            margin-top: 2px;

            color: #9993AA;

            font-size: 8px;
        }


        /* =========================================================
           SEARCH WRAPPER
        ========================================================= */

        .home-search-wrapper {
            position: relative;
            z-index: 20;

            width: min(
                1420px,
                calc(100% - 80px)
            );

            margin: -175px auto 0;
        }

        .home-search-box {
            display: grid;

            grid-template-columns:
                1fr
                1fr
                235px;

            gap: 13px;

            align-items: end;

            padding: 18px;

            border:
                1px solid rgba(255,255,255,.14);

            border-radius: 18px;

            background:
                rgba(14,12,38,.96);

            backdrop-filter: blur(28px);

            box-shadow:
                0 30px 65px rgba(0,0,0,.35);
        }

        .home-search-field label {
            display: block;

            margin:
                0
                0
                7px
                4px;

            color: #DCD8E5;

            font-size: 9px;
            font-weight: 850;

            letter-spacing: .055em;

            text-transform: uppercase;
        }

        .home-search-field input,
        .home-search-field select {
            width: 100%;
            height: 47px;

            padding: 0 14px;

            border:
                1px solid rgba(255,255,255,.12);

            border-radius: 10px;

            outline: none;

            background: #18162F;

            color: #FFFFFF;

            font-size: 11px;
        }

        .home-search-field input::placeholder {
            color: #8D899A;
        }

        .home-search-field input:focus,
        .home-search-field select:focus {
            border-color:
                rgba(247,37,133,.65);

            box-shadow:
                0 0 0 3px
                rgba(247,37,133,.08);
        }

        .home-search-button {
            height: 47px;

            border: 0;
            border-radius: 10px;

            color: #FFFFFF;

            font-size: 12px;
            font-weight: 850;

            cursor: pointer;

            background:
                linear-gradient(
                    90deg,
                    #F72585,
                    #7437FF
                );

            box-shadow:
                0 11px 25px
                rgba(247,37,133,.20);
        }


        /* =========================================================
           BENEFITS
        ========================================================= */

        .home-benefits {
            padding:
                97px
                0
                34px;

            background:
                linear-gradient(
                    180deg,
                    #0A081D,
                    #080717
                );
        }

        .home-benefits-inner {
            width: min(
                1420px,
                calc(100% - 80px)
            );

            margin: 0 auto;

            display: grid;

            grid-template-columns:
                repeat(5, 1fr);

            overflow: hidden;

            border:
                1px solid rgba(255,255,255,.055);

            border-radius: 16px;

            background:
                rgba(255,255,255,.018);
        }

        .home-benefit {
            min-height: 92px;

            display: flex;
            align-items: center;

            gap: 12px;

            padding: 15px 20px;

            border-right:
                1px solid rgba(255,255,255,.07);
        }

        .home-benefit:last-child {
            border-right: 0;
        }

        .home-benefit-icon {
            width: 42px;
            height: 42px;

            flex: 0 0 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(238,54,217,.19),
                    rgba(113,55,255,.05)
                );

            color: #F04ADC;

            font-size: 20px;
        }

        .home-benefit-title {
            color: #FFFFFF;

            font-size: 10.5px;
            font-weight: 850;
        }

        .home-benefit-subtitle {
            margin-top: 3px;

            color: #8F899D;

            font-size: 8px;
        }


        /* =========================================================
           LOWER HOME
        ========================================================= */

        .home-lower {
            padding:
                17px
                0
                65px;

            background: #080717;
        }

        .home-lower-inner {
            width: min(
                1420px,
                calc(100% - 80px)
            );

            margin: 0 auto;
        }

        .home-section-header {
            margin-bottom: 13px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }

        .home-section-title {
            margin: 0;

            color: #FFFFFF;

            font-size: 19px;
            font-weight: 900;
        }

        .home-section-link {
            color: #F14AA7;

            font-size: 9px;
            font-weight: 750;
        }


        /* =========================================================
           DANCE STYLE CHIPS
        ========================================================= */

        .home-style-list {
            display: flex;
            flex-wrap: wrap;

            gap: 8px;

            margin-bottom: 38px;
        }

        .home-style-chip {
            padding:
                7px
                17px;

            border:
                1px solid rgba(255,255,255,.14);

            border-radius: 999px;

            background:
                rgba(255,255,255,.015);

            color: #D8D4E0;

            font-size: 9px;
            font-weight: 700;
        }

        .home-style-chip:first-child {
            border-color: transparent;

            color: #FFFFFF;

            background:
                linear-gradient(
                    90deg,
                    #F72585,
                    #B52EEA
                );
        }


        /* =========================================================
           FIND TEACHER MESSAGE
        ========================================================= */

        .home-teacher-placeholder {
            padding: 23px;

            border:
                1px solid rgba(255,255,255,.065);

            border-radius: 15px;

            background:
                rgba(255,255,255,.015);

            color: #8F899D;

            font-size: 10px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media(max-width: 1200px) {

            .home-nav-links {
                gap: 12px;
            }

            .home-nav-links a {
                font-size: 10px;
            }

            .home-brand {
                min-width: auto;
            }

            .home-brand-name {
                font-size: 24px;
            }

            .home-search-box {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .home-benefits-inner {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .home-benefit {
                border-bottom:
                    1px solid rgba(255,255,255,.06);
            }

        }


        @media(max-width: 950px) {

            .home-nav-links {
                display: none;
            }

            .home-navbar-inner {
                justify-content: space-between;
            }

            .home-hero-copy {
                width: 60%;
            }

            .home-brand-logo,
            .home-brand-logo img {
                width: 64px;
                height: 64px;
            }

        }


        @media(max-width: 720px) {

            .home-navbar {
                height: 78px;
            }

            .home-navbar-inner,
            .home-hero-inner,
            .home-search-wrapper,
            .home-benefits-inner,
            .home-lower-inner {
                width:
                    calc(100% - 28px);
            }

            .home-brand-name {
                display: none;
            }

            .home-brand-logo,
            .home-brand-logo img {
                width: 58px;
                height: 58px;
            }

            .home-user-name {
                display: none;
            }

            .home-hero {
                min-height: 690px;

                background-position:
                    63% center;
            }

            .home-hero::before {
                background:
                    linear-gradient(
                        180deg,
                        rgba(5,3,18,.35) 0%,
                        rgba(5,3,18,.70) 40%,
                        rgba(5,3,18,.97) 70%,
                        #070614 100%
                    );
            }

            .home-hero-inner {
                min-height: 690px;

                align-items: flex-end;
            }

            .home-hero-copy {
                width: 100%;

                padding-bottom: 125px;
            }

            .home-hero h1 {
                font-size: 46px;

                letter-spacing: -2.5px;
            }

            .home-hero h1 .white-line,
            .home-hero h1 .gradient-line {
                white-space: normal;
            }

            .home-hero-description {
                font-size: 14px;
            }

            .home-trust-row {
                flex-wrap: wrap;

                gap: 13px;
            }

            .home-trust-item {
                padding-right: 13px;
            }

            .home-search-box {
                grid-template-columns: 1fr;
            }

            .home-benefits-inner {
                grid-template-columns: 1fr;
            }

            .home-benefit {
                border-right: 0;
            }

        }


        /* =========================================================
           TOP RATED TEACHERS
        ========================================================= */

        .home-teachers-section {
            padding: 26px 0 55px;
            background: #080717;
        }

        .home-teachers-container {
            width: min(
                1420px,
                calc(100% - 80px)
            );

            margin: 0 auto;
        }

        .home-teachers-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;

            margin-bottom: 14px;
        }

        .home-teachers-header h2 {
            margin: 0;

            color: #FFFFFF;

            font-size: 18px;
            font-weight: 900;
        }

        .home-view-all-teachers {
            color: #F72585;

            font-size: 9px;
            font-weight: 800;
        }

        .home-teachers-grid {
            display: grid;

            grid-template-columns:
                repeat(5, minmax(0, 1fr));

            gap: 12px;
        }

        .home-teacher-card {
            min-width: 0;

            padding: 10px;

            border:
                1px solid rgba(255,255,255,.07);

            border-radius: 12px;

            background: #100E25;

            transition:
                transform .18s ease,
                border-color .18s ease;
        }

        .home-teacher-card:hover {
            transform: translateY(-3px);

            border-color:
                rgba(247,37,133,.34);
        }

        .home-teacher-card-top {
            display: flex;
            align-items: center;

            gap: 10px;
        }

        .home-teacher-photo-wrap {
            width: 64px;
            height: 74px;

            flex: 0 0 64px;

            overflow: hidden;

            border-radius: 9px;
        }

        .home-teacher-photo {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;
        }

        .home-teacher-photo-fallback {
            width: 100%;
            height: 100%;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #FFFFFF;

            font-size: 22px;
            font-weight: 900;

            background:
                linear-gradient(
                    135deg,
                    #F72585,
                    #7437FF
                );
        }

        .home-teacher-main-info {
            min-width: 0;
        }

        .home-teacher-main-info h3 {
            margin: 0 0 5px;

            overflow: hidden;

            color: #FFFFFF;

            font-size: 12px;
            font-weight: 850;

            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .home-teacher-rating {
            display: flex;
            align-items: center;

            gap: 5px;
        }

        .home-teacher-stars {
            display: flex;

            gap: 1px;
        }

        .home-teacher-stars span {
            font-size: 9px;
        }

        .star-active {
            color: #FFD84D;
        }

        .star-empty {
            color: #39344A;
        }

        .home-teacher-review-count {
            color: #928C9D;

            font-size: 7px;
        }

        .home-teacher-location {
            margin-top: 5px;

            color: #9993AA;

            font-size: 7px;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .home-teacher-styles {
            display: flex;
            align-items: center;

            gap: 4px;

            margin-top: 8px;
        }

        .home-teacher-styles span {
            padding: 3px 6px;

            border-radius: 999px;

            color: #E7DDEB;

            background:
                rgba(247,37,133,.09);

            font-size: 7px;
        }

        .home-teacher-styles .more-styles {
            color: #F72585;
        }

        .home-teacher-card-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;

            gap: 8px;

            margin-top: 9px;

            padding-top: 8px;

            border-top:
                1px solid rgba(255,255,255,.055);
        }

        .home-teacher-rate small {
            display: block;

            color: #8E899C;

            font-size: 7px;
        }

        .home-teacher-rate strong {
            display: block;

            margin-top: 2px;

            color: #FFFFFF;

            font-size: 11px;
            font-weight: 900;
        }

        .home-teacher-profile-btn {
            min-height: 27px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 0 9px;

            border-radius: 7px;

            color: #FFFFFF;

            font-size: 7px;
            font-weight: 850;

            background:
                linear-gradient(
                    90deg,
                    #F72585,
                    #7437FF
                );
        }

        .home-no-teachers {
            padding: 24px;

            text-align: center;

            border:
                1px dashed rgba(255,255,255,.09);

            border-radius: 12px;

            color: #938DA2;

            font-size: 10px;
        }

        @media(max-width: 1200px) {

            .home-teachers-grid {
                grid-template-columns:
                    repeat(4, minmax(0, 1fr));
            }

        }

        @media(max-width: 950px) {

            .home-teachers-grid {
                grid-template-columns:
                    repeat(3, minmax(0, 1fr));
            }

        }

        @media(max-width: 720px) {

            .home-teachers-container {
                width:
                    calc(100% - 28px);
            }

            .home-teachers-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

        }

        @media(max-width: 500px) {

            .home-teachers-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>


<body class="home-page">


{{-- =========================================================
   NAVBAR
========================================================= --}}

<nav class="home-navbar">

    <div class="home-navbar-inner">


        {{-- BRAND --}}
        <a
            href="{{ route('home') }}"
            class="home-brand"
        >

            <div class="home-brand-logo">

                <img
                    src="{{ asset('logo/logo.png') }}"
                    alt="DancePair"
                >

            </div>


            <div class="home-brand-name">

                Dance<span>Pair</span>

            </div>

        </a>



        {{-- NAV LINKS --}}
        <div class="home-nav-links">

            <a
                href="{{ route('home') }}"
                class="active"
            >
                {{ __('common.home') }}
            </a>

            <a href="{{ route('public.find-teacher') }}">
                {{ __('common.find_teacher') }}
            </a>

            <a href="{{ route('public.become-teacher') }}">
                {{ __('common.become_teacher') }}
            </a>

            <a href="{{ route('public.dance-styles') }}">
                {{ __('common.dance_styles') }}
            </a>

            <a href="{{ route('public.how-it-works') }}">
                {{ __('common.partnerships') }}
            </a>

            <a href="{{ route('public.contact') }}">
                {{ __('common.contact') }}
            </a>

        </div>



        {{-- NAV ACTIONS --}}
        <div class="home-nav-actions">

            {{-- Language Switch --}}
            <div class="home-language-switch">

                <a
                    href="{{ route('language.switch', 'en') }}"
                    class="{{ app()->getLocale() === 'en' ? 'active' : '' }}"
                >
                    EN
                </a>

                <span>/</span>

                <a
                    href="{{ route('language.switch', 'fr') }}"
                    class="{{ app()->getLocale() === 'fr' ? 'active' : '' }}"
                >
                    FR
                </a>

            </div>


            @auth

                @if(auth()->user()->role === 'teacher')

                    <a
                        href="{{ route('teacher.dashboard') }}"
                        class="home-dashboard-btn"
                    >
                        {{ __('common.dashboard') }}
                    </a>

                @elseif(auth()->user()->role === 'student')

                    <a
                        href="{{ route('student.dashboard') }}"
                        class="home-dashboard-btn"
                    >
                        {{ __('common.dashboard') }}
                    </a>

                @elseif(auth()->user()->role === 'admin')

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="home-dashboard-btn"
                    >
                        {{ __('common.dashboard') }}
                    </a>

                @endif


                <span class="home-user-name">
                    {{ auth()->user()->name }}
                </span>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="home-logout-btn"
                    >
                        {{ __('common.logout') }}
                    </button>

                </form>


            @else

                <a
                    href="{{ route('login') }}"
                    class="home-login-btn"
                >
                    {{ __('common.login') }}
                </a>


                <a
                    href="{{ route('register') }}"
                    class="home-register-btn"
                >
                    {{ __('common.join_now') }}
                </a>

            @endauth

        </div>

    </div>

</nav>



{{-- =========================================================
   HERO
========================================================= --}}

<section class="home-hero">

    <div class="home-hero-inner">

        <div class="home-hero-copy">

            <div class="home-hero-eyebrow">
                {{ __('home.hero_eyebrow') }}
            </div>


            <h1>

                <span class="white-line">
                    {{ __('home.hero_title_white') }}
                </span>

                <span class="gradient-line">
                    {{ __('home.hero_title_gradient') }}
                </span>

            </h1>


            <p class="home-hero-description">

                {{ __('home.hero_description_1') }}

                <br>

                {{ __('home.hero_description_2') }}

            </p>


            <!--
            <div class="home-hero-actions">

                <a
                    href="{{ route('public.how-it-works') }}"
                    class="home-secondary-btn"
                >
                    How It Works
                    <span>↓</span>
                </a>

            </div>
            -->

        </div>

    </div>

</section>



{{-- =========================================================
   SEARCH
========================================================= --}}

<div
    class="home-search-wrapper"
    id="teacher-search"
>

    <form
        class="home-search-box"
        method="GET"
        action="{{ route('home') }}"
    >


        {{-- LOCATION --}}
        <div class="home-search-field">

            <label>
                {{ __('home.city') }}
            </label>

            <input
                type="text"
                name="location"
                value="{{ request('location') }}"
                placeholder="{{ __('home.city_placeholder') }}"
            >

        </div>



        {{-- DANCE STYLE --}}
        <div class="home-search-field">

            <label>
                {{ __('home.dance_style') }}
            </label>

            <select name="dance_style_id">

                <option value="">
                    {{ __('home.all_dance_styles') }}
                </option>


                @if(isset($danceStyles))

                    @foreach($danceStyles as $danceStyle)

                        <option
                            value="{{ $danceStyle->id }}"
                            @selected(
                                (string) request('dance_style_id')
                                ===
                                (string) $danceStyle->id
                            )
                        >
                            {{ $danceStyle->name }}
                        </option>

                    @endforeach

                @endif

            </select>

        </div>



        <button
            type="submit"
            class="home-search-button"
        >
            {{ __('home.find_my_teacher') }}
        </button>


    </form>

</div>



{{-- =========================================================
   TOP RATED TEACHERS
========================================================= --}}

<section class="home-teachers-section">

    <div class="home-teachers-container">

        <div class="home-teachers-header">

            <h2>

                @if(
                    request()->filled('location')
                    ||
                    request()->filled('dance_style_id')
                )

                    {{ __('home.teacher_results') }}

                @else

                    {{ __('home.top_rated_teachers') }}

                @endif

            </h2>


            <a
                href="{{ route('public.find-teacher') }}"
                class="home-view-all-teachers"
            >
                {{ __('home.view_all_teachers') }}
            </a>

        </div>


        @if(isset($topTeachers) && $topTeachers->count())

            <div class="home-teachers-grid">

                @foreach($topTeachers->take(10) as $teacher)

                    @php

                        $averageRating =
                            (float) ($teacher->reviews_avg_rating ?? 0);

                        $reviewCount =
                            (int) ($teacher->reviews_count ?? 0);


                        $minimumRate =
                            $teacher
                                ->danceStyles
                                ->map(function ($style) {
                                    return $style->pivot->hourly_rate ?? null;
                                })
                                ->filter(function ($rate) {
                                    return $rate !== null && $rate > 0;
                                })
                                ->min();


                        $teacherName =
                            $teacher->user->name ?? __('home.dance_teacher');


                        $teacherInitial =
                            strtoupper(
                                mb_substr(
                                    $teacherName,
                                    0,
                                    1
                                )
                            );

                    @endphp


                    <article class="home-teacher-card">

                        <div class="home-teacher-card-top">

                            <div class="home-teacher-photo-wrap">

                                @if($teacher->profile_photo)

                                    <img
                                        src="{{ asset('storage/' . $teacher->profile_photo) }}"
                                        alt="{{ $teacherName }}"
                                        class="home-teacher-photo"
                                    >

                                @else

                                    <div class="home-teacher-photo-fallback">
                                        {{ $teacherInitial }}
                                    </div>

                                @endif

                            </div>


                            <div class="home-teacher-main-info">

                                <h3>
                                    {{ $teacherName }}
                                </h3>


                                <div class="home-teacher-rating">

                                    <div class="home-teacher-stars">

                                        @for($i = 1; $i <= 5; $i++)

                                            <span
                                                class="{{
                                                    $i <= round($averageRating)
                                                        ? 'star-active'
                                                        : 'star-empty'
                                                }}"
                                            >
                                                ★
                                            </span>

                                        @endfor

                                    </div>


                                    @if($reviewCount > 0)

                                        <span class="home-teacher-review-count">

                                            {{ number_format($averageRating, 1) }}
                                            ({{ $reviewCount }})

                                        </span>

                                    @else

                                        <span class="home-teacher-review-count">
                                            {{ __('home.new') }}
                                        </span>

                                    @endif

                                </div>


                                @if($teacher->city)

                                    <div class="home-teacher-location">

                                        📍 {{ $teacher->city }}

                                    </div>

                                @endif

                            </div>

                        </div>



                        @if($teacher->danceStyles->count())

                            <div class="home-teacher-styles">

                                @foreach($teacher->danceStyles->take(2) as $style)

                                    <span>
                                        {{ $style->name }}
                                    </span>

                                @endforeach


                                @if($teacher->danceStyles->count() > 2)

                                    <span class="more-styles">

                                        +{{ $teacher->danceStyles->count() - 2 }}

                                    </span>

                                @endif

                            </div>

                        @endif



                        <div class="home-teacher-card-footer">

                            <div class="home-teacher-rate">

                                @if($minimumRate)

                                    <small>
                                        {{ __('home.from') }}
                                    </small>

                                    <strong>
                                        ${{ number_format($minimumRate, 0) }}/h
                                    </strong>

                                @else

                                    <small>
                                        {{ __('home.view_rates') }}
                                    </small>

                                @endif

                            </div>


                            @auth

                                @if(auth()->user()->role === 'student')

                                    <a
                                        href="{{ route('student.teachers.show', $teacher) }}"
                                        class="home-teacher-profile-btn"
                                    >
                                        {{ __('home.view_profile') }}
                                    </a>

                                @else

                                    <a
                                        href="{{ route('public.find-teacher') }}"
                                        class="home-teacher-profile-btn"
                                    >
                                        {{ __('home.view_profile') }}
                                    </a>

                                @endif

                            @else

                                <a
                                    href="{{ route('public.find-teacher') }}"
                                    class="home-teacher-profile-btn"
                                >
                                    {{ __('home.view_profile') }}
                                </a>

                            @endauth

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="home-no-teachers">

                @if(
                    request()->filled('location')
                    ||
                    request()->filled('dance_style_id')
                )

                    {{ __('home.no_search_results') }}

                @else

                    {{ __('home.no_teachers') }}

                @endif

            </div>

        @endif

    </div>

</section>


</body>

</html>