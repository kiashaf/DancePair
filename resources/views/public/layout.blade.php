<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'DancePair')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>

        html {
            scroll-behavior: smooth;
        }

        body.public-page {
            margin: 0;
            min-height: 100vh;

            color: #FFFFFF;
            background: #070615;
        }


        /* =====================================================
           NAVBAR
        ===================================================== */

        .public-navbar {
            position: relative;
            z-index: 1000;

            height: 92px;

            display: flex;
            align-items: center;

            border-bottom:
                1px solid rgba(255,255,255,.07);

            background: #050412;
        }


        .public-navbar-inner {
            width: min(
                1580px,
                calc(100% - 64px)
            );

            height: 92px;

            margin: 0 auto;

            display: flex;

            align-items: center;

            gap: 28px;
        }


        /* =====================================================
           LOGO
        ===================================================== */

        .public-brand {
            flex: 0 0 auto;

            display: flex;

            align-items: center;

            gap: 12px;

            min-width: 235px;

            color: #FFFFFF;

            text-decoration: none;
        }


        .public-brand-logo {
            width: 72px;
            height: 72px;

            flex: 0 0 72px;

            display: flex;
            align-items: center;
            justify-content: center;
        }


        .public-brand-logo img {
            width: 72px;
            height: 72px;

            max-width: none;
            max-height: none;

            display: block;

            object-fit: contain;
        }


        .public-brand-name {
            color: #FFFFFF;

            font-size: 27px;
            line-height: 1;

            font-weight: 900;

            letter-spacing: -1px;

            white-space: nowrap;
        }


        .public-brand-name span {
            color: #F72585;
        }


        /* =====================================================
           NAVIGATION
        ===================================================== */

        .public-nav-links {
            flex: 1;

            min-width: 0;

            display: flex;

            align-items: center;
            justify-content: center;

            gap: 21px;
        }


        .public-nav-links a {
            position: relative;

            height: 92px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            color: #FFFFFF;

            text-decoration: none;

            white-space: nowrap;

            font-size: 12px;
            font-weight: 750;

            transition:
                color .2s ease;
        }


        .public-nav-links a:hover {
            color: #F72585;
        }


        .public-nav-links a::after {
            content: "";

            position: absolute;

            left: 50%;
            bottom: 17px;

            width: 0;
            height: 3px;

            border-radius: 999px;

            background:
                linear-gradient(
                    90deg,
                    #F72585,
                    #8338EC
                );

            transform:
                translateX(-50%);

            transition:
                width .2s ease;
        }


        .public-nav-links a:hover::after,
        .public-nav-links a.active::after {
            width: 42px;
        }


        /* =====================================================
           ACCOUNT BUTTONS
        ===================================================== */

        .public-nav-actions {
            flex: 0 0 auto;

            display: flex;

            align-items: center;
            justify-content: flex-end;

            gap: 10px;
        }


        .public-login-btn,
        .public-logout-btn {
            min-height: 42px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            padding: 0 17px;

            border:
                1px solid rgba(255,255,255,.20);

            border-radius: 11px;

            color: #FFFFFF;

            background: transparent;

            text-decoration: none;

            font-size: 12px;
            font-weight: 800;

            cursor: pointer;
        }


        .public-register-btn,
        .public-dashboard-btn {
            min-height: 42px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            padding: 0 19px;

            border: 0;
            border-radius: 11px;

            color: #FFFFFF;

            background:
                linear-gradient(
                    90deg,
                    #F72585,
                    #8338EC
                );

            box-shadow:
                0 8px 25px
                rgba(247,37,133,.18);

            text-decoration: none;

            font-size: 12px;
            font-weight: 850;
        }


        .public-user-name {
            max-width: 100px;

            overflow: hidden;

            color: #FFFFFF;

            font-size: 12px;
            font-weight: 700;

            text-overflow: ellipsis;

            white-space: nowrap;
        }


        .public-nav-actions form {
            margin: 0;
        }


        /* =====================================================
           MAIN
        ===================================================== */

        .public-main {
            min-height: calc(100vh - 100px);

            background:
                radial-gradient(
                    circle at 80% 10%,
                    rgba(131,56,236,.08),
                    transparent 28%
                ),
                #070615;
        }


        /* =====================================================
           COMMON PAGE HERO
        ===================================================== */

        .public-page-hero {
            padding: 85px 0 65px;

            border-bottom:
                1px solid rgba(255,255,255,.06);

            background:
                radial-gradient(
                    circle at 75% 35%,
                    rgba(131,56,236,.17),
                    transparent 31%
                ),
                radial-gradient(
                    circle at 30% 50%,
                    rgba(247,37,133,.08),
                    transparent 27%
                );
        }


        .public-container {
            width: min(
                1450px,
                calc(100% - 80px)
            );

            margin: 0 auto;
        }


        .public-eyebrow {
            display: inline-flex;

            align-items: center;

            min-height: 34px;

            padding: 0 15px;

            margin-bottom: 22px;

            border:
                1px solid rgba(255,255,255,.10);

            border-radius: 999px;

            color: #E8B8D6;

            background:
                rgba(255,255,255,.07);

            font-size: 11px;
            font-weight: 850;

            letter-spacing: .10em;

            text-transform: uppercase;
        }


        .public-page-hero h1 {
            max-width: 900px;

            margin: 0;

            color: #FFFFFF;

            font-size: clamp(
                45px,
                5vw,
                76px
            );

            line-height: .98;

            font-weight: 950;

            letter-spacing: -3px;
        }


        .public-gradient-text {
            color: transparent;

            background:
                linear-gradient(
                    90deg,
                    #FF238C,
                    #B52CF1,
                    #7937FF
                );

            background-clip: text;
            -webkit-background-clip: text;
        }


        .public-page-hero p {
            max-width: 650px;

            margin: 23px 0 0;

            color: #B1ACBD;

            font-size: 17px;
            line-height: 1.75;
        }


        /* =====================================================
           COMMON SECTION
        ===================================================== */

        .public-section {
            padding: 70px 0;
        }


        .public-section-title {
            margin-bottom: 35px;
        }


        .public-section-title span {
            color: #F72585;

            font-size: 11px;
            font-weight: 850;

            letter-spacing: .11em;

            text-transform: uppercase;
        }


        .public-section-title h2 {
            margin: 7px 0 0;

            color: #FFFFFF;

            font-size: 34px;
            font-weight: 900;

            letter-spacing: -1px;
        }


        .public-section-title p {
            max-width: 620px;

            margin: 10px 0 0;

            color: #8F899D;

            font-size: 14px;
            line-height: 1.7;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .public-footer {
            padding: 30px 0;

            border-top:
                1px solid rgba(255,255,255,.07);

            background: #04030F;
        }


        .public-footer-inner {
            width: min(
                1450px,
                calc(100% - 80px)
            );

            margin: 0 auto;

            display: grid;

            grid-template-columns:
                1fr
                auto
                1fr;

            align-items: center;

            gap: 25px;
        }


        .public-footer-brand {
            display: flex;

            align-items: center;

            gap: 9px;

            color: #FFFFFF;

            text-decoration: none;

            font-size: 18px;
            font-weight: 900;
        }


        .public-footer-brand img {
            width: 48px;
            height: 48px;

            object-fit: contain;
        }


        .public-footer-brand span span {
            color: #F72585;
        }


        .public-footer-links {
            display: flex;

            align-items: center;

            justify-content: center;

            flex-wrap: wrap;

            gap: 22px;
        }


        .public-footer-links a {
            color: #8E899A;

            text-decoration: none;

            white-space: nowrap;

            font-size: 11px;

            transition:
                color .2s ease;
        }


        .public-footer-links a:hover {
            color: #FFFFFF;
        }


        .public-footer-copy {
            color: #625E6D;

            font-size: 10px;

            text-align: right;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media(max-width: 1420px) {

            .public-navbar-inner {
                gap: 20px;
            }

            .public-brand {
                min-width: 205px;
            }

            .public-brand-logo,
            .public-brand-logo img {
                width: 64px;
                height: 64px;

                flex-basis: 64px;
            }

            .public-brand-name {
                font-size: 23px;
            }

            .public-nav-links {
                gap: 15px;
            }

            .public-nav-links a {
                font-size: 11px;
            }

            .public-login-btn,
            .public-logout-btn,
            .public-register-btn,
            .public-dashboard-btn {
                padding-left: 14px;
                padding-right: 14px;

                font-size: 11px;
            }

        }


        @media(max-width: 1220px) {

            .public-brand {
                min-width: 72px;
            }

            .public-brand-name {
                display: none;
            }

            .public-nav-links {
                gap: 13px;
            }

            .public-nav-links a {
                font-size: 10px;
            }

            .public-login-btn,
            .public-logout-btn,
            .public-register-btn,
            .public-dashboard-btn {
                padding-left: 12px;
                padding-right: 12px;

                font-size: 10px;
            }

        }


        @media(max-width: 1050px) {

            .public-navbar {
                height: auto;

                min-height: 82px;

                padding: 10px 0;
            }

            .public-navbar-inner {
                height: auto;

                min-height: 62px;

                flex-wrap: wrap;

                justify-content: space-between;

                gap: 10px 18px;
            }

            .public-brand {
                min-width: auto;
            }

            .public-nav-actions {
                margin-left: auto;
            }

            .public-nav-links {
                order: 3;

                flex: 0 0 100%;

                width: 100%;

                overflow-x: auto;

                justify-content: flex-start;

                gap: 24px;

                scrollbar-width: thin;
            }

            .public-nav-links a {
                height: 45px;

                font-size: 11px;
            }

            .public-nav-links a::after {
                bottom: 0;
            }

        }


        @media(max-width: 650px) {

            .public-navbar-inner,
            .public-container,
            .public-footer-inner {
                width:
                    calc(100% - 28px);
            }

            .public-navbar-inner {
                gap: 10px;
            }

            .public-brand-logo,
            .public-brand-logo img {
                width: 54px;
                height: 54px;

                flex-basis: 54px;
            }

            .public-brand-name {
                display: none;
            }

            .public-nav-actions {
                gap: 7px;
            }

            .public-login-btn,
            .public-logout-btn,
            .public-register-btn,
            .public-dashboard-btn {
                min-height: 40px;

                padding-left: 11px;
                padding-right: 11px;

                font-size: 10px;
            }

            .public-user-name {
                display: none;
            }

            .public-page-hero {
                padding: 60px 0 50px;
            }

            .public-page-hero h1 {
                letter-spacing: -2px;
            }

            .public-footer-inner {
                display: flex;

                align-items: flex-start;

                flex-direction: column;
            }

            .public-footer-links {
                justify-content: flex-start;

                gap: 15px;
            }

            .public-footer-copy {
                text-align: left;
            }

        }

    </style>


    @stack('styles')

</head>


<body class="public-page">


{{-- =========================================================
   NAVBAR
========================================================= --}}

<nav class="public-navbar">

    <div class="public-navbar-inner">


        {{-- LOGO --}}
        <a
            href="{{ route('home') }}"
            class="public-brand"
        >

            <div class="public-brand-logo">

                <img
                    src="{{ asset('logo/logo.png') }}"
                    alt="DancePair"
                >

            </div>


            <div class="public-brand-name">
                Dance<span>Pair</span>
            </div>

        </a>



        {{-- NAVIGATION --}}
        <div class="public-nav-links">

            <a
                href="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'active' : '' }}"
            >
                Home
            </a>


            <a
                href="{{ route('public.find-teacher') }}"
                class="{{ request()->routeIs('public.find-teacher') ? 'active' : '' }}"
            >
                Find a Teacher
            </a>


            <a
                href="{{ route('public.become-teacher') }}"
                class="{{ request()->routeIs('public.become-teacher') ? 'active' : '' }}"
            >
                Become a Teacher
            </a>


            <a
                href="{{ route('public.dance-styles') }}"
                class="{{ request()->routeIs('public.dance-styles') ? 'active' : '' }}"
            >
                Dance Styles
            </a>


            <a
                href="{{ route('public.how-it-works') }}"
                class="{{ request()->routeIs('public.how-it-works') ? 'active' : '' }}"
            >
              Partnerships
            </a>


          <!--   <a
                href="{{ route('public.about') }}"
                class="{{ request()->routeIs('public.about') ? 'active' : '' }}"
            >
                About Us
            </a>

 -->
            <a
                href="{{ route('public.contact') }}"
                class="{{ request()->routeIs('public.contact') ? 'active' : '' }}"
            >
                Contact Us
            </a>

        </div>



        {{-- ACCOUNT --}}
        <div class="public-nav-actions">

            @auth

                @if(auth()->user()->role === 'teacher')

                    <a
                        href="{{ route('teacher.dashboard') }}"
                        class="public-dashboard-btn"
                    >
                        Dashboard
                    </a>

                @elseif(auth()->user()->role === 'student')

                    <a
                        href="{{ route('student.dashboard') }}"
                        class="public-dashboard-btn"
                    >
                        Dashboard
                    </a>

                @elseif(auth()->user()->role === 'admin')

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="public-dashboard-btn"
                    >
                        Dashboard
                    </a>

                @endif


                <span class="public-user-name">
                    {{ auth()->user()->name }}
                </span>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="public-logout-btn"
                    >
                        Logout
                    </button>

                </form>


            @else

                <a
                    href="{{ route('login') }}"
                    class="public-login-btn"
                >
                    Login
                </a>


                <a
                    href="{{ route('register') }}"
                    class="public-register-btn"
                >
                    Join Now
                </a>

            @endauth

        </div>

    </div>

</nav>



{{-- =========================================================
   PAGE CONTENT
========================================================= --}}

<main class="public-main">

    @yield('content')

</main>



{{-- =========================================================
   FOOTER
========================================================= --}}

<footer class="public-footer">

    <div class="public-footer-inner">


        <a
            href="{{ route('home') }}"
            class="public-footer-brand"
        >

            <x-ui.logo />

        </a>


        <div class="public-footer-links">

            <a href="{{ route('public.find-teacher') }}">
                Find a Teacher
            </a>


            <a href="{{ route('public.become-teacher') }}">
                Become a Teacher
            </a>


            <a href="{{ route('public.dance-styles') }}">
                Dance Styles
            </a>


            <a href="{{ route('public.how-it-works') }}">
                How It Works
            </a>


            <a href="{{ route('public.about') }}">
                About Us
            </a>


            <a href="{{ route('public.contact') }}">
                Contact
            </a>

        </div>


        <div class="public-footer-copy">

            © {{ date('Y') }} DancePair.
            All rights reserved.

        </div>

    </div>

</footer>


@stack('scripts')

</body>

</html>