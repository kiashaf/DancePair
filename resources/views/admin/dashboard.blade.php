@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')

<style>

/* =========================================================
   PAGE
========================================================= */

.admin-dashboard {
    display: flex;
    flex-direction: column;
    gap: 24px;
}


/* =========================================================
   SECTIONS
========================================================= */

.admin-dashboard-section {
    padding: 24px;

    border-radius: 22px;
    border: 1px solid #E2E8F0;

    background: #FFFFFF;

    box-shadow:
        0 8px 24px rgba(15, 23, 42, .035);
}

.admin-dashboard-section.clients {
    border-top: 4px solid #38BDF8;
}

.admin-dashboard-section.bookings {
    border-top: 4px solid #8B5CF6;
}

.admin-dashboard-section.finance {
    border-top: 4px solid #10B981;
}


/* =========================================================
   SECTION HEADER
========================================================= */

.admin-section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 16px;

    margin-bottom: 18px;
}

.admin-section-heading h3 {
    margin: 0;

    font-size: 22px;
    font-weight: 800;

    letter-spacing: -.3px;

    color: #0F172A;
}

.admin-section-heading p {
    margin: 5px 0 0;

    font-size: 11px;

    color: #64748B;
}

.admin-section-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    height: 30px;

    padding: 0 12px;

    border-radius: 999px;

    background: #F8FAFC;

    border: 1px solid #E2E8F0;

    color: #475569;

    font-size: 10px;
    font-weight: 750;

    white-space: nowrap;
}


/* =========================================================
   GRID
========================================================= */

.admin-stats-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 14px;
}


/* =========================================================
   CARD
========================================================= */

.admin-stat-card {
    position: relative;

    min-height: 132px;

    padding: 18px;

    display: flex;
    flex-direction: column;

    background: #FCFDFE;

    border: 1px solid #E2E8F0;
    border-radius: 16px;

    cursor: pointer;

    transition:
        transform .16s ease,
        box-shadow .16s ease,
        border-color .16s ease;
}

.admin-stat-card:hover {
    transform: translateY(-2px);

    border-color: #CBD5E1;

    box-shadow:
        0 8px 18px rgba(15, 23, 42, .055);
}

.admin-stat-card.active {
    border-color: #0EA5E9;

    box-shadow:
        0 0 0 3px rgba(14,165,233,.08);
}

.admin-stat-card.no-panel {
    cursor: default;
}

.admin-stat-card.no-panel:hover {
    transform: none;
    box-shadow: none;
}


/* =========================================================
   CARD TOP
========================================================= */

.admin-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 12px;
}

.admin-stat-label {
    min-width: 0;

    font-size: 10px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .45px;

    color: #64748B;
}


/* =========================================================
   ICON
========================================================= */

.admin-stat-icon {
    width: 36px;
    height: 36px;

    flex: 0 0 36px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    font-size: 15px;

    background: #F1F5F9;

    color: #0F172A;
}

.clients .admin-stat-icon {
    background: #E0F2FE;
}

.bookings .admin-stat-icon {
    background: #EDE9FE;
}

.finance .admin-stat-icon {
    background: #DCFCE7;
}


/* =========================================================
   VALUES
========================================================= */

.admin-stat-number {
    margin-top: 16px;

    max-width: 100%;

    font-size: clamp(21px, 1.7vw, 28px);
    line-height: 1.08;

    font-weight: 800;

    letter-spacing: -.35px;

    color: #0F172A;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;

    font-variant-numeric: tabular-nums;
}

.admin-stat-number.money {
    font-size: clamp(19px, 1.45vw, 25px);
}

.admin-stat-number.percent {
    font-size: clamp(20px, 1.55vw, 26px);
}

.finance .admin-stat-number.money {
    color: #047857;
}
.finance .admin-stat-number.money.dancepair-revenue {
    color: #DC2626;
}


/* =========================================================
   FOOTER
========================================================= */

.admin-stat-footer {
    margin-top: auto;
    padding-top: 14px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 8px;

    font-size: 9.5px;
    font-weight: 700;
}

.clients .admin-stat-footer {
    color: #0284C7;
}

.bookings .admin-stat-footer {
    color: #7C3AED;
}

.finance .admin-stat-footer {
    color: #059669;
}

.admin-stat-footer span:first-child {
    min-width: 0;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.admin-stat-footer span:last-child {
    width: 24px;
    height: 24px;

    flex: 0 0 24px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #F1F5F9;
}

.admin-stat-footer a {
    color: inherit;
    text-decoration: none;
}


/* =========================================================
   DETAIL PANELS
========================================================= */

.admin-detail-panel {
    display: none;

    padding: 22px;

    border-radius: 18px;
    border: 1px solid #E2E8F0;

    background: #FFFFFF;

    box-shadow:
        0 10px 28px rgba(15, 23, 42, .055);
}

.admin-detail-panel.active {
    display: block;
}

.admin-detail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 18px;
}

.admin-detail-title {
    margin: 0;

    font-size: 20px;
    font-weight: 800;

    color: #0F172A;
}

.admin-detail-subtitle {
    margin-top: 4px;

    font-size: 10px;

    color: #64748B;
}

.admin-close-btn {
    width: 34px;
    height: 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 0;
    border-radius: 50%;

    background: #F1F5F9;

    color: #64748B;

    cursor: pointer;

    font-size: 17px;
}

.admin-close-btn:hover {
    background: #E2E8F0;
    color: #0F172A;
}


/* =========================================================
   DETAIL LIST
========================================================= */

.admin-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.admin-row {
    display: grid;

    grid-template-columns:
        repeat(6, minmax(0,1fr));

    gap: 12px;

    align-items: center;

    padding: 13px 14px;

    border: 1px solid #E8EEF3;
    border-radius: 12px;

    background: #FCFDFE;
}

.admin-row:hover {
    background: #F8FAFC;
}

.admin-label {
    display: block;

    margin-bottom: 3px;

    font-size: 7.5px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .4px;

    color: #94A3B8;
}

.admin-value {
    font-size: 10.5px;
    font-weight: 650;

    color: #1F2937;

    overflow-wrap: anywhere;
}

.admin-money {
    color: #047857;
    font-weight: 800;
}

.admin-fee {
    color: #B45309;
    font-weight: 800;
}


/* =========================================================
   STATUS
========================================================= */

.admin-status {
    display: inline-flex;
    align-items: center;

    padding: 5px 8px;

    margin-right: 3px;

    border-radius: 999px;

    font-size: 8px;
    font-weight: 800;
}

.admin-status.pending {
    background: #FEF3C7;
    color: #92400E;
}

.admin-status.confirmed {
    background: #DBEAFE;
    color: #1D4ED8;
}

.admin-status.completed,
.admin-status.paid {
    background: #D1FAE5;
    color: #047857;
}

.admin-status.cancelled {
    background: #FEE2E2;
    color: #B91C1C;
}


/* =========================================================
   ACTION
========================================================= */

.admin-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 7px 11px;

    border-radius: 8px;

    background: #0F766E;

    color: #FFFFFF;

    text-decoration: none;

    font-size: 9px;
    font-weight: 750;
}

.admin-action-btn:hover {
    background: #115E59;
    color: #FFFFFF;
}


/* =========================================================
   EMPTY
========================================================= */

.admin-empty {
    padding: 34px 18px;

    border: 1px dashed #CBD5E1;
    border-radius: 12px;

    text-align: center;

    background: #FAFCFD;

    color: #94A3B8;

    font-size: 10px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1200px) {

    .admin-stats-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .admin-row {
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
    }
}

@media(max-width: 750px) {

    .admin-dashboard {
        gap: 18px;
    }

    .admin-dashboard-section {
        padding: 16px;

        border-radius: 16px;
    }

    .admin-section-heading {
        flex-direction: column;
        align-items: flex-start;
    }

    .admin-stats-grid {
        grid-template-columns: 1fr;
    }

    .admin-row {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }
}

</style>


<div class="admin-dashboard">


{{-- =========================================================
   CLIENTS
========================================================= --}}

<div class="admin-dashboard-section clients">

    <div class="admin-section-heading">

        <div>

            <h3>Users</h3>

            <p>
                Teachers and students registered on DancePair.
            </p>

        </div>


        <div class="admin-section-badge">
            {{ number_format($teachersCount + $studentsCount) }}
            total
        </div>

    </div>


    <div class="admin-stats-grid">


        <div
            class="admin-stat-card"
            data-panel="teachers-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Teachers
                </div>

                <div class="admin-stat-icon">
                    👤
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($teachersCount) }}"
            >
                {{ number_format($teachersCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    View teachers
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="students-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Students
                </div>

                <div class="admin-stat-icon">
                    👥
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($studentsCount) }}"
            >
                {{ number_format($studentsCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    View students
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="pending-teachers-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Pending Teachers
                </div>

                <div class="admin-stat-icon">
                    !
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($pendingTeachersCount) }}"
            >
                {{ number_format($pendingTeachersCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    Verification required
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="reviews-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Reviews
                </div>

                <div class="admin-stat-icon">
                    ★
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($reviewsCount) }}"
            >
                {{ number_format($reviewsCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    View reviews
                </span>

                <span>
                    →
                </span>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
   BOOKINGS
========================================================= --}}

<div class="admin-dashboard-section bookings">

    <div class="admin-section-heading">

        <div>

            <h3>
                Bookings & Lessons
            </h3>

            <p>
                Lesson requests, upcoming classes and booking activity.
            </p>

        </div>


        <div class="admin-section-badge">
            {{ number_format($bookingsCount) }}
            bookings
        </div>

    </div>


    <div class="admin-stats-grid">


        <div
            class="admin-stat-card"
            data-panel="bookings-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Total Bookings
                </div>

                <div class="admin-stat-icon">
                    📚
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($bookingsCount) }}"
            >
                {{ number_format($bookingsCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    View all bookings
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="pending-bookings-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Pending Requests
                </div>

                <div class="admin-stat-icon">
                    ⏳
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($pendingBookingsCount) }}"
            >
                {{ number_format($pendingBookingsCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    Waiting for teacher
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="upcoming-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Upcoming Lessons
                </div>

                <div class="admin-stat-icon">
                    📅
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($upcomingBookingsCount) }}"
            >
                {{ number_format($upcomingBookingsCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    Future accepted lessons
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="completed-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Completed Lessons
                </div>

                <div class="admin-stat-icon">
                    ✓
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($completedBookingsCount) }}"
            >
                {{ number_format($completedBookingsCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    View completed
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="awaiting-payment-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Awaiting Payment
                </div>

                <div class="admin-stat-icon">
                    $
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($awaitingPaymentCount) }}"
            >
                {{ number_format($awaitingPaymentCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    Accepted but unpaid
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="paid-bookings-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Paid Lessons
                </div>

                <div class="admin-stat-icon">
                    💳
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($paidBookingsCount) }}"
            >
                {{ number_format($paidBookingsCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    Payment completed
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="cancelled-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Cancelled / Refused
                </div>

                <div class="admin-stat-icon">
                    ×
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($cancelledBookingsCount) }}"
            >
                {{ number_format($cancelledBookingsCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    View cancelled
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="payments-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Successful Payments
                </div>

                <div class="admin-stat-icon">
                    ✓
                </div>

            </div>

            <div
                class="admin-stat-number"
                title="{{ number_format($paymentsCount) }}"
            >
                {{ number_format($paymentsCount) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    View transactions
                </span>

                <span>
                    →
                </span>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
   FINANCE
========================================================= --}}

<div class="admin-dashboard-section finance">

    <div class="admin-section-heading">

        <div>

            <h3>
                Financial Overview
            </h3>

            <p>
                Payments, commissions and teacher earnings.
            </p>

        </div>


        <div class="admin-section-badge">

            {{ number_format(
                $commissionPercent,
                1
            ) }}%
            commission

        </div>

    </div>


    <div class="admin-stats-grid">


        <div
            class="admin-stat-card"
            data-panel="payments-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Gross Payments
                </div>

                <div class="admin-stat-icon">
                    💳
                </div>

            </div>

            <div
                class="admin-stat-number money"
                title="${{ number_format($grossRevenue, 2) }}"
            >
                ${{ number_format($grossRevenue, 2) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    Total paid by students
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="payments-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    DancePair Revenue
                </div>

                <div class="admin-stat-icon">
                    %
                </div>

            </div>

            <div
            class="admin-stat-number money dancepair-revenue"
             title="${{ number_format($dancePairRevenue, 2) }}"
            >
              ${{ number_format($dancePairRevenue, 2) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    Platform commissions
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div
            class="admin-stat-card"
            data-panel="payments-panel"
        >

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Teacher Earnings
                </div>

                <div class="admin-stat-icon">
                    $
                </div>

            </div>

            <div
                class="admin-stat-number money"
                title="${{ number_format($teacherEarnings, 2) }}"
            >
                ${{ number_format($teacherEarnings, 2) }}
            </div>

            <div class="admin-stat-footer">

                <span>
                    Teacher share
                </span>

                <span>
                    →
                </span>

            </div>

        </div>



        <div class="admin-stat-card no-panel">

            <div class="admin-stat-top">

                <div class="admin-stat-label">
                    Current Commission
                </div>

                <div class="admin-stat-icon">
                    %
                </div>

            </div>

            <div class="admin-stat-number percent">

                {{ number_format(
                    $commissionPercent,
                    1
                ) }}%

            </div>

            <div class="admin-stat-footer">

                <a href="{{ route('admin.settings') }}">
                    Change in Settings
                </a>

                <span>
                    →
                </span>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
   BOOKING DETAIL PANELS
========================================================= --}}

@php

    $bookingPanels = [

        'bookings-panel' => [
            'title' => 'All Bookings',
            'subtitle' => 'Every lesson request in DancePair.',
            'items' => $bookings,
        ],

        'pending-bookings-panel' => [
            'title' => 'Pending Requests',
            'subtitle' => 'Waiting for teacher response.',
            'items' => $pendingBookings,
        ],

        'upcoming-panel' => [
            'title' => 'Upcoming Lessons',
            'subtitle' => 'Accepted future lessons.',
            'items' => $upcomingBookings,
        ],

        'completed-panel' => [
            'title' => 'Completed Lessons',
            'subtitle' => 'Lessons marked as completed.',
            'items' => $completedBookings,
        ],

        'awaiting-payment-panel' => [
            'title' => 'Awaiting Payment',
            'subtitle' => 'Accepted lessons that still require payment.',
            'items' => $awaitingPaymentBookings,
        ],

        'paid-bookings-panel' => [
            'title' => 'Paid Lessons',
            'subtitle' => 'Bookings with completed payment.',
            'items' => $paidBookings,
        ],

        'cancelled-panel' => [
            'title' => 'Cancelled / Refused',
            'subtitle' => 'Cancelled or refused lesson requests.',
            'items' => $cancelledBookings,
        ],

    ];

@endphp


@foreach(
    $bookingPanels
    as $panelId => $panel
)

<div
    id="{{ $panelId }}"
    class="admin-detail-panel"
>

    <div class="admin-detail-header">

        <div>

            <h3 class="admin-detail-title">
                {{ $panel['title'] }}
            </h3>

            <div class="admin-detail-subtitle">
                {{ $panel['subtitle'] }}
            </div>

        </div>

        <button
            type="button"
            class="admin-close-btn"
        >
            ×
        </button>

    </div>


    @if($panel['items']->count())

        <div class="admin-list">

            @foreach(
                $panel['items']
                as $booking
            )

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


                <div class="admin-row">


                    <div>

                        <span class="admin-label">
                            Student
                        </span>

                        <div class="admin-value">

                            {{ $booking
                                ->student
                                ?->user
                                ?->name
                                ?? 'Student'
                            }}

                        </div>

                    </div>


                    <div>

                        <span class="admin-label">
                            Teacher
                        </span>

                        <div class="admin-value">

                            {{ $booking
                                ->teacher
                                ?->user
                                ?->name
                                ?? 'Teacher'
                            }}

                        </div>

                    </div>


                    <div>

                        <span class="admin-label">
                            Dance
                        </span>

                        <div class="admin-value">

                            {{ $booking
                                ->danceStyle
                                ?->name
                                ?? 'Dance'
                            }}

                        </div>

                    </div>


                    <div>

                        <span class="admin-label">
                            Lesson
                        </span>

                        <div class="admin-value">

                            {{ \Carbon\Carbon::parse(
                                $booking->lesson_date
                            )->format('M d, Y') }}

                            <br>

                            {{ $start->format('g:i A') }}
                            -
                            {{ $end->format('g:i A') }}

                        </div>

                    </div>


                    <div>

                        <span class="admin-label">
                            Price
                        </span>

                        <div class="admin-money">

                            ${{ number_format(
                                (float) $booking->price,
                                2
                            ) }}

                        </div>

                    </div>


                    <div>

                        <span class="admin-label">
                            Status
                        </span>

                        <span
                            class="
                                admin-status
                                {{ $booking->status }}
                            "
                        >
                            {{ ucfirst(
                                $booking->status
                            ) }}
                        </span>


                        @if($booking->paid)

                            <span class="admin-status paid">
                                Paid
                            </span>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="admin-empty">
            Nothing to show here.
        </div>

    @endif

</div>

@endforeach



{{-- =========================================================
   TEACHERS PANEL
========================================================= --}}

<div
    id="teachers-panel"
    class="admin-detail-panel"
>

    <div class="admin-detail-header">

        <div>

            <h3 class="admin-detail-title">
                Teachers
            </h3>

            <div class="admin-detail-subtitle">
                All registered DancePair teachers.
            </div>

        </div>

        <button
            class="admin-close-btn"
            type="button"
        >
            ×
        </button>

    </div>


    <div class="admin-list">

        @forelse(
            $teachers
            as $teacher
        )

            <div class="admin-row">


                <div>

                    <span class="admin-label">
                        Name
                    </span>

                    <div class="admin-value">
                        {{ $teacher->user?->name ?? '—' }}
                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Email
                    </span>

                    <div class="admin-value">
                        {{ $teacher->user?->email ?? '—' }}
                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        City
                    </span>

                    <div class="admin-value">
                        {{ $teacher->city ?? '—' }}
                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Status
                    </span>

                    <span
                        class="
                            admin-status
                            {{ $teacher->verified
                                ? 'completed'
                                : 'pending'
                            }}
                        "
                    >

                        {{ $teacher->verified
                            ? 'Verified'
                            : 'Pending'
                        }}

                    </span>

                </div>


                <div>

                    <span class="admin-label">
                        Joined
                    </span>

                    <div class="admin-value">

                        {{ optional(
                            $teacher->created_at
                        )->format('M d, Y') }}

                    </div>

                </div>


                <div>

                    <a
                        href="{{ route(
                            'admin.teachers.edit',
                            $teacher
                        ) }}"
                        class="admin-action-btn"
                    >
                        Manage
                    </a>

                </div>

            </div>

        @empty

            <div class="admin-empty">
                No teachers registered.
            </div>

        @endforelse

    </div>

</div>



{{-- =========================================================
   PENDING TEACHERS
========================================================= --}}

<div
    id="pending-teachers-panel"
    class="admin-detail-panel"
>

    <div class="admin-detail-header">

        <div>

            <h3 class="admin-detail-title">
                Pending Teachers
            </h3>

            <div class="admin-detail-subtitle">
                Teacher accounts waiting for verification.
            </div>

        </div>

        <button
            class="admin-close-btn"
            type="button"
        >
            ×
        </button>

    </div>


    <div class="admin-list">

        @forelse(
            $pendingTeachers
            as $teacher
        )

            <div class="admin-row">


                <div>

                    <span class="admin-label">
                        Teacher
                    </span>

                    <div class="admin-value">
                        {{ $teacher->user?->name ?? '—' }}
                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Email
                    </span>

                    <div class="admin-value">
                        {{ $teacher->user?->email ?? '—' }}
                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        City
                    </span>

                    <div class="admin-value">
                        {{ $teacher->city ?? '—' }}
                    </div>

                </div>


                <div>

                    <span class="admin-status pending">
                        Pending Verification
                    </span>

                </div>


                <div></div>


                <div>

                    <a
                        href="{{ route(
                            'admin.teachers.edit',
                            $teacher
                        ) }}"
                        class="admin-action-btn"
                    >
                        Review
                    </a>

                </div>

            </div>

        @empty

            <div class="admin-empty">
                No pending teachers.
            </div>

        @endforelse

    </div>

</div>



{{-- =========================================================
   STUDENTS PANEL
========================================================= --}}

<div
    id="students-panel"
    class="admin-detail-panel"
>

    <div class="admin-detail-header">

        <div>

            <h3 class="admin-detail-title">
                Students
            </h3>

            <div class="admin-detail-subtitle">
                All registered DancePair students.
            </div>

        </div>

        <button
            class="admin-close-btn"
            type="button"
        >
            ×
        </button>

    </div>


    <div class="admin-list">

        @forelse(
            $students
            as $student
        )

            <div class="admin-row">


                <div>

                    <span class="admin-label">
                        Name
                    </span>

                    <div class="admin-value">
                        {{ $student->user?->name ?? '—' }}
                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Email
                    </span>

                    <div class="admin-value">
                        {{ $student->user?->email ?? '—' }}
                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        City
                    </span>

                    <div class="admin-value">
                        {{ $student->city ?? '—' }}
                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Joined
                    </span>

                    <div class="admin-value">

                        {{ optional(
                            $student->created_at
                        )->format('M d, Y') }}

                    </div>

                </div>


                <div></div>


                <div>

                    <a
                        href="{{ route(
                            'admin.students.edit',
                            $student
                        ) }}"
                        class="admin-action-btn"
                    >
                        Manage
                    </a>

                </div>

            </div>

        @empty

            <div class="admin-empty">
                No students registered.
            </div>

        @endforelse

    </div>

</div>



{{-- =========================================================
   PAYMENTS PANEL
========================================================= --}}

<div
    id="payments-panel"
    class="admin-detail-panel"
>

    <div class="admin-detail-header">

        <div>

            <h3 class="admin-detail-title">
                Payment Transactions
            </h3>

            <div class="admin-detail-subtitle">
                Full financial breakdown of successful payments.
            </div>

        </div>

        <button
            class="admin-close-btn"
            type="button"
        >
            ×
        </button>

    </div>


    <div class="admin-list">

        @forelse(
            $payments
            as $payment
        )

            <div class="admin-row">


                <div>

                    <span class="admin-label">
                        Student
                    </span>

                    <div class="admin-value">

                        {{ $payment
                            ->booking
                            ?->student
                            ?->user
                            ?->name
                            ?? 'Student'
                        }}

                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Teacher
                    </span>

                    <div class="admin-value">

                        {{ $payment
                            ->booking
                            ?->teacher
                            ?->user
                            ?->name
                            ?? 'Teacher'
                        }}

                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Dance
                    </span>

                    <div class="admin-value">

                        {{ $payment
                            ->booking
                            ?->danceStyle
                            ?->name
                            ?? 'Dance'
                        }}

                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Gross
                    </span>

                    <div class="admin-money">

                        ${{ number_format(
                            (float) $payment->amount,
                            2
                        ) }}

                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        DancePair Fee
                    </span>

                    <div class="admin-fee">

                        ${{ number_format(
                            (float) $payment->platform_fee,
                            2
                        ) }}

                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Teacher Share
                    </span>

                    <div class="admin-money">

                        ${{ number_format(
                            (float) $payment->teacher_amount,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        @empty

            <div class="admin-empty">
                No successful payments yet.
            </div>

        @endforelse

    </div>

</div>



{{-- =========================================================
   REVIEWS PANEL
========================================================= --}}

<div
    id="reviews-panel"
    class="admin-detail-panel"
>

    <div class="admin-detail-header">

        <div>

            <h3 class="admin-detail-title">
                Reviews
            </h3>

            <div class="admin-detail-subtitle">
                Reviews submitted through DancePair.
            </div>

        </div>

        <button
            class="admin-close-btn"
            type="button"
        >
            ×
        </button>

    </div>


    <div class="admin-list">

        @forelse(
            $reviews
            as $review
        )

            <div class="admin-row">


                <div>

                    <span class="admin-label">
                        From
                    </span>

                    <div class="admin-value">

                        {{ ucfirst(
                            $review->reviewer_type
                        ) }}

                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Student
                    </span>

                    <div class="admin-value">
                        {{ $review->student?->user?->name ?? '—' }}
                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Teacher
                    </span>

                    <div class="admin-value">
                        {{ $review->teacher?->user?->name ?? '—' }}
                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Rating
                    </span>

                    <div class="admin-value">
                        {{ $review->rating }} / 5
                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Date
                    </span>

                    <div class="admin-value">

                        {{ optional(
                            $review->created_at
                        )->format(
                            'M d, Y • g:i A'
                        ) }}

                    </div>

                </div>


                <div>

                    <span class="admin-label">
                        Comment
                    </span>

                    <div class="admin-value">
                        {{ $review->comment ?: '—' }}
                    </div>

                </div>

            </div>

        @empty

            <div class="admin-empty">
                No reviews yet.
            </div>

        @endforelse

    </div>

</div>


</div>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const cards =
            document.querySelectorAll(
                '.admin-stat-card[data-panel]'
            );

        const panels =
            document.querySelectorAll(
                '.admin-detail-panel'
            );

        const closeButtons =
            document.querySelectorAll(
                '.admin-close-btn'
            );


        function closeAll()
        {
            panels.forEach(
                function (panel) {

                    panel.classList.remove(
                        'active'
                    );

                }
            );


            cards.forEach(
                function (card) {

                    card.classList.remove(
                        'active'
                    );

                }
            );
        }


        function openPanel(panelId)
        {
            const panel =
                document.getElementById(
                    panelId
                );


            if (!panel) {
                return;
            }


            closeAll();


            panel.classList.add(
                'active'
            );


            cards.forEach(
                function (card) {

                    if (
                        card.dataset.panel
                        ===
                        panelId
                    ) {
                        card.classList.add(
                            'active'
                        );
                    }

                }
            );


            setTimeout(
                function () {

                    panel.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });

                },
                50
            );
        }


        cards.forEach(
            function (card) {

                card.addEventListener(
                    'click',
                    function (event) {

                        if (
                            event.target.closest(
                                'a'
                            )
                        ) {
                            return;
                        }


                        const panelId =
                            card.dataset.panel;


                        const panel =
                            document.getElementById(
                                panelId
                            );


                        const alreadyOpen =
                            panel
                            &&
                            panel.classList.contains(
                                'active'
                            );


                        closeAll();


                        if (!alreadyOpen) {
                            openPanel(
                                panelId
                            );
                        }

                    }
                );

            }
        );


        closeButtons.forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    closeAll
                );

            }
        );


        const params =
            new URLSearchParams(
                window.location.search
            );


        const panel =
            params.get(
                'panel'
            );


        if (panel) {
            openPanel(panel);
        }

    }
);

</script>

@endsection