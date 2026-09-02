@extends('teacher.layout')

@section('title', __('teacher.dashboard'))
@section('page-title', __('teacher.dashboard'))

@section('content')

<style>

.teacher-dashboard {
    display: flex;
    flex-direction: column;
    gap: 22px;
}


/* =========================================================
   TOP CARDS
========================================================= */

.teacher-overview {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}

.teacher-stat {
    position: relative;
    overflow: hidden;

    min-height: 155px;

    padding: 22px;

    border-radius: 22px;
    border: 1px solid #DDD6FE;

    background:
        linear-gradient(
            145deg,
            #FFFFFF 0%,
            #F8F6FF 100%
        );

    box-shadow:
        0 8px 24px rgba(91, 33, 182, .06);

    cursor: pointer;

    transition:
        transform .18s ease,
        box-shadow .18s ease,
        border-color .18s ease;
}

.teacher-stat:hover {
    transform: translateY(-3px);

    border-color: #B8A7EE;

    box-shadow:
        0 12px 28px rgba(91, 33, 182, .11);
}

.teacher-stat.active {
    border-color: #7C3AED;

    box-shadow:
        0 0 0 3px rgba(124,58,237,.10);
}

.teacher-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.teacher-stat-label {
    font-size: 12px;
    font-weight: 700;

    color: #64748B;

    text-transform: uppercase;
    letter-spacing: .4px;
}

.teacher-stat-icon {
    width: 44px;
    height: 44px;

    border-radius: 14px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #F1ECFF;

    font-size: 21px;
}

.teacher-stat-number {
    margin-top: 18px;

    font-size: 36px;
    line-height: 1;

    font-weight: 800;

    color: #111827;
}

.teacher-stat-footer {
    margin-top: 13px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    color: #7C3AED;

    font-size: 11px;
    font-weight: 700;
}


/* =========================================================
   NOTIFICATIONS
========================================================= */

.teacher-notifications {
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
        0 8px 24px rgba(202, 138, 4, .08);
}

.teacher-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    gap: 15px;

    margin-bottom: 18px;
}

.teacher-section-title {
    margin: 0;

    font-size: 22px;
    font-weight: 800;

    color: #111827;
}

.teacher-section-subtitle {
    margin-top: 3px;

    color: #7C6422;

    font-size: 11px;
}

.teacher-notification-count {
    min-width: 30px;
    height: 30px;

    padding: 0 8px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 999px;

    background: #EAB308;
    color: #FFFFFF;

    font-size: 11px;
    font-weight: 800;
}

.teacher-notification-list {
    display: flex;
    flex-direction: column;

    gap: 10px;
}

.teacher-notification-item {
    display: flex;

    gap: 13px;

    padding: 14px 15px;

    border-radius: 15px;
    border: 1px solid #F1DEA0;

    background: rgba(255, 255, 255, .88);

    text-decoration: none;

    color: inherit;

    transition:
        background .15s ease,
        border-color .15s ease,
        transform .15s ease,
        box-shadow .15s ease;
}

.teacher-notification-item:hover {
    background: #FFFFFF;

    border-color: #E9C44A;

    transform: translateY(-1px);

    box-shadow:
        0 6px 16px rgba(202, 138, 4, .08);
}

.teacher-notification-item.unread {
    background: #FFFFFF;

    border-left: 4px solid #EAB308;
}


/* =========================================================
   NOTIFICATION ICON
========================================================= */

.teacher-notification-icon {
    width: 42px;
    height: 42px;

    flex: 0 0 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 13px;

    font-size: 17px;
    font-weight: 800;
}

.teacher-notification-icon svg {
    width: 20px;
    height: 20px;

    display: block;
}

.teacher-notification-icon.request {
    background: #FEF3C7;
    color: #B45309;
}

.teacher-notification-icon.payment {
    background: #D1FAE5;
    color: #047857;
}

.teacher-notification-icon.password {
    background: #EDE9FE;
    color: #7C3AED;
}

.teacher-notification-icon.default {
    background: #F1F5F9;
    color: #475569;
}


/* =========================================================
   NOTIFICATION CONTENT
========================================================= */

.teacher-notification-content {
    min-width: 0;

    flex: 1;
}

.teacher-notification-title {
    font-size: 13px;
    font-weight: 750;

    color: #111827;
}

.teacher-notification-message {
    margin-top: 3px;

    font-size: 11px;
    line-height: 1.5;

    color: #64748B;
}

.teacher-notification-time {
    margin-top: 6px;

    font-size: 9px;

    color: #A38A42;
}

.teacher-notification-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    margin-top: 8px;

    padding: 6px 10px;

    border-radius: 8px;

    background: #EAB308;
    color: #FFFFFF;

    font-size: 9.5px;
    font-weight: 750;
}

.teacher-notification-item:hover
.teacher-notification-action {
    background: #CA8A04;
}


/* =========================================================
   DETAIL PANELS
========================================================= */

.teacher-detail-panel {
    display: none;

    background: #FFFFFF;

    border: 1px solid #DDD6FE;
    border-radius: 22px;

    padding: 24px;

    box-shadow:
        0 8px 24px rgba(15,23,42,.04);
}

.teacher-detail-panel.active {
    display: block;
}

.teacher-detail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 18px;
}

.teacher-detail-title {
    margin: 0;

    font-size: 21px;
    font-weight: 800;

    color: #111827;
}

.teacher-close-btn {
    border: 0;

    width: 34px;
    height: 34px;

    border-radius: 50%;

    background: #F1F5F9;
    color: #64748B;

    cursor: pointer;

    font-size: 17px;
}


/* =========================================================
   LIST ROW
========================================================= */

.teacher-dashboard-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.teacher-dashboard-row {
    display: grid;

    grid-template-columns:
        1fr
        1fr
        1.1fr
        1.1fr
        .8fr
        auto;

    gap: 14px;

    align-items: center;

    padding: 14px 16px;

    border: 1px solid #ECE8F8;
    border-radius: 14px;

    background: #FCFBFF;
}

.teacher-row-label {
    display: block;

    margin-bottom: 3px;

    font-size: 8px;
    font-weight: 700;

    color: #94A3B8;

    text-transform: uppercase;
    letter-spacing: .4px;
}

.teacher-row-value {
    font-size: 12px;
    font-weight: 650;

    color: #1F2937;
}

.teacher-price {
    color: #047857;
    font-weight: 800;
}


/* =========================================================
   STATUS
========================================================= */

.teacher-status {
    display: inline-block;

    padding: 5px 9px;

    border-radius: 999px;

    font-size: 9px;
    font-weight: 700;
}

.teacher-status.pending {
    background: #FEF3C7;
    color: #92400E;
}

.teacher-status.confirmed {
    background: #DBEAFE;
    color: #1D4ED8;
}

.teacher-status.paid {
    background: #D1FAE5;
    color: #047857;
}


/* =========================================================
   BUTTONS
========================================================= */

.teacher-dashboard-btn {
    display: inline-block;

    padding: 7px 11px;

    border: 0;
    border-radius: 9px;

    text-decoration: none;

    font-size: 10px;
    font-weight: 700;

    white-space: nowrap;

    cursor: pointer;
}

.teacher-dashboard-btn.primary {
    background: #7C3AED;
    color: #FFFFFF;
}

.teacher-dashboard-btn.success {
    background: #059669;
    color: #FFFFFF;
}

.teacher-dashboard-btn.danger {
    background: #FFFFFF;
    color: #DC2626;

    border: 1px solid #FCA5A5;
}

.teacher-dashboard-btn.secondary {
    background: #F1F5F9;
    color: #475569;
}

.teacher-request-actions {
    display: flex;
    gap: 6px;
    align-items: center;
}

.teacher-request-actions form {
    margin: 0;
}


/* =========================================================
   EMPTY
========================================================= */

.teacher-dashboard-empty {
    padding: 38px 20px;

    text-align: center;

    border-radius: 14px;
    border: 1px dashed #D7CFF3;

    background: #FCFBFF;

    color: #94A3B8;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1000px) {

    .teacher-overview {
        grid-template-columns: 1fr;
    }

    .teacher-dashboard-row {
        grid-template-columns: 1fr 1fr;
    }
}

@media(max-width: 650px) {

    .teacher-dashboard-row {
        grid-template-columns: 1fr;
    }
}

</style>


<div class="teacher-dashboard">


    {{-- =====================================================
       CLICKABLE CARDS
    ====================================================== --}}

    <div class="teacher-overview">


        {{-- NEW REQUESTS --}}
        <div
            class="teacher-stat"
            data-panel="teacher-requests-panel"
        >

            <div class="teacher-stat-top">

                <div class="teacher-stat-label">
                    {{ __('teacher.new_requests') }}
                </div>

                <div class="teacher-stat-icon">
                    📩
                </div>

            </div>

            <div class="teacher-stat-number">
                {{ $pendingRequests }}
            </div>

            <div class="teacher-stat-footer">

                <span>
                    {{ __('teacher.view_lesson_requests') }}
                </span>

                <span>→</span>

            </div>

        </div>


        {{-- UPCOMING --}}
        <div
            class="teacher-stat"
            data-panel="teacher-upcoming-panel"
        >

            <div class="teacher-stat-top">

                <div class="teacher-stat-label">
                    {{ __('teacher.upcoming_lessons') }}
                </div>

                <div class="teacher-stat-icon">
                    📅
                </div>

            </div>

            <div class="teacher-stat-number">
                {{ $upcomingLessons }}
            </div>

            <div class="teacher-stat-footer">

                <span>
                    {{ __('teacher.view_upcoming_lessons') }}
                </span>

                <span>→</span>

            </div>

        </div>


        {{-- PAYMENTS --}}
        <div
            class="teacher-stat"
            data-panel="teacher-payments-panel"
        >

            <div class="teacher-stat-top">

                <div class="teacher-stat-label">
                    {{ __('teacher.payments_received') }}
                </div>

                <div class="teacher-stat-icon">
                    $
                </div>

            </div>

            <div class="teacher-stat-number">
                {{ $paymentsReceived }}
            </div>

            <div class="teacher-stat-footer">

                <span>
                    ${{ number_format(
                        (float) $totalTeacherEarnings,
                        2
                    ) }}

                    {{ __('teacher.earned') }}
                </span>

                <span>→</span>

            </div>

        </div>

    </div>



    {{-- =====================================================
       NOTIFICATIONS
    ====================================================== --}}

    <div class="teacher-notifications">

        <div class="teacher-section-header">

            <div>

                <h3 class="teacher-section-title">
                    {{ __('teacher.notifications') }}
                </h3>

                <div class="teacher-section-subtitle">
                    {{ __('teacher.notification_subtitle') }}
                </div>

            </div>


            @if($unreadNotificationCount > 0)

                <div class="teacher-notification-count">
                    {{ $unreadNotificationCount }}
                </div>

            @endif

        </div>


        @if($notifications->count())

            <div class="teacher-notification-list">

                @foreach($notifications as $notification)

                    @php

                        $type =
                            $notification->data['type']
                            ?? 'default';

                        $title =
                            $notification->data['title']
                            ?? __('teacher.notification');

                        $message =
                            $notification->data['message']
                            ?? '';

                        $url =
                            $notification->data['url']
                            ?? route('teacher.dashboard');

                    @endphp


                    <a
                        href="{{ $url }}"
                        class="
                            teacher-notification-item
                            {{ is_null($notification->read_at)
                                ? 'unread'
                                : ''
                            }}
                        "
                    >

                        <div
                            class="
                                teacher-notification-icon

                                @if($type === 'new_booking_request')
                                    request

                                @elseif($type === 'payment_received')
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
                                    <rect x="5" y="10" width="14" height="11" rx="2"></rect>
                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                                    <circle cx="12" cy="15.5" r="1"></circle>
                                </svg>


                            @elseif($type === 'new_booking_request')

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path d="m3 7 9 6 9-6"></path>
                                    <path d="M18 3v4"></path>
                                    <path d="M16 5h4"></path>
                                </svg>


                            @elseif($type === 'payment_received')

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <path d="M3 10h18"></path>
                                    <path d="M7 15h3"></path>
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
                                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                                    <path d="M10 21h4"></path>
                                </svg>

                            @endif

                        </div>


                        <div class="teacher-notification-content">

                            <div class="teacher-notification-title">
                                {{ $title }}
                            </div>

                            @if($message)

                                <div class="teacher-notification-message">
                                    {{ $message }}
                                </div>

                            @endif


                            <div class="teacher-notification-time">

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


                            @if($type === 'new_booking_request')

                                <span class="teacher-notification-action">
                                    {{ __('teacher.view_request') }}
                                </span>

                            @elseif($type === 'payment_received')

                                <span class="teacher-notification-action">
                                    {{ __('teacher.view_payment') }}
                                </span>

                            @endif

                        </div>

                    </a>

                @endforeach

            </div>

        @else

            <div class="teacher-dashboard-empty">

                <div style="
                    font-size:26px;
                    margin-bottom:7px;
                ">
                    🔔
                </div>

                {{ __('teacher.no_notifications') }}

            </div>

        @endif

    </div>



    {{-- =====================================================
       REQUESTS PANEL
    ====================================================== --}}

    <div
        id="teacher-requests-panel"
        class="teacher-detail-panel"
    >

        <div class="teacher-detail-header">

            <div>

                <h3 class="teacher-detail-title">
                    {{ __('teacher.new_lesson_requests') }}
                </h3>

                <div class="teacher-section-subtitle">
                    {{ __('teacher.incoming_requests_subtitle') }}
                </div>

            </div>

            <button
                type="button"
                class="teacher-close-btn"
            >
                ×
            </button>

        </div>


        @if($pendingRequestList->count())

            <div class="teacher-dashboard-list">

                @foreach($pendingRequestList as $booking)

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


                    <div class="teacher-dashboard-row">

                        <div>

                            <span class="teacher-row-label">
                                {{ __('teacher.student') }}
                            </span>

                            <div class="teacher-row-value">

                                {{ $booking
                                    ->student
                                    ?->user
                                    ?->name
                                    ?? __('teacher.student')
                                }}

                            </div>

                        </div>


                        <div>

                            <span class="teacher-row-label">
                                {{ __('teacher.dance') }}
                            </span>

                            <div class="teacher-row-value">
                                {{ $booking->danceStyle->name ?? __('teacher.dance') }}
                            </div>

                        </div>


                        <div>

                            <span class="teacher-row-label">
                                {{ __('teacher.date') }}
                            </span>

                            <div class="teacher-row-value">

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

                            <span class="teacher-row-label">
                                {{ __('teacher.time') }}
                            </span>

                            <div class="teacher-row-value">

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

                            <span class="teacher-row-label">
                                {{ __('teacher.price') }}
                            </span>

                            <div class="teacher-row-value teacher-price">

                                ${{ number_format(
                                    (float) $booking->price,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div class="teacher-request-actions">

                            <form
                                method="POST"
                                action="{{ route(
                                    'teacher.bookings.accept',
                                    $booking
                                ) }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="
                                        teacher-dashboard-btn
                                        success
                                    "
                                >
                                    {{ __('teacher.accept') }}
                                </button>

                            </form>


                            <form
                                method="POST"
                                action="{{ route(
                                    'teacher.bookings.reject',
                                    $booking
                                ) }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="
                                        teacher-dashboard-btn
                                        danger
                                    "
                                >
                                    {{ __('teacher.refuse') }}
                                </button>

                            </form>

                        </div>

                    </div>

                @endforeach

            </div>


        @else

            <div class="teacher-dashboard-empty">
                {{ __('teacher.no_new_requests') }}
            </div>

        @endif

    </div>



    {{-- =====================================================
       UPCOMING LESSONS PANEL
    ====================================================== --}}

    <div
        id="teacher-upcoming-panel"
        class="teacher-detail-panel"
    >

        <div class="teacher-detail-header">

            <div>

                <h3 class="teacher-detail-title">
                    {{ __('teacher.upcoming_lessons') }}
                </h3>

                <div class="teacher-section-subtitle">
                    {{ __('teacher.upcoming_lessons_subtitle') }}
                </div>

            </div>

            <button
                type="button"
                class="teacher-close-btn"
            >
                ×
            </button>

        </div>


        @if($upcomingLessonList->count())

            <div class="teacher-dashboard-list">

                @foreach($upcomingLessonList as $booking)

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


                    <div class="teacher-dashboard-row">

                        <div>

                            <span class="teacher-row-label">
                                {{ __('teacher.student') }}
                            </span>

                            <div class="teacher-row-value">

                                {{ $booking
                                    ->student
                                    ?->user
                                    ?->name
                                    ?? __('teacher.student')
                                }}

                            </div>

                        </div>


                        <div>

                            <span class="teacher-row-label">
                                {{ __('teacher.dance') }}
                            </span>

                            <div class="teacher-row-value">
                                {{ $booking->danceStyle->name ?? __('teacher.dance') }}
                            </div>

                        </div>


                        <div>

                            <span class="teacher-row-label">
                                {{ __('teacher.date') }}
                            </span>

                            <div class="teacher-row-value">

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

                            <span class="teacher-row-label">
                                {{ __('teacher.time') }}
                            </span>

                            <div class="teacher-row-value">

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

                            <span class="teacher-row-label">
                                {{ __('teacher.payment') }}
                            </span>

                            @if($booking->paid)

                                <span class="teacher-status paid">
                                    {{ __('teacher.paid') }}
                                </span>

                            @else

                                <span class="teacher-status pending">
                                    {{ __('teacher.awaiting_payment') }}
                                </span>

                            @endif

                        </div>


                        <div>

                            <a
                                href="{{ route(
                                    'teacher.bookings.student',
                                    $booking
                                ) }}"
                                class="
                                    teacher-dashboard-btn
                                    secondary
                                "
                            >
                                {{ __('teacher.student') }}
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>


        @else

            <div class="teacher-dashboard-empty">
                {{ __('teacher.no_upcoming_lessons') }}
            </div>

        @endif

    </div>



    {{-- =====================================================
       PAYMENTS PANEL
    ====================================================== --}}

    <div
        id="teacher-payments-panel"
        class="teacher-detail-panel"
    >

        <div class="teacher-detail-header">

            <div>

                <h3 class="teacher-detail-title">
                    {{ __('teacher.payments_received') }}
                </h3>

                <div class="teacher-section-subtitle">
                    {{ __('teacher.payments_subtitle') }}
                </div>

            </div>

            <button
                type="button"
                class="teacher-close-btn"
            >
                ×
            </button>

        </div>


        @if($paymentReceivedList->count())

            <div class="teacher-dashboard-list">

                @foreach($paymentReceivedList as $payment)

                    @php
                        $booking = $payment->booking;
                    @endphp


                    <div class="teacher-dashboard-row">

                        <div>

                            <span class="teacher-row-label">
                                {{ __('teacher.student') }}
                            </span>

                            <div class="teacher-row-value">

                                {{ $booking
                                    ?->student
                                    ?->user
                                    ?->name
                                    ?? __('teacher.student')
                                }}

                            </div>

                        </div>


                        <div>

                            <span class="teacher-row-label">
                                {{ __('teacher.dance') }}
                            </span>

                            <div class="teacher-row-value">

                                {{ $booking
                                    ?->danceStyle
                                    ?->name
                                    ?? __('teacher.dance')
                                }}

                            </div>

                        </div>


                        <div>

                            <span class="teacher-row-label">
                                {{ __('teacher.payment_date') }}
                            </span>

                            <div class="teacher-row-value">

                                @if($payment->paid_at)

                                    {{ \Carbon\Carbon::parse(
                                        $payment->paid_at
                                    )
                                    ->locale(app()->getLocale())
                                    ->translatedFormat(
                                        app()->getLocale() === 'fr'
                                            ? 'd M Y • H:i'
                                            : 'M d, Y • g:i A'
                                    ) }}

                                @else

                                    —

                                @endif

                            </div>

                        </div>


                        <div>

                            <span class="teacher-row-label">
                                {{ __('teacher.lesson_price') }}
                            </span>

                            <div class="teacher-row-value">

                                ${{ number_format(
                                    (float) $payment->amount,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div>

                            <span class="teacher-row-label">
                                {{ __('teacher.your_earnings') }}
                            </span>

                            <div class="teacher-row-value teacher-price">

                                ${{ number_format(
                                    (float) $payment->teacher_amount,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div>

                            <span class="teacher-status paid">
                                {{ __('teacher.paid') }}
                            </span>

                        </div>

                    </div>

                @endforeach

            </div>


        @else

            <div class="teacher-dashboard-empty">
                {{ __('teacher.no_payments_received') }}
            </div>

        @endif

    </div>

</div>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const cards =
            document.querySelectorAll(
                '.teacher-stat'
            );

        const panels =
            document.querySelectorAll(
                '.teacher-detail-panel'
            );

        const closeButtons =
            document.querySelectorAll(
                '.teacher-close-btn'
            );


        cards.forEach(function (card) {

            card.addEventListener(
                'click',
                function () {

                    const targetId =
                        card.dataset.panel;

                    const targetPanel =
                        document.getElementById(
                            targetId
                        );

                    const wasOpen =
                        targetPanel
                            .classList
                            .contains('active');


                    panels.forEach(
                        function (panel) {
                            panel
                                .classList
                                .remove('active');
                        }
                    );


                    cards.forEach(
                        function (item) {
                            item
                                .classList
                                .remove('active');
                        }
                    );


                    if (!wasOpen) {

                        targetPanel
                            .classList
                            .add('active');

                        card
                            .classList
                            .add('active');


                        setTimeout(
                            function () {

                                targetPanel
                                    .scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'start'
                                    });

                            },
                            50
                        );
                    }

                }
            );

        });


        closeButtons.forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        panels.forEach(
                            function (panel) {
                                panel
                                    .classList
                                    .remove('active');
                            }
                        );

                        cards.forEach(
                            function (card) {
                                card
                                    .classList
                                    .remove('active');
                            }
                        );

                    }
                );

            }
        );

    }
);

</script>

@endsection