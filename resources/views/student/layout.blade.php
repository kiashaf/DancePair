<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', __('student.panel')) | DancePair
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="student-panel">


@php

    $sidebarUnreadNotifications =
        auth()->check()
            ? auth()->user()->unreadNotifications()->count()
            : 0;

@endphp


<div class="container-fluid">

    <div class="row">


        {{-- =================================================
           SIDEBAR
        ================================================== --}}

        <div class="col-md-3 col-lg-2 sidebar p-4">


            <x-ui.logo />


            <div class="mt-4">


                {{-- DASHBOARD --}}
                <a
                    href="{{ route('student.dashboard') }}"

                    class="
                        {{ request()->routeIs('student.dashboard')
                            ? 'active'
                            : ''
                        }}
                    "

                    style="
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                    "
                >

                    <span>
                        {{ __('student.dashboard') }}
                    </span>


                    @if($sidebarUnreadNotifications > 0)

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
                                color:white;

                                font-size:10px;
                                font-weight:700;
                            "
                        >
                            {{ $sidebarUnreadNotifications }}
                        </span>

                    @endif

                </a>


                {{-- FIND TEACHERS --}}
                <a
                    href="{{ route('student.teachers') }}"

                    class="
                        {{ request()->routeIs('student.teachers*')
                            ? 'active'
                            : ''
                        }}
                    "
                >
                    {{ __('student.find_teachers') }}
                </a>


                {{-- BOOKINGS --}}
                <a
                    href="{{ route('student.bookings') }}"

                    class="
                        {{ request()->routeIs('student.bookings*')
                            ? 'active'
                            : ''
                        }}
                    "
                >
                    {{ __('student.my_bookings') }}
                </a>


                {{-- PAYMENTS --}}
                <a
                    href="{{ route('student.payments.index') }}"

                    class="
                        {{ request()->routeIs('student.payments.*')
                            ? 'active'
                            : ''
                        }}
                    "
                >
                    {{ __('student.payments') }}
                </a>


                {{-- REVIEWS --}}
                <a
                    href="{{ route('student.reviews') }}"

                    class="
                        {{ request()->routeIs('student.reviews')
                            ? 'active'
                            : ''
                        }}
                    "
                >
                    {{ __('student.reviews') }}
                </a>


                {{-- PROFILE --}}
                <a
                    href="{{ route('student.profile.edit') }}"

                    class="
                        {{ request()->routeIs('student.profile.*')
                            ? 'active'
                            : ''
                        }}
                    "
                >
                    {{ __('student.my_profile') }}
                </a>

            </div>


            <hr class="student-sidebar-separator">


            <a href="{{ route('home') }}">
                {{ __('student.home') }}
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
                    {{ __('student.logout') }}
                </button>

            </form>

        </div>



        {{-- =================================================
           MAIN CONTENT
        ================================================== --}}

        <div class="col-md-9 col-lg-10 p-0 main-content">


            {{-- TOP BAR --}}
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
                        {{ __('student.welcome_back') }},
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
                    <div
                        style="
                            display:flex;
                            align-items:center;
                            gap:6px;
                            font-size:12px;
                            font-weight:700;
                        "
                    >

                        <a
                            href="{{ route('language.switch', 'en') }}"
                            style="
                                text-decoration:none;
                                color:
                                    {{ app()->getLocale() === 'en'
                                        ? '#F72585'
                                        : '#6B7280'
                                    }};
                            "
                        >
                            EN
                        </a>

                        <span style="color:#9CA3AF;">
                            /
                        </span>

                        <a
                            href="{{ route('language.switch', 'fr') }}"
                            style="
                                text-decoration:none;
                                color:
                                    {{ app()->getLocale() === 'fr'
                                        ? '#F72585'
                                        : '#6B7280'
                                    }};
                            "
                        >
                            FR
                        </a>

                    </div>


                    {{-- NOTIFICATION INDICATOR --}}
                    <a
                        href="{{ route('student.dashboard') }}"
                        style="
                            position:relative;
                            width:38px;
                            height:38px;

                            display:flex;
                            align-items:center;
                            justify-content:center;

                            border-radius:50%;

                            background:#F1F8FC;

                            color:#0369A1;

                            text-decoration:none;

                            font-size:18px;
                        "
                        title="{{ __('student.notifications') }}"
                    >

                        🔔


                        @if($sidebarUnreadNotifications > 0)

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
                                {{ $sidebarUnreadNotifications }}
                            </span>

                        @endif

                    </a>


                    {{-- AVATAR --}}
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