<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', __('teacher.panel')) | DancePair
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>

        body {
            background: #F6F4FB;
        }

        .sidebar {
            min-height: 100vh;
            background: #111827;
            color: white;
        }

        .sidebar a {
            color: #D1D5DB;
            text-decoration: none;
            display: block;
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 6px;
            transition: .2s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #1F2937;
            color: white;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #E5E7EB;
        }

        .avatar {
            width: 54px;
            height: 54px;

            border-radius: 50%;

            background: #EDE9FE;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #6D28D9;

            font-weight: 800;
            font-size: 20px;
        }

        .main-content {
            min-height: 100vh;
        }


        /* =========================================================
           LANGUAGE SWITCH
        ========================================================= */

        .teacher-language-switch {
            display: flex;
            align-items: center;
            gap: 5px;

            font-size: 11px;
            font-weight: 800;
        }

        .teacher-language-switch a {
            color: #64748B;
            text-decoration: none;
        }

        .teacher-language-switch a:hover {
            color: #111827;
        }

        .teacher-language-switch a.active {
            color: #7C3AED;
        }

        .teacher-language-switch span {
            color: #CBD5E1;
        }

    </style>

</head>


<body class="teacher-panel">


@php

    $teacherUnreadNotifications =
        auth()->check()
            ? auth()->user()
                ->unreadNotifications()
                ->count()
            : 0;

@endphp


<div class="container-fluid">

    <div class="row">


        {{-- SIDEBAR --}}
        <div class="col-md-3 col-lg-2 sidebar p-4">


            <x-ui.logo />


            <div class="mt-4">


                <a
                    href="{{ route('teacher.dashboard') }}"
                    class="{{ request()->routeIs('teacher.dashboard')
                        ? 'active'
                        : ''
                    }}"
                    style="
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                    "
                >

                    <span>
                        {{ __('teacher.dashboard') }}
                    </span>


                    @if($teacherUnreadNotifications > 0)

                        <span
                            style="
                                min-width:22px;
                                height:22px;
                                padding:0 6px;

                                border-radius:999px;

                                display:flex;
                                align-items:center;
                                justify-content:center;

                                background:#EF4444;
                                color:#FFFFFF;

                                font-size:10px;
                                font-weight:700;
                            "
                        >
                            {{ $teacherUnreadNotifications }}
                        </span>

                    @endif

                </a>


                <a
                    href="{{ route('teacher.profile.edit') }}"
                    class="{{ request()->routeIs('teacher.profile.*')
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ __('teacher.my_profile') }}
                </a>


                <a
                    href="{{ route('teacher.bookings') }}"
                    class="{{ request()->routeIs('teacher.bookings*')
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ __('teacher.bookings') }}
                </a>


                <a
                    href="{{ route('teacher.availability') }}"
                    class="{{ request()->routeIs('teacher.availability')
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ __('teacher.availability') }}
                </a>


                <a
                    href="{{ route('teacher.reviews') }}"
                    class="{{ request()->routeIs('teacher.reviews')
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ __('teacher.reviews') }}
                </a>


                <a
                    href="{{ route('teacher.earnings') }}"
                    class="{{ request()->routeIs('teacher.earnings')
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ __('teacher.earnings') }}
                </a>

            </div>


            <hr class="border-secondary my-4">


            <a href="/">
                {{ __('teacher.home') }}
            </a>


            <form
                method="POST"
                action="{{ route('logout') }}"
                class="mt-4"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-light w-100"
                >
                    {{ __('teacher.logout') }}
                </button>

            </form>

        </div>



        {{-- MAIN --}}
        <div class="col-md-9 col-lg-10 p-0 main-content">


            <div
                class="
                    topbar
                    px-4
                    py-3
                    d-flex
                    justify-content-between
                    align-items-center
                "
            >

                <div>

                    <h4 class="mb-0">
                        @yield('page-title')
                    </h4>

                    <small class="text-muted">
                        {{ __('teacher.welcome_back') }},
                        {{ auth()->user()->name }}
                    </small>

                </div>


                <div
                    style="
                        display:flex;
                        align-items:center;
                        gap:14px;
                    "
                >


                    {{-- LANGUAGE SWITCH --}}
                    <div class="teacher-language-switch">

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


                    {{-- NOTIFICATION BELL --}}
                    <a
                        href="{{ route('teacher.dashboard') }}"
                        title="{{ __('teacher.notifications') }}"
                        style="
                            position:relative;

                            width:38px;
                            height:38px;

                            display:flex;
                            align-items:center;
                            justify-content:center;

                            border-radius:50%;

                            background:#F3F0FF;
                            color:#6D28D9;

                            text-decoration:none;

                            font-size:18px;
                        "
                    >

                        🔔


                        @if($teacherUnreadNotifications > 0)

                            <span
                                style="
                                    position:absolute;

                                    top:-3px;
                                    right:-3px;

                                    min-width:17px;
                                    height:17px;

                                    padding:0 4px;

                                    border-radius:999px;

                                    display:flex;
                                    align-items:center;
                                    justify-content:center;

                                    background:#EF4444;
                                    color:#FFFFFF;

                                    font-size:8px;
                                    font-weight:700;
                                "
                            >
                                {{ $teacherUnreadNotifications }}
                            </span>

                        @endif

                    </a>


                    <div class="avatar">

                        {{ strtoupper(
                            substr(
                                auth()->user()->name,
                                0,
                                1
                            )
                        ) }}

                    </div>

                </div>

            </div>


            <main class="p-4">
                @yield('content')
            </main>

        </div>

    </div>

</div>

@include('partials.platform-message-widget')
</body>

</html>