<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}">

    <link rel="shortcut icon" type="image/png" href="{{ asset('logo/logo.png') }}">

    <link rel="apple-touch-icon" href="{{ asset('logo/logo.png') }}">


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
           MOBILE SIDEBAR OVERLAY
        ================================================== --}}

        <div
            class="mobile-sidebar-overlay"
            data-mobile-sidebar-overlay
        ></div>



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
                            {{ __('student.welcome_back') }},
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



                    {{-- =================================================
                       AVATAR / USER MENU
                    ================================================== --}}

                    <div
                        class="dp-user-menu"
                        style="
                            position:relative;
                            flex-shrink:0;
                        "
                    >

                        <button
                            type="button"
                            class="avatar"
                            data-user-menu-toggle
                            aria-expanded="false"
                            aria-label="{{ app()->getLocale() === 'fr'
                                ? 'Ouvrir le menu du compte'
                                : 'Open account menu'
                            }}"
                            style="
                                border:0;
                                padding:0;
                                margin:0;
                                cursor:pointer;
                                overflow:hidden;
                            "
                        >

                            @if(auth()->user()->student?->profile_photo)

                                <img
                                    src="{{ asset(
                                        'storage/' .
                                        auth()->user()->student->profile_photo
                                    ) }}"
                                    alt="{{ auth()->user()->name }}"
                                    style="
                                        width:100%;
                                        height:100%;
                                        object-fit:cover;
                                        border-radius:50%;
                                        display:block;
                                    "
                                >

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



                        {{-- DROPDOWN --}}

                        <div
                            data-user-menu-dropdown
                            style="
                                position:absolute;

                                top:calc(100% + 10px);
                                right:0;

                                width:180px;

                                display:none;

                                padding:8px;

                                border:1px solid #E5E7EB;
                                border-radius:12px;

                                background:#FFFFFF;

                                box-shadow:
                                    0 12px 30px
                                    rgba(15,23,42,.14);

                                z-index:99999;
                            "
                        >


                            {{-- HOME --}}

                            <a
                                href="{{ route('home') }}"
                                style="
                                    display:block;

                                    width:100%;

                                    padding:10px 12px;

                                    border-radius:8px;

                                    color:#334155;

                                    font-size:12px;
                                    font-weight:700;

                                    text-decoration:none;
                                "
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
                                style="margin:0;"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    style="
                                        display:block;

                                        width:100%;

                                        padding:10px 12px;

                                        border:0;
                                        border-radius:8px;

                                        background:transparent;

                                        color:#DC2626;

                                        font-size:12px;
                                        font-weight:700;

                                        text-align:left;

                                        cursor:pointer;
                                    "
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

        const userMenu =
            document.querySelector(
                '.dp-user-menu'
            );


        if (!userMenu) {

            return;
        }


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

                dropdown.style.display =
                    'none';


                toggle.setAttribute(
                    'aria-expanded',
                    'false'
                );
            };


        toggle.addEventListener(
            'click',
            function (event) {

                event.stopPropagation();


                const isOpen =
                    dropdown.style.display
                    ===
                    'block';


                if (isOpen) {

                    closeMenu();

                    return;
                }


                dropdown.style.display =
                    'block';


                toggle.setAttribute(
                    'aria-expanded',
                    'true'
                );
            }
        );


        dropdown.addEventListener(
            'click',
            function (event) {

                event.stopPropagation();
            }
        );


        document.addEventListener(
            'click',
            closeMenu
        );


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key
                    ===
                    'Escape'
                ) {

                    closeMenu();
                }
            }
        );
    }
);
</script>


</body>

</html>