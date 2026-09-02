<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Admin Panel') | DancePair
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>

        body {
            background: #F5F8FA;
        }

        .sidebar {
            min-height: 100vh;

            background: #111827;

            color: #FFFFFF;
        }

        .sidebar a {
            display: block;

            padding: 12px 14px;

            margin-bottom: 6px;

            border-radius: 10px;

            color: #D1D5DB;

            text-decoration: none;

            transition: .18s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #1F2937;
            color: #FFFFFF;
        }

        .topbar {
            background: #FFFFFF;

            border-bottom: 1px solid #E5E7EB;
        }

        .admin-avatar {
            width: 52px;
            height: 52px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #E0F2FE;

            color: #0369A1;

            font-weight: 800;
            font-size: 19px;
        }

        .main-content {
            min-height: 100vh;
        }

    </style>

</head>


<body class="admin-panel">


<div class="container-fluid">

    <div class="row">


        {{-- =================================================
           SIDEBAR
        ================================================== --}}

        <div class="col-md-3 col-lg-2 sidebar p-4">


            <x-ui.logo />


            <div class="mt-4">


                <a
                    href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard')
                        ? 'active'
                        : ''
                    }}"
                >
                    Dashboard
                </a>


                <a
                    href="{{ route('admin.teachers') }}"
                    class="{{ request()->routeIs('admin.teachers*')
                        ? 'active'
                        : ''
                    }}"
                >
                    Teachers
                </a>


                <a
                    href="{{ route('admin.students') }}"
                    class="{{ request()->routeIs('admin.students*')
                        ? 'active'
                        : ''
                    }}"
                >
                    Students
                </a>


                <a
    href="{{ route('admin.bookings') }}"
    class="{{ request()->routeIs('admin.bookings*')
        ? 'active'
        : ''
    }}"
>
    Bookings
</a>


<a href="{{ route('admin.reviews') }}"
   class="{{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
    Reviews
</a>

<a
    href="{{ route('admin.payments') }}"
    class="{{ request()->routeIs('admin.payments*') ? 'active' : '' }}"
>
    Payments
</a>
<a
    href="{{ route('admin.platform-messages') }}"
    class="dw-sidebar-link
        {{ request()->routeIs('admin.platform-messages*') ? 'active' : '' }}"
>
    Messages
</a>

                <a
                    href="{{ route('admin.settings') }}"
                    class="{{ request()->routeIs('admin.settings*')
                        ? 'active'
                        : ''
                    }}"
                >
                    Settings
                </a>

            </div>


            <hr class="sidebar-separator">


            <a href="/">
                Home
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
                    Logout
                </button>

            </form>

        </div>



        {{-- =================================================
           MAIN
        ================================================== --}}

        <div
            class="
                col-md-9
                col-lg-10
                p-0
                main-content
            "
        >


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

                        DancePair Administration

                        • Welcome back,
                        {{ auth()->user()->name }}

                    </small>

                </div>


                <div class="admin-avatar">

                    {{ strtoupper(
                        substr(
                            auth()->user()->name,
                            0,
                            1
                        )
                    ) }}

                </div>

            </div>


            <main class="p-4">

                @yield('content')

            </main>

        </div>

    </div>

</div>


</body>

</html>