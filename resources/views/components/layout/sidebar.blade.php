@props([
    'role' => 'teacher'
])

@php

    $menus = [

        'teacher' => [
            [
                'label' => 'Dashboard',
                'route' => 'teacher.dashboard',
            ],
            [
                'label' => 'My Profile',
                'route' => 'teacher.profile.edit',
            ],
            [
                'label' => 'Bookings',
                'route' => 'teacher.bookings',
            ],
            [
                'label' => 'Availability',
                'route' => 'teacher.availability',
            ],
            [
                'label' => 'Reviews',
                'route' => 'teacher.reviews',
            ],
            [
                'label' => 'Earnings',
                'route' => 'teacher.earnings',
            ],
        ],

        'student' => [
            [
                'label' => 'Dashboard',
                'route' => 'student.dashboard',
            ],
            [
                'label' => 'Find Teachers',
                'route' => 'student.teachers',
            ],
        ],

        'admin' => [
            [
                'label' => 'Dashboard',
                'route' => 'admin.dashboard',
            ],
        ],

    ];

    $items = $menus[$role] ?? [];

@endphp


<aside class="dw-sidebar dw-sidebar-{{ $role }}">

    <div class="dw-sidebar-logo">

        <x-ui.logo variant="light" />

        <small class="dw-sidebar-role">
            {{ ucfirst($role) }} Panel
        </small>

    </div>


    <nav class="dw-sidebar-nav">

        @foreach($items as $item)

            @php
                $isActive = request()->routeIs($item['route']);
            @endphp

            <a
                href="{{ route($item['route']) }}"
                class="dw-sidebar-link {{ $isActive ? 'active' : '' }}"
            >

                <span>
                    {{ $item['label'] }}
                </span>

            </a>

        @endforeach

    </nav>


    <div class="dw-sidebar-bottom">

        <a href="/" class="dw-sidebar-link">
            Home
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="dw-sidebar-logout"
            >
                Logout
            </button>

        </form>

    </div>

</aside>