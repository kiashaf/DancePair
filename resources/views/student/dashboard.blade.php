@extends('student.layout')

@section('title', __('student.dashboard'))
@section('page-title', __('student.dashboard'))

@section('content')

<style>

.student-dashboard {
    display: flex;
    flex-direction: column;
    gap: 22px;
}


/* =========================================================
   OVERVIEW CARDS
========================================================= */

.dashboard-overview {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}

.dashboard-stat {
    position: relative;
    overflow: hidden;

    min-height: 155px;

    padding: 22px;

    border-radius: 22px;
    border: 1px solid #CDE9F8;

    background:
        linear-gradient(
            145deg,
            #FFFFFF 0%,
            #F2FAFF 100%
        );

    box-shadow:
        0 8px 24px rgba(2, 132, 199, .06);

    cursor: pointer;

    transition:
        transform .18s ease,
        box-shadow .18s ease,
        border-color .18s ease;
}

.dashboard-stat:hover {
    transform: translateY(-3px);

    border-color: #85CBEA;

    box-shadow:
        0 12px 28px rgba(2, 132, 199, .11);
}

.dashboard-stat.active {
    border-color: #0284C7;

    box-shadow:
        0 0 0 3px rgba(2,132,199,.10);
}

.dashboard-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.dashboard-stat-label {
    font-size: 12px;
    font-weight: 700;

    color: #64748B;

    text-transform: uppercase;
    letter-spacing: .4px;
}

.dashboard-stat-icon {
    width: 44px;
    height: 44px;

    border-radius: 14px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #E8F6FE;

    font-size: 21px;
}

.dashboard-stat-number {
    margin-top: 18px;

    font-size: 36px;
    line-height: 1;

    font-weight: 800;

    color: #111827;
}

.dashboard-stat-footer {
    margin-top: 13px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    color: #0284C7;

    font-size: 11px;
    font-weight: 700;
}


/* =========================================================
   NOTIFICATIONS
========================================================= */

.dashboard-notifications {
    background:
        linear-gradient(
            145deg,
            #FFF9D9 0%,
            #FFF4B8 100%
        );

    border: 1px solid #F2D675;
    border-radius: 22px;

    padding: 24px;

    box-shadow:
        0 8px 24px rgba(202, 138, 4, 0.08);
}

.dashboard-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    gap: 15px;

    margin-bottom: 18px;
}

.dashboard-section-title {
    margin: 0;

    font-size: 22px;
    font-weight: 800;

    color: #111827;
}

.dashboard-section-subtitle {
    margin-top: 3px;

    color: #64748B;

    font-size: 11px;
}

.dashboard-notification-count {
    min-width: 30px;
    height: 30px;

    padding: 0 8px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 999px;

    background: #0284C7;
    color: #FFFFFF;

    font-size: 11px;
    font-weight: 700;
}

.dashboard-notification-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.dashboard-notification-item {
    display: flex;
    gap: 13px;

    padding: 14px 15px;

    border-radius: 15px;
    border: 1px solid #D6EAF5;

    background: rgba(255,255,255,.74);

    text-decoration: none;
    color: inherit;

    transition: .15s ease;
}

.dashboard-notification-item:hover {
    background: #FFFFFF;
}

.dashboard-notification-item.unread {
    background: #FFFFFF;
    border-left: 4px solid #0284C7;
}

.dashboard-notification-icon {
    width: 40px;
    height: 40px;

    flex: 0 0 40px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    font-size: 17px;
    font-weight: 800;
}

.dashboard-notification-icon.accepted {
    background: #D1FAE5;
    color: #047857;
}

.dashboard-notification-icon.rejected {
    background: #FEE2E2;
    color: #B91C1C;
}

.dashboard-notification-icon.payment {
    background: #DBEAFE;
    color: #1D4ED8;
}

.dashboard-notification-icon.password {
    background: #EDE9FE;
    color: #7C3AED;
}

.dashboard-notification-icon.booking {
    background: #FEF3C7;
    color: #B45309;
}

.dashboard-notification-icon.default {
    background: #F1F5F9;
    color: #475569;
}

.dashboard-notification-icon svg {
    width: 20px;
    height: 20px;
    display: block;
}

.dashboard-notification-icon.default {
    background: #F1F5F9;
    color: #475569;
}

.dashboard-notification-content {
    min-width: 0;
    flex: 1;
}

.dashboard-notification-title {
    font-size: 13px;
    font-weight: 700;

    color: #111827;
}

.dashboard-notification-message {
    margin-top: 3px;

    font-size: 11px;
    line-height: 1.5;

    color: #64748B;
}

.dashboard-notification-time {
    margin-top: 6px;

    font-size: 9px;

    color: #94A3B8;
}

.dashboard-notification-action {
    display: inline-block;

    margin-top: 8px;

    padding: 5px 9px;

    border-radius: 8px;

    background: #0284C7;
    color: #FFFFFF;

    font-size: 10px;
    font-weight: 700;
}

.dashboard-empty {
    padding: 36px 20px;

    text-align: center;

    border-radius: 14px;
    border: 1px dashed #BCDCEC;

    color: #94A3B8;

    background: rgba(255,255,255,.55);
}


/* =========================================================
   DETAILS DRAWER
========================================================= */

.dashboard-detail-panel {
    display: none;

    background: #FFFFFF;

    border: 1px solid #CDE9F8;
    border-radius: 22px;

    padding: 24px;

    box-shadow:
        0 8px 24px rgba(15,23,42,.04);
}

.dashboard-detail-panel.active {
    display: block;
}

.dashboard-detail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 18px;
}

.dashboard-detail-title {
    margin: 0;

    font-size: 21px;
    font-weight: 800;

    color: #111827;
}

.dashboard-close-btn {
    border: 0;

    width: 34px;
    height: 34px;

    border-radius: 50%;

    background: #F1F5F9;
    color: #64748B;

    cursor: pointer;

    font-size: 17px;
}

.dashboard-close-btn:hover {
    background: #E2E8F0;
}


/* =========================================================
   LESSON ROWS
========================================================= */

.dashboard-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.dashboard-list-item {
    display: grid;

    grid-template-columns:
        1.1fr
        1fr
        1.2fr
        .9fr
        .8fr
        auto;

    gap: 14px;

    align-items: center;

    padding: 14px 16px;

    border: 1px solid #E1EDF4;
    border-radius: 14px;

    background: #FAFDFF;
}

.dashboard-list-label {
    display: block;

    margin-bottom: 3px;

    font-size: 8px;
    font-weight: 700;

    color: #94A3B8;

    text-transform: uppercase;
    letter-spacing: .4px;
}

.dashboard-list-value {
    font-size: 12px;
    font-weight: 650;

    color: #1F2937;
}

.dashboard-status {
    display: inline-block;

    padding: 5px 9px;

    border-radius: 999px;

    font-size: 9px;
    font-weight: 700;
}

.dashboard-status.pending {
    background: #FEF3C7;
    color: #92400E;
}

.dashboard-status.confirmed {
    background: #D1FAE5;
    color: #047857;
}

.dashboard-status.completed {
    background: #DBEAFE;
    color: #1D4ED8;
}

.dashboard-action-btn {
    display: inline-block;

    padding: 7px 11px;

    border-radius: 9px;

    background: #0284C7;
    color: #FFFFFF;

    text-decoration: none;

    font-size: 10px;
    font-weight: 700;

    white-space: nowrap;
}

.dashboard-action-btn:hover {
    background: #0369A1;
    color: #FFFFFF;
}

.dashboard-action-secondary {
    background: #F1F5F9;
    color: #475569;
}

.dashboard-action-secondary:hover {
    background: #E2E8F0;
    color: #1F2937;
}


/* =========================================================
   PAYMENT AMOUNT
========================================================= */

.dashboard-price {
    color: #0369A1;

    font-weight: 800;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1000px) {

    .dashboard-overview {
        grid-template-columns: 1fr;
    }

    .dashboard-list-item {
        grid-template-columns: 1fr 1fr;
    }
}

@media(max-width: 650px) {

    .dashboard-list-item {
        grid-template-columns: 1fr;
    }
}

</style>



<div class="student-dashboard">


    {{-- =====================================================
       CLICKABLE STATS
    ====================================================== --}}

    <div class="dashboard-overview">


        {{-- UPCOMING --}}
        <div
            class="dashboard-stat"
            data-panel="upcoming-panel"
        >

            <div class="dashboard-stat-top">

                <div class="dashboard-stat-label">
                    {{ __('student.upcoming_bookings') }}
                </div>

                <div class="dashboard-stat-icon">
                    📅
                </div>

            </div>

            <div class="dashboard-stat-number">
                {{ $upcomingBookings }}
            </div>

            <div class="dashboard-stat-footer">

                <span>
                    {{ __('student.view_upcoming_lessons') }}
                </span>

                <span>
                    →
                </span>

            </div>

        </div>


        {{-- COMPLETED --}}
        <div
            class="dashboard-stat"
            data-panel="completed-panel"
        >

            <div class="dashboard-stat-top">

                <div class="dashboard-stat-label">
                    {{ __('student.completed_lessons') }}
                </div>

                <div class="dashboard-stat-icon">
                    ✓
                </div>

            </div>

            <div class="dashboard-stat-number">
                {{ $completedLessons }}
            </div>

            <div class="dashboard-stat-footer">

                <span>
                    {{ __('student.view_completed_lessons') }}
                </span>

                <span>
                    →
                </span>

            </div>

        </div>


        {{-- PAYMENT REQUIRED --}}
        <div
            class="dashboard-stat"
            data-panel="payment-panel"
        >

            <div class="dashboard-stat-top">

                <div class="dashboard-stat-label">
                    {{ __('student.payments_required') }}
                </div>

                <div class="dashboard-stat-icon">
                    $
                </div>

            </div>

            <div class="dashboard-stat-number">
                {{ $pendingPayments }}
            </div>

            <div class="dashboard-stat-footer">

                <span>
                    {{ __('student.view_payments_due') }}
                </span>

                <span>
                    →
                </span>

            </div>

        </div>

    </div>



    {{-- =====================================================
       NOTIFICATIONS
    ====================================================== --}}

    <div class="dashboard-notifications">

        <div class="dashboard-section-header">

            <div>

                <h3 class="dashboard-section-title">
                    {{ __('student.notifications') }}
                </h3>

                <div class="dashboard-section-subtitle">
                    {{ __('student.notification_subtitle') }}
                </div>

            </div>


            @if($unreadNotificationCount > 0)

                <div class="dashboard-notification-count">
                    {{ $unreadNotificationCount }}
                </div>

            @endif

        </div>


        @if($notifications->count())

            <div class="dashboard-notification-list">

                @foreach($notifications as $notification)

                    @php

                        $type =
                            $notification->data['type']
                            ?? 'default';

                        $title =
                            $notification->data['title']
                            ?? __('student.notification');

                        $message =
                            $notification->data['message']
                            ?? '';

                        $url =
                            $notification->data['url']
                            ?? '#';

                    @endphp


                    <a
                        href="{{ $url }}"
                        class="
                            dashboard-notification-item
                            {{ is_null($notification->read_at)
                                ? 'unread'
                                : ''
                            }}
                        "
                    >

                        <div
                            class="
                                dashboard-notification-icon

                                @if($type === 'booking_accepted')
                                    accepted

                                @elseif($type === 'booking_rejected')
                                    rejected

                                @elseif($type === 'payment_confirmed')
                                    payment

                                @elseif($type === 'password_changed')
                                    password

                                @else
                                    default
                                @endif
                            "
                        >

                            @if($type === 'password_changed')

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <rect
                                        x="5"
                                        y="10"
                                        width="14"
                                        height="11"
                                        rx="2"
                                    ></rect>

                                    <path
                                        d="M8 10V7a4 4 0 0 1 8 0v3"
                                    ></path>

                                    <circle
                                        cx="12"
                                        cy="15.5"
                                        r="1"
                                    ></circle>
                                </svg>


                            @elseif($type === 'booking_accepted')

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                    ></circle>

                                    <path
                                        d="m8 12 2.5 2.5L16 9"
                                    ></path>
                                </svg>


                            @elseif($type === 'booking_rejected')

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                    ></circle>

                                    <path
                                        d="m9 9 6 6"
                                    ></path>

                                    <path
                                        d="m15 9-6 6"
                                    ></path>
                                </svg>


                            @elseif($type === 'payment_confirmed')

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <rect
                                        x="3"
                                        y="5"
                                        width="18"
                                        height="14"
                                        rx="2"
                                    ></rect>

                                    <path
                                        d="M3 10h18"
                                    ></path>

                                    <path
                                        d="M7 15h3"
                                    ></path>
                                </svg>


                            @else

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"
                                    ></path>

                                    <path
                                        d="M10 21h4"
                                    ></path>
                                </svg>

                            @endif

                        </div>


                        <div class="dashboard-notification-content">

                            <div class="dashboard-notification-title">
                                {{ $title }}
                            </div>

                            @if($message)

                                <div class="dashboard-notification-message">
                                    {{ $message }}
                                </div>

                            @endif


                            <div class="dashboard-notification-time">

                                {{ $notification
                                    ->created_at
                                    ->copy()
                                    ->locale(app()->getLocale())
                                    ->translatedFormat(
                                        app()->getLocale() === 'fr'
                                            ? 'd M Y • H:i'
                                            : 'M d, Y • g:i A'
                                    )
                                }}

                            </div>


                            @if($type === 'booking_accepted')

                                <span class="dashboard-notification-action">
                                    {{ __('student.view_booking_pay') }}
                                </span>

                            @elseif($type === 'payment_confirmed')

                                <span class="dashboard-notification-action">
                                    {{ __('student.view_payment') }}
                                </span>

                            @endif

                        </div>

                    </a>

                @endforeach

            </div>


        @else

            <div class="dashboard-empty">

                <div style="font-size:26px;margin-bottom:7px;">
                    🔔
                </div>

                {{ __('student.no_notifications') }}

            </div>

        @endif

    </div>



    {{-- =====================================================
       UPCOMING PANEL
    ====================================================== --}}

    <div
        id="upcoming-panel"
        class="dashboard-detail-panel"
    >

        <div class="dashboard-detail-header">

            <div>

                <h3 class="dashboard-detail-title">
                    {{ __('student.upcoming_bookings') }}
                </h3>

                <div class="dashboard-section-subtitle">
                    {{ __('student.upcoming_subtitle') }}
                </div>

            </div>

            <button
                type="button"
                class="dashboard-close-btn"
            >
                ×
            </button>

        </div>


        @if($upcomingBookingList->count())

            <div class="dashboard-list">

                @foreach($upcomingBookingList as $booking)

                    @php

                        $start =
                            \Carbon\Carbon::parse(
                                $booking->lesson_time
                            );

                        $end =
                            $start
                                ->copy()
                                ->addMinutes(
                                    $booking->duration ?? 60
                                );

                    @endphp


                    <div class="dashboard-list-item">

                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.dance') }}
                            </span>

                            <div class="dashboard-list-value">
                                {{ $booking->danceStyle->name ?? __('student.dance') }}
                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.teacher') }}
                            </span>

                            <div class="dashboard-list-value">

                                {{ $booking
                                    ->teacher
                                    ?->user
                                    ?->name
                                    ?? __('student.teacher')
                                }}

                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.date') }}
                            </span>

                            <div class="dashboard-list-value">

                                {{ \Carbon\Carbon::parse(
                                    $booking->lesson_date
                                )
                                ->locale(app()->getLocale())
                                ->translatedFormat(
                                    app()->getLocale() === 'fr'
                                        ? 'd M Y'
                                        : 'M d, Y'
                                ) }}

                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.time') }}
                            </span>

                            <div class="dashboard-list-value">

                                @if(app()->getLocale() === 'fr')

                                    {{ $start->format('H:i') }}
                                    -
                                    {{ $end->format('H:i') }}

                                @else

                                    {{ $start->format('g:i A') }}
                                    -
                                    {{ $end->format('g:i A') }}

                                @endif

                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.status') }}
                            </span>

                            <span
                                class="
                                    dashboard-status
                                    {{ $booking->status }}
                                "
                            >
                                {{ __('student.' . $booking->status) }}
                            </span>

                        </div>


                        <div>

                            <a
                                href="{{ route(
                                    'student.bookings'
                                ) }}"
                                class="
                                    dashboard-action-btn
                                    dashboard-action-secondary
                                "
                            >
                                {{ __('student.view') }}
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>


        @else

            <div class="dashboard-empty">
                {{ __('student.no_upcoming_bookings') }}
            </div>

        @endif

    </div>



    {{-- =====================================================
       COMPLETED PANEL
    ====================================================== --}}

    <div
        id="completed-panel"
        class="dashboard-detail-panel"
    >

        <div class="dashboard-detail-header">

            <div>

                <h3 class="dashboard-detail-title">
                    {{ __('student.completed_lessons') }}
                </h3>

                <div class="dashboard-section-subtitle">
                    {{ __('student.completed_subtitle') }}
                </div>

            </div>

            <button
                type="button"
                class="dashboard-close-btn"
            >
                ×
            </button>

        </div>


        @if($completedLessonList->count())

            <div class="dashboard-list">

                @foreach($completedLessonList as $booking)

                    @php

                        $start =
                            \Carbon\Carbon::parse(
                                $booking->lesson_time
                            );

                        $end =
                            $start
                                ->copy()
                                ->addMinutes(
                                    $booking->duration ?? 60
                                );

                    @endphp


                    <div class="dashboard-list-item">

                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.dance') }}
                            </span>

                            <div class="dashboard-list-value">
                                {{ $booking->danceStyle->name ?? __('student.dance') }}
                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.teacher') }}
                            </span>

                            <div class="dashboard-list-value">

                                {{ $booking
                                    ->teacher
                                    ?->user
                                    ?->name
                                    ?? __('student.teacher')
                                }}

                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.date') }}
                            </span>

                            <div class="dashboard-list-value">

                                {{ \Carbon\Carbon::parse(
                                    $booking->lesson_date
                                )
                                ->locale(app()->getLocale())
                                ->translatedFormat(
                                    app()->getLocale() === 'fr'
                                        ? 'd M Y'
                                        : 'M d, Y'
                                ) }}

                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.time') }}
                            </span>

                            <div class="dashboard-list-value">

                                @if(app()->getLocale() === 'fr')

                                    {{ $start->format('H:i') }}
                                    -
                                    {{ $end->format('H:i') }}

                                @else

                                    {{ $start->format('g:i A') }}
                                    -
                                    {{ $end->format('g:i A') }}

                                @endif

                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.status') }}
                            </span>

                            <span class="dashboard-status completed">
                                {{ __('student.completed') }}
                            </span>

                        </div>


                        <div>

                            <a
                                href="{{ route(
                                    'student.reviews'
                                ) }}"
                                class="
                                    dashboard-action-btn
                                    dashboard-action-secondary
                                "
                            >
                                {{ __('student.reviews') }}
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>


        @else

            <div class="dashboard-empty">
                {{ __('student.no_completed_lessons') }}
            </div>

        @endif

    </div>



    {{-- =====================================================
       PAYMENT REQUIRED PANEL
    ====================================================== --}}

    <div
        id="payment-panel"
        class="dashboard-detail-panel"
    >

        <div class="dashboard-detail-header">

            <div>

                <h3 class="dashboard-detail-title">
                    {{ __('student.payments_required') }}
                </h3>

                <div class="dashboard-section-subtitle">
                    {{ __('student.confirmed_lessons_waiting_payment') }}
                </div>

            </div>

            <button
                type="button"
                class="dashboard-close-btn"
            >
                ×
            </button>

        </div>


        @if($paymentRequiredList->count())

            <div class="dashboard-list">

                @foreach($paymentRequiredList as $booking)

                    @php

                        $start =
                            \Carbon\Carbon::parse(
                                $booking->lesson_time
                            );

                        $end =
                            $start
                                ->copy()
                                ->addMinutes(
                                    $booking->duration ?? 60
                                );

                    @endphp


                    <div class="dashboard-list-item">

                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.dance') }}
                            </span>

                            <div class="dashboard-list-value">
                                {{ $booking->danceStyle->name ?? __('student.dance') }}
                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.teacher') }}
                            </span>

                            <div class="dashboard-list-value">

                                {{ $booking
                                    ->teacher
                                    ?->user
                                    ?->name
                                    ?? __('student.teacher')
                                }}

                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.date') }}
                            </span>

                            <div class="dashboard-list-value">

                                {{ \Carbon\Carbon::parse(
                                    $booking->lesson_date
                                )
                                ->locale(app()->getLocale())
                                ->translatedFormat(
                                    app()->getLocale() === 'fr'
                                        ? 'd M Y'
                                        : 'M d, Y'
                                ) }}

                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.time') }}
                            </span>

                            <div class="dashboard-list-value">

                                @if(app()->getLocale() === 'fr')

                                    {{ $start->format('H:i') }}
                                    -
                                    {{ $end->format('H:i') }}

                                @else

                                    {{ $start->format('g:i A') }}
                                    -
                                    {{ $end->format('g:i A') }}

                                @endif

                            </div>

                        </div>


                        <div>

                            <span class="dashboard-list-label">
                                {{ __('student.amount') }}
                            </span>

                            <div
                                class="
                                    dashboard-list-value
                                    dashboard-price
                                "
                            >
                                ${{ number_format(
                                    (float) $booking->price,
                                    2
                                ) }}
                            </div>

                        </div>


                        <div>

                            <a
                                href="{{ route(
                                    'student.payments.show',
                                    $booking
                                ) }}"
                                class="dashboard-action-btn"
                            >
                                {{ __('student.pay_now') }}
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>


        @else

            <div class="dashboard-empty">
                {{ __('student.no_payments_required') }}
            </div>

        @endif

    </div>

</div>



<script>

document.addEventListener('DOMContentLoaded', function () {

    const cards =
        document.querySelectorAll('.dashboard-stat');

    const panels =
        document.querySelectorAll('.dashboard-detail-panel');

    const closeButtons =
        document.querySelectorAll('.dashboard-close-btn');


    cards.forEach(function (card) {

        card.addEventListener('click', function () {

            const targetId =
                card.dataset.panel;

            const targetPanel =
                document.getElementById(targetId);

            const wasOpen =
                targetPanel.classList.contains('active');


            /*
            |--------------------------------------------------------------------------
            | CLOSE EVERYTHING FIRST
            |--------------------------------------------------------------------------
            */

            panels.forEach(function (panel) {
                panel.classList.remove('active');
            });

            cards.forEach(function (item) {
                item.classList.remove('active');
            });


            /*
            |--------------------------------------------------------------------------
            | OPEN SELECTED PANEL
            |--------------------------------------------------------------------------
            */

            if (!wasOpen) {

                targetPanel.classList.add('active');

                card.classList.add('active');


                setTimeout(function () {

                    targetPanel.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                }, 50);
            }

        });

    });


    closeButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            panels.forEach(function (panel) {
                panel.classList.remove('active');
            });

            cards.forEach(function (card) {
                card.classList.remove('active');
            });

        });

    });

});

</script>

@endsection