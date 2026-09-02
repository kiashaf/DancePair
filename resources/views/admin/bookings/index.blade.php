@extends('admin.layout')

@section('title', 'Bookings')
@section('page-title', 'Bookings')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | FALLBACK FILTER CHECK
    |--------------------------------------------------------------------------
    |
    | اگر Controller متغیر $hasFilters را فرستاده باشد، همان استفاده می‌شود.
    | اگر نفرستاده باشد، اینجا از request محاسبه می‌کنیم.
    |
    */

    $hasFilters = $hasFilters ?? (
        request()->filled('search') ||
        request()->filled('status') ||
        request()->filled('payment') ||
        request()->filled('date_from') ||
        request()->filled('date_to') ||
        request()->filled('teacher_id') ||
        request()->filled('student_id')
    );

@endphp


<style>

/* =========================================================
   PAGE
========================================================= */

.admin-bookings-page {
    display: flex;
    flex-direction: column;
    gap: 22px;
}


/* =========================================================
   OVERVIEW
========================================================= */

.booking-overview {
    padding: 20px;

    background: #FFFFFF;

    border: 1px solid #E2E8F0;
    border-top: 4px solid #8B5CF6;
    border-radius: 20px;

    box-shadow:
        0 8px 24px rgba(15, 23, 42, .035);
}

.booking-overview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    gap: 16px;

    margin-bottom: 18px;
}

.booking-overview-title h3 {
    margin: 0;

    font-size: 22px;
    font-weight: 800;

    color: #0F172A;
}

.booking-overview-title p {
    margin: 5px 0 0;

    font-size: 11px;

    color: #64748B;
}

.booking-overview-badge {
    padding: 7px 12px;

    border-radius: 999px;

    background: #F5F3FF;

    border: 1px solid #DDD6FE;

    color: #6D28D9;

    font-size: 10px;
    font-weight: 800;
}


/* =========================================================
   KPI
========================================================= */

.booking-kpi-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0,1fr));

    gap: 12px;
}

.booking-kpi-card {
    padding: 16px;

    min-height: 112px;

    display: flex;
    flex-direction: column;
    justify-content: space-between;

    border-radius: 15px;

    border: 1px solid #E2E8F0;

    background: #FCFDFE;
}

.booking-kpi-label {
    font-size: 9px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .45px;

    color: #64748B;
}

.booking-kpi-value {
    margin-top: 10px;

    font-size: clamp(20px, 1.5vw, 26px);
    font-weight: 800;

    line-height: 1.1;

    color: #0F172A;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.booking-kpi-note {
    margin-top: 8px;

    font-size: 9px;
    font-weight: 650;

    color: #94A3B8;
}


/* =========================================================
   STATUS STRIP
========================================================= */

.booking-status-strip {
    margin-top: 14px;

    display: flex;
    flex-wrap: wrap;

    gap: 8px;
}

.booking-status-chip {
    padding: 6px 10px;

    border-radius: 999px;

    font-size: 9px;
    font-weight: 750;

    border: 1px solid #E2E8F0;

    background: #FFFFFF;

    color: #475569;
}

.booking-status-chip.pending {
    background: #FFFBEB;
    border-color: #FDE68A;
    color: #92400E;
}

.booking-status-chip.completed {
    background: #ECFDF5;
    border-color: #A7F3D0;
    color: #047857;
}

.booking-status-chip.cancelled {
    background: #FEF2F2;
    border-color: #FECACA;
    color: #B91C1C;
}


/* =========================================================
   FILTERS
========================================================= */

.booking-filter-card {
    padding: 20px;

    background: #FFFFFF;

    border: 1px solid #E2E8F0;
    border-radius: 18px;

    box-shadow:
        0 6px 18px rgba(15,23,42,.03);
}

.booking-filter-header {
    margin-bottom: 16px;
}

.booking-filter-header h4 {
    margin: 0;

    font-size: 19px;
    font-weight: 800;

    color: #0F172A;
}

.booking-filter-header p {
    margin: 4px 0 0;

    font-size: 10px;

    color: #64748B;
}

.booking-filter-card .form-label {
    margin-bottom: 5px;

    font-size: 9px;
    font-weight: 750;

    color: #475569;
}

.booking-filter-card .form-control,
.booking-filter-card .form-select {
    min-height: 42px;

    border-radius: 10px;

    border: 1px solid #CBD5E1;

    background: #FBFDFC;

    font-size: 11px;
}

.booking-filter-card .form-control:focus,
.booking-filter-card .form-select:focus {
    border-color: #8B5CF6;

    box-shadow:
        0 0 0 3px rgba(139,92,246,.08);
}

.booking-filter-actions {
    display: flex;
    align-items: end;

    gap: 8px;
}

.booking-filter-btn {
    min-height: 42px;

    padding: 0 16px;

    border: 0;
    border-radius: 10px;

    background: #7C3AED;

    color: #FFFFFF;

    font-size: 10px;
    font-weight: 800;
}

.booking-filter-btn:hover {
    background: #6D28D9;
}

.booking-reset-btn {
    min-height: 42px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 14px;

    border-radius: 10px;

    border: 1px solid #CBD5E1;

    background: #FFFFFF;

    color: #64748B;

    text-decoration: none;

    font-size: 10px;
    font-weight: 700;
}


/* =========================================================
   SEARCH RESULTS
========================================================= */

.booking-management-card {
    padding: 20px;

    background: #FFFFFF;

    border: 1px solid #E2E8F0;
    border-radius: 18px;

    box-shadow:
        0 6px 18px rgba(15,23,42,.03);
}

.booking-management-header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    gap: 14px;

    margin-bottom: 15px;
}

.booking-management-header h4 {
    margin: 0;

    font-size: 19px;
    font-weight: 800;

    color: #0F172A;
}

.booking-management-header p {
    margin: 4px 0 0;

    font-size: 10px;

    color: #64748B;
}

.booking-count-pill {
    padding: 6px 10px;

    border-radius: 999px;

    background: #F8FAFC;

    border: 1px solid #E2E8F0;

    color: #475569;

    font-size: 9px;
    font-weight: 750;
}


/* =========================================================
   TABLE
========================================================= */

.booking-table-wrap {
    overflow-x: auto;

    border: 1px solid #E8EEF3;
    border-radius: 14px;
}

.booking-table {
    width: 100%;

    border-collapse: collapse;

    min-width: 1080px;
}

.booking-table thead {
    background: #F8FAFC;
}

.booking-table th {
    padding: 11px 12px;

    border-bottom: 1px solid #E2E8F0;

    font-size: 8px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .4px;

    color: #64748B;

    text-align: left;
}

.booking-table td {
    padding: 14px 12px;

    border-bottom: 1px solid #EEF2F7;

    font-size: 10.5px;

    color: #334155;

    vertical-align: middle;
}

.booking-table tbody tr:last-child td {
    border-bottom: 0;
}


/* =========================================================
   CLICKABLE ROW
========================================================= */

.booking-clickable-row {
    cursor: pointer;

    transition:
        background-color .15s ease,
        box-shadow .15s ease;
}

.booking-clickable-row:hover {
    background: #F8F5FF;
}

.booking-clickable-row:hover td:first-child {
    box-shadow:
        inset 3px 0 0 #8B5CF6;
}


/* =========================================================
   PERSON
========================================================= */

.booking-person-name {
    font-weight: 750;

    color: #0F172A;
}

.booking-person-email {
    margin-top: 2px;

    font-size: 9px;

    color: #94A3B8;
}


/* =========================================================
   LESSON
========================================================= */

.booking-lesson-date {
    font-weight: 750;

    color: #0F172A;
}

.booking-lesson-time {
    margin-top: 2px;

    font-size: 9px;

    color: #64748B;
}

.booking-price {
    font-weight: 800;

    color: #0F172A;
}


/* =========================================================
   BADGES
========================================================= */

.booking-badge {
    display: inline-flex;
    align-items: center;

    padding: 5px 8px;

    border-radius: 999px;

    font-size: 8px;
    font-weight: 800;
}

.booking-badge.pending {
    background: #FEF3C7;
    color: #92400E;
}

.booking-badge.confirmed {
    background: #DBEAFE;
    color: #1D4ED8;
}

.booking-badge.completed {
    background: #D1FAE5;
    color: #047857;
}

.booking-badge.cancelled {
    background: #FEE2E2;
    color: #B91C1C;
}

.booking-payment-paid {
    display: inline-flex;
    align-items: center;

    gap: 4px;

    font-size: 9px;
    font-weight: 800;

    color: #047857;
}

.booking-payment-unpaid {
    display: inline-flex;
    align-items: center;

    gap: 4px;

    font-size: 9px;
    font-weight: 800;

    color: #C2410C;
}

.booking-payment-dot {
    width: 7px;
    height: 7px;

    border-radius: 50%;

    background: currentColor;
}


/* =========================================================
   VIEW BUTTON
========================================================= */

.booking-view-cell {
    width: 48px;

    text-align: right;
}

.booking-view-btn {
    width: 30px;
    height: 30px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #F5F3FF;

    border: 1px solid #DDD6FE;

    color: #7C3AED;

    font-size: 14px;
    font-weight: 800;

    transition:
        background .15s ease,
        color .15s ease,
        transform .15s ease;
}

.booking-clickable-row:hover .booking-view-btn {
    background: #7C3AED;

    color: #FFFFFF;

    transform: translateX(2px);
}


/* =========================================================
   EMPTY RESULTS
========================================================= */

.booking-empty-results {
    padding: 42px 20px;

    text-align: center;

    color: #94A3B8;

    font-size: 11px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1200px) {

    .booking-kpi-grid {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }
}

@media(max-width: 750px) {

    .booking-kpi-grid {
        grid-template-columns: 1fr;
    }

    .booking-overview-header,
    .booking-management-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .booking-filter-actions {
        align-items: stretch;
    }
}

</style>


<div class="admin-bookings-page">


{{-- =========================================================
   OVERVIEW
========================================================= --}}

<div class="booking-overview">

    <div class="booking-overview-header">

        <div class="booking-overview-title">

            <h3>
                Booking Overview
            </h3>

            <p>
                Monitor lesson activity and payment status.
            </p>

        </div>


        <div class="booking-overview-badge">
            {{ number_format($totalBookings) }} total
        </div>

    </div>


    <div class="booking-kpi-grid">


        {{-- TOTAL --}}
        <div class="booking-kpi-card">

            <div class="booking-kpi-label">
                Total Bookings
            </div>

            <div
                class="booking-kpi-value"
                title="{{ number_format($totalBookings) }}"
            >
                {{ number_format($totalBookings) }}
            </div>

            <div class="booking-kpi-note">
                All lesson requests
            </div>

        </div>


        {{-- CONFIRMED --}}
        <div class="booking-kpi-card">

            <div class="booking-kpi-label">
                Confirmed
            </div>

            <div
                class="booking-kpi-value"
                title="{{ number_format($confirmedCount) }}"
            >
                {{ number_format($confirmedCount) }}
            </div>

            <div class="booking-kpi-note">
                Accepted by teachers
            </div>

        </div>


        {{-- PAID --}}
        <div class="booking-kpi-card">

            <div class="booking-kpi-label">
                Paid
            </div>

            <div
                class="booking-kpi-value"
                title="{{ number_format($paidCount) }}"
            >
                {{ number_format($paidCount) }}
            </div>

            <div class="booking-kpi-note">
                Payment completed
            </div>

        </div>


        {{-- UNPAID --}}
        <div class="booking-kpi-card">

            <div class="booking-kpi-label">
                Unpaid
            </div>

            <div
                class="booking-kpi-value"
                title="{{ number_format($unpaidCount) }}"
            >
                {{ number_format($unpaidCount) }}
            </div>

            <div class="booking-kpi-note">
                Payment outstanding
            </div>

        </div>

    </div>


    {{-- ONLY NON-DUPLICATED STATUS --}}
    <div class="booking-status-strip">

        <span class="booking-status-chip pending">
            Pending {{ number_format($pendingCount) }}
        </span>

        <span class="booking-status-chip completed">
            Completed {{ number_format($completedCount) }}
        </span>

        <span class="booking-status-chip cancelled">
            Cancelled {{ number_format($cancelledCount) }}
        </span>

    </div>

</div>



{{-- =========================================================
   FILTERS
========================================================= --}}

<div class="booking-filter-card">

    <div class="booking-filter-header">

        <h4>
            Find Bookings
        </h4>

        <p>
            Search and filter DancePair lesson activity.
        </p>

    </div>


    <form
        method="GET"
        action="{{ route('admin.bookings') }}"
    >

        <div class="row g-3">


            {{-- SEARCH --}}
            <div class="col-xl-4 col-md-6">

                <label class="form-label">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    value="{{ request('search') }}"
                    placeholder="Student, teacher, email or dance..."
                >

            </div>


            {{-- STATUS --}}
            <div class="col-xl-2 col-md-3">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-select"
                >

                    <option value="">
                        All
                    </option>

                    @foreach([
                        'pending',
                        'confirmed',
                        'completed',
                        'cancelled'
                    ] as $status)

                        <option
                            value="{{ $status }}"
                            {{ request('status') === $status ? 'selected' : '' }}
                        >
                            {{ ucfirst($status) }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- PAYMENT --}}
            <div class="col-xl-2 col-md-3">

                <label class="form-label">
                    Payment
                </label>

                <select
                    name="payment"
                    class="form-select"
                >

                    <option value="">
                        All
                    </option>

                    <option
                        value="paid"
                        {{ request('payment') === 'paid' ? 'selected' : '' }}
                    >
                        Paid
                    </option>

                    <option
                        value="unpaid"
                        {{ request('payment') === 'unpaid' ? 'selected' : '' }}
                    >
                        Unpaid
                    </option>

                </select>

            </div>


            {{-- FROM --}}
            <div class="col-xl-2 col-md-3">

                <label class="form-label">
                    From
                </label>

                <input
                    type="date"
                    name="date_from"
                    class="form-control"
                    value="{{ request('date_from') }}"
                >

            </div>


            {{-- TO --}}
            <div class="col-xl-2 col-md-3">

                <label class="form-label">
                    To
                </label>

                <input
                    type="date"
                    name="date_to"
                    class="form-control"
                    value="{{ request('date_to') }}"
                >

            </div>


            {{-- TEACHER --}}
            <div class="col-xl-4 col-md-6">

                <label class="form-label">
                    Teacher
                </label>

                <select
                    name="teacher_id"
                    class="form-select"
                >

                    <option value="">
                        All Teachers
                    </option>

                    @foreach($teachers as $teacher)

                        <option
                            value="{{ $teacher->id }}"
                            {{ (string) request('teacher_id') === (string) $teacher->id ? 'selected' : '' }}
                        >
                            {{ $teacher->user?->name ?? 'Teacher' }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- STUDENT --}}
            <div class="col-xl-4 col-md-6">

                <label class="form-label">
                    Student
                </label>

                <select
                    name="student_id"
                    class="form-select"
                >

                    <option value="">
                        All Students
                    </option>

                    @foreach($students as $student)

                        <option
                            value="{{ $student->id }}"
                            {{ (string) request('student_id') === (string) $student->id ? 'selected' : '' }}
                        >
                            {{ $student->user?->name ?? 'Student' }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- ACTIONS --}}
            <div class="col-xl-4 col-md-12 booking-filter-actions">

                <button
                    type="submit"
                    class="booking-filter-btn"
                >
                    Apply Filters
                </button>


                <a
                    href="{{ route('admin.bookings') }}"
                    class="booking-reset-btn"
                >
                    Reset
                </a>

            </div>

        </div>

    </form>

</div>



{{-- =========================================================
   SEARCH RESULTS
   ONLY VISIBLE AFTER SEARCH / FILTER
========================================================= --}}

@if($hasFilters)

    <div class="booking-management-card">

        <div class="booking-management-header">

            <div>

                <h4>
                    Search Results
                </h4>

                <p>
                    Results matching your selected filters.
                </p>

            </div>


            <div class="booking-count-pill">

                {{ number_format(
                    $bookings?->total() ?? 0
                ) }}

                results

            </div>

        </div>


        @if($bookings && $bookings->count())


            <div class="booking-table-wrap">

                <table class="booking-table">

                    <thead>

                        <tr>

                            <th>Student</th>

                            <th>Teacher</th>

                            <th>Dance</th>

                            <th>Lesson</th>

                            <th>Duration</th>

                            <th>Price</th>

                            <th>Status</th>

                            <th>Payment</th>

                            <th></th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($bookings as $booking)

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


                            <tr
                                class="booking-clickable-row"
                                onclick="window.location.href='{{ route('admin.bookings.show', $booking) }}'"
                                title="View booking details"
                            >


                                {{-- STUDENT --}}
                                <td>

                                    <div class="booking-person-name">

                                        {{ $booking
                                            ->student
                                            ?->user
                                            ?->name
                                            ?? '—'
                                        }}

                                    </div>


                                    <div class="booking-person-email">

                                        {{ $booking
                                            ->student
                                            ?->user
                                            ?->email
                                            ?? ''
                                        }}

                                    </div>

                                </td>



                                {{-- TEACHER --}}
                                <td>

                                    <div class="booking-person-name">

                                        {{ $booking
                                            ->teacher
                                            ?->user
                                            ?->name
                                            ?? '—'
                                        }}

                                    </div>


                                    <div class="booking-person-email">

                                        {{ $booking
                                            ->teacher
                                            ?->user
                                            ?->email
                                            ?? ''
                                        }}

                                    </div>

                                </td>



                                {{-- DANCE --}}
                                <td>

                                    {{ $booking
                                        ->danceStyle
                                        ?->name
                                        ?? '—'
                                    }}

                                </td>



                                {{-- LESSON --}}
                                <td>

                                    <div class="booking-lesson-date">

                                        {{ \Carbon\Carbon::parse(
                                            $booking->lesson_date
                                        )->format(
                                            'M d, Y'
                                        ) }}

                                    </div>


                                    <div class="booking-lesson-time">

                                        {{ $start->format('g:i A') }}

                                        -

                                        {{ $end->format('g:i A') }}

                                    </div>

                                </td>



                                {{-- DURATION --}}
                                <td>

                                    {{ number_format(
                                        $booking->duration ?? 60
                                    ) }}

                                    min

                                </td>



                                {{-- PRICE --}}
                                <td class="booking-price">

                                    ${{ number_format(
                                        (float) $booking->price,
                                        2
                                    ) }}

                                </td>



                                {{-- STATUS --}}
                                <td>

                                    <span
                                        class="
                                            booking-badge
                                            {{ $booking->status }}
                                        "
                                    >
                                        {{ ucfirst(
                                            $booking->status
                                        ) }}
                                    </span>

                                </td>



                                {{-- PAYMENT --}}
                                <td>

                                    @if($booking->paid)

                                        <span class="booking-payment-paid">

                                            <span class="booking-payment-dot"></span>

                                            Paid

                                        </span>

                                    @else

                                        <span class="booking-payment-unpaid">

                                            <span class="booking-payment-dot"></span>

                                            Unpaid

                                        </span>

                                    @endif

                                </td>



                                {{-- VIEW --}}
                                <td class="booking-view-cell">

                                    <span class="booking-view-btn">
                                        →
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            @if($bookings->hasPages())

                <div class="mt-4">

                    {{ $bookings->links() }}

                </div>

            @endif


        @else


            <div class="booking-empty-results">

                No bookings match your search or filters.

            </div>


        @endif

    </div>

@endif


</div>

@endsection