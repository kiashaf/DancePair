<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('logo/logo.png') }}"
    >

    <link
        rel="shortcut icon"
        type="image/png"
        href="{{ asset('logo/logo.png') }}"
    >

    <link
        rel="apple-touch-icon"
        href="{{ asset('logo/logo.png') }}"
    >


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


        /* =========================================================
           USER MENU
        ========================================================= */

        .dp-user-menu {
            position: relative;
            flex-shrink: 0;
        }


        .dp-user-menu-toggle {
            border: 0;
            padding: 0;
            margin: 0;

            cursor: pointer;

            overflow: hidden;
        }


        .dp-user-menu-dropdown {
            position: absolute;

            top: calc(100% + 10px);
            right: 0;

            width: 180px;

            display: none;

            padding: 8px;

            border: 1px solid #E5E7EB;
            border-radius: 12px;

            background: #FFFFFF;

            box-shadow:
                0 12px 30px
                rgba(15, 23, 42, .14);

            z-index: 99999;
        }


        .dp-user-menu.is-open
        .dp-user-menu-dropdown {
            display: block;
        }


        .dp-user-menu-home {
            display: block;

            width: 100%;

            padding: 10px 12px;

            border-radius: 8px;

            color: #334155;

            font-size: 12px;
            font-weight: 700;

            text-decoration: none;

            transition:
                background .15s ease,
                color .15s ease;
        }


        .dp-user-menu-home:hover {
            background: #F8FAFC;
            color: #111827;
        }


        .dp-user-menu-form {
            margin: 0;
        }


        .dp-user-menu-logout {
            display: block;

            width: 100%;

            padding: 10px 12px;

            border: 0;
            border-radius: 8px;

            background: transparent;

            color: #DC2626;

            font-family: inherit;
            font-size: 12px;
            font-weight: 700;

            text-align: left;

            cursor: pointer;

            transition:
                background .15s ease,
                color .15s ease;
        }


        .dp-user-menu-logout:hover {
            background: #FEF2F2;
            color: #B91C1C;
        }


        .dp-user-menu-avatar-image {
            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;

            border-radius: 50%;
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


        {{-- =================================================
           SIDEBAR
        ================================================== --}}

        <div
            class="col-md-3 col-lg-2 sidebar p-4"
            data-mobile-sidebar
        >


            {{-- =================================================
               MOBILE SIDEBAR CLOSE BUTTON
            ================================================== --}}

            <button
                type="button"
                class="mobile-sidebar-close"
                data-mobile-sidebar-close
                aria-label="{{ app()->getLocale() === 'fr'
                    ? 'Fermer le menu'
                    : 'Close menu'
                }}"
            >
                ×
            </button>


            <x-ui.logo />


            <div class="mt-4">


                {{-- DASHBOARD --}}

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


                {{-- MY PROFILE --}}

                <a
                    href="{{ route('teacher.profile.edit') }}"
                    class="{{ request()->routeIs('teacher.profile.*')
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ __('teacher.my_profile') }}
                </a>


                {{-- BOOKINGS --}}

                <a
                    href="{{ route('teacher.bookings') }}"
                    class="{{ request()->routeIs('teacher.bookings*')
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ __('teacher.bookings') }}
                </a>


                {{-- AVAILABILITY --}}

                <a
                    href="{{ route('teacher.availability') }}"
                    class="{{ request()->routeIs('teacher.availability')
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ __('teacher.availability') }}
                </a>


                {{-- REVIEWS --}}

                <a
                    href="{{ route('teacher.reviews') }}"
                    class="{{ request()->routeIs('teacher.reviews')
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ __('teacher.reviews') }}
                </a>


                {{-- EARNINGS --}}

                <a
                    href="{{ route('teacher.earnings') }}"
                    class="{{ request()->routeIs('teacher.earnings')
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ __('teacher.earnings') }}
                </a>


                {{-- PROMOTIONS --}}

                <a
                    href="{{ route('teacher.promotions.index') }}"
                    class="{{ request()->routeIs('teacher.promotions.*')
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ app()->getLocale() === 'fr'
                        ? 'Promotions'
                        : 'Promotions'
                    }}
                </a>

            </div>


            <hr class="border-secondary my-4">


            {{-- HOME --}}

            <a href="/">
                {{ __('teacher.home') }}
            </a>


            {{-- LOGOUT --}}

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



        {{-- =================================================
           MOBILE SIDEBAR OVERLAY
        ================================================== --}}

        <div
            class="mobile-sidebar-overlay"
            data-mobile-sidebar-overlay
        ></div>



        {{-- =================================================
           MAIN
        ================================================== --}}

        <div class="col-md-9 col-lg-10 p-0 main-content">


            {{-- =================================================
               TOP BAR
            ================================================== --}}

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


                {{-- =================================================
                   LEFT SIDE
                ================================================== --}}

                <div class="dashboard-topbar-left">


                    {{-- MOBILE MENU BUTTON --}}

                    <button
                        type="button"
                        class="mobile-sidebar-toggle"
                        data-mobile-sidebar-open
                        aria-expanded="false"
                        aria-label="{{ app()->getLocale() === 'fr'
                            ? 'Ouvrir le menu'
                            : 'Open menu'
                        }}"
                    >
                        ☰
                    </button>


                    <div class="dashboard-topbar-title">

                        <h4 class="mb-0">
                            @yield('page-title')
                        </h4>

                        <small class="text-muted">

                            {{ __('teacher.welcome_back') }},

                            {{ auth()->user()->name }}

                        </small>

                    </div>

                </div>



                {{-- =================================================
                   RIGHT SIDE
                ================================================== --}}

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
                            class="{{ app()->getLocale() === 'en'
                                ? 'active'
                                : ''
                            }}"
                        >
                            EN
                        </a>


                        <span>/</span>


                        <a
                            href="{{ route('language.switch', 'fr') }}"
                            class="{{ app()->getLocale() === 'fr'
                                ? 'active'
                                : ''
                            }}"
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



                    {{-- =================================================
                       AVATAR / USER MENU
                    ================================================== --}}

                    <div class="dp-user-menu">

                        <button
                            type="button"
                            class="
                                avatar
                                dp-user-menu-toggle
                            "
                            data-user-menu-toggle
                            aria-expanded="false"
                            aria-label="{{ app()->getLocale() === 'fr'
                                ? 'Ouvrir le menu du compte'
                                : 'Open account menu'
                            }}"
                        >


                            {{-- PROFILE PHOTO IF AVAILABLE --}}

                            @if(
                                auth()->user()
                                    ->teacher
                                    ?->profile_photo
                            )

                                <img
                                    src="{{ asset(
                                        'storage/' .
                                        auth()->user()
                                            ->teacher
                                            ->profile_photo
                                    ) }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="dp-user-menu-avatar-image"
                                >


                            {{-- FIRST LETTER IF NO PHOTO --}}

                            @else

                                {{ strtoupper(
                                    substr(
                                        auth()->user()->name,
                                        0,
                                        1
                                    )
                                ) }}

                            @endif

                        </button>



                        {{-- =================================================
                           ACCOUNT DROPDOWN
                        ================================================== --}}

                        <div
                            class="dp-user-menu-dropdown"
                            data-user-menu-dropdown
                        >


                            {{-- HOME --}}

                            <a
                                href="{{ route('home') }}"
                                class="dp-user-menu-home"
                            >
                                {{ app()->getLocale() === 'fr'
                                    ? 'Accueil'
                                    : 'Home'
                                }}
                            </a>



                            {{-- SIGN OUT --}}

                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                                class="dp-user-menu-form"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="dp-user-menu-logout"
                                >
                                    {{ app()->getLocale() === 'fr'
                                        ? 'Déconnexion'
                                        : 'Sign out'
                                    }}
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
               PAGE CONTENT
            ================================================== --}}

            <main class="p-4">

                @yield('content')

            </main>


        </div>

    </div>

</div>



{{-- =================================================
   PLATFORM MESSAGE WIDGET
================================================== --}}

@if(
    (int) \App\Models\Setting::getValue(
        'show_platform_message_widget',
        1
    ) === 1
)

    @include('partials.platform-message-widget')

@endif



{{-- =================================================
   AVATAR DROPDOWN SCRIPT
================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const userMenus =
            document.querySelectorAll(
                '.dp-user-menu'
            );


        userMenus.forEach(
            function (userMenu) {

                const toggle =
                    userMenu.querySelector(
                        '[data-user-menu-toggle]'
                    );


                const dropdown =
                    userMenu.querySelector(
                        '[data-user-menu-dropdown]'
                    );


                if (
                    !toggle
                    ||
                    !dropdown
                ) {

                    return;
                }


                const closeMenu =
                    function () {

                        userMenu
                            .classList
                            .remove(
                                'is-open'
                            );


                        toggle.setAttribute(
                            'aria-expanded',
                            'false'
                        );
                    };


                const openMenu =
                    function () {

                        document
                            .querySelectorAll(
                                '.dp-user-menu.is-open'
                            )
                            .forEach(
                                function (
                                    otherMenu
                                ) {

                                    if (
                                        otherMenu
                                        !==
                                        userMenu
                                    ) {

                                        otherMenu
                                            .classList
                                            .remove(
                                                'is-open'
                                            );


                                        const otherToggle =
                                            otherMenu
                                                .querySelector(
                                                    '[data-user-menu-toggle]'
                                                );


                                        if (otherToggle) {

                                            otherToggle
                                                .setAttribute(
                                                    'aria-expanded',
                                                    'false'
                                                );
                                        }
                                    }
                                }
                            );


                        userMenu
                            .classList
                            .add(
                                'is-open'
                            );


                        toggle.setAttribute(
                            'aria-expanded',
                            'true'
                        );
                    };


                toggle.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();

                        event.stopPropagation();


                        const isOpen =
                            userMenu
                                .classList
                                .contains(
                                    'is-open'
                                );


                        if (isOpen) {

                            closeMenu();

                        } else {

                            openMenu();
                        }
                    }
                );


                dropdown.addEventListener(
                    'click',
                    function (event) {

                        event.stopPropagation();
                    }
                );

            }
        );


        document.addEventListener(
            'click',
            function () {

                document
                    .querySelectorAll(
                        '.dp-user-menu.is-open'
                    )
                    .forEach(
                        function (userMenu) {

                            userMenu
                                .classList
                                .remove(
                                    'is-open'
                                );


                            const toggle =
                                userMenu
                                    .querySelector(
                                        '[data-user-menu-toggle]'
                                    );


                            if (toggle) {

                                toggle
                                    .setAttribute(
                                        'aria-expanded',
                                        'false'
                                    );
                            }
                        }
                    );
            }
        );


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key
                    !==
                    'Escape'
                ) {

                    return;
                }


                document
                    .querySelectorAll(
                        '.dp-user-menu.is-open'
                    )
                    .forEach(
                        function (userMenu) {

                            userMenu
                                .classList
                                .remove(
                                    'is-open'
                                );


                            const toggle =
                                userMenu
                                    .querySelector(
                                        '[data-user-menu-toggle]'
                                    );


                            if (toggle) {

                                toggle
                                    .setAttribute(
                                        'aria-expanded',
                                        'false'
                                    );
                            }
                        }
                    );
            }
        );

    }
);

</script>


</body>

</html>