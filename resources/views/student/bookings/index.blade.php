@extends('student.layout')

@section('title', __('student.my_bookings'))
@section('page-title', __('student.my_bookings'))

@section('content')

@php
    $month = (int) request('month', now()->month);
    $year = (int) request('year', now()->year);

    $firstDay = \Carbon\Carbon::create($year, $month, 1);
    $daysInMonth = $firstDay->daysInMonth;
    $startDay = $firstDay->dayOfWeekIso;

    $previousMonth = $firstDay->copy()->subMonth();
    $nextMonth = $firstDay->copy()->addMonth();
@endphp


<style>

/* =========================================================
   CALENDAR
========================================================= */

.student-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    border-left: 1px solid #cfe3f3;
    border-top: 1px solid #cfe3f3;
    border-radius: 14px;
    overflow: hidden;
}

.student-calendar-header {
    background: #dff2ff;
    font-weight: 700;
    text-align: center;
    padding: 12px 5px;
    border-right: 1px solid #cfe3f3;
    border-bottom: 1px solid #cfe3f3;
    font-size: 13px;
}

.student-calendar-day {
    min-height: 125px;
    padding: 8px;
    background: white;
    border-right: 1px solid #cfe3f3;
    border-bottom: 1px solid #cfe3f3;
    overflow: hidden;
}

.student-calendar-day.empty {
    background: #f7fbff;
}

.student-calendar-day.today {
    background: #edf8ff;
}

.student-day-number {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 6px;
}

.student-calendar-day.today .student-day-number {
    background: #078bc7;
    color: white;
}


/* =========================================================
   CALENDAR BOOKING
========================================================= */

.student-calendar-booking {
    padding: 6px 7px;
    border-radius: 8px;
    margin-top: 5px;
    font-size: 10px;
    line-height: 1.35;
}

.student-calendar-booking.booking-pending {
    background: #fff3cd;
    color: #6b5200;
    border-left: 3px solid #ffc107;
}

.student-calendar-booking.booking-confirmed {
    background: #d1fae5;
    color: #047857;
    border-left: 3px solid #10b981;
}

.student-calendar-booking.booking-completed {
    background: #dbeafe;
    color: #1d4ed8;
    border-left: 3px solid #3b82f6;
}

.booking-calendar-teacher {
    font-weight: 700;
}

.booking-calendar-style {
    font-weight: 600;
    margin-top: 1px;
}

.booking-calendar-time {
    margin-top: 2px;
}

.booking-calendar-status {
    font-size: 9px;
    font-weight: 700;
    margin-top: 2px;
    text-transform: uppercase;
}


/* =========================================================
   CALENDAR NAVIGATION
========================================================= */

.calendar-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}


/* =========================================================
   MY REQUESTS
========================================================= */

.student-request-list {
    display: flex;
    flex-direction: column;
}

.student-request-row {
    display: grid;

    grid-template-columns:
        1.2fr
        1fr
        1fr
        1fr
        .65fr
        95px
        118px;

    align-items: center;
    gap: 12px;
    padding: 11px 4px;
    border-bottom: 1px solid #cfe3f3;
    font-size: 13px;
}

.student-request-row:last-child {
    border-bottom: none;
}


/* =========================================================
   LABELS / VALUES
========================================================= */

.student-request-label {
    display: block;
    color: #7c96a8;
    font-size: 9px;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 2px;
}

.student-request-value {
    font-size: 13px;
    font-weight: 500;
}

.student-request-price {
    color: #0284c7;
    font-size: 13px;
    font-weight: 700;
}


/* =========================================================
   STATUS
========================================================= */

.student-request-status-area {
    width: 95px;
}

.student-request-status {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
}

.student-request-status.pending {
    background: #fff3cd;
    color: #7a5b00;
}

.student-request-status.accepted {
    background: #d1fae5;
    color: #047857;
}

.student-request-status.refused {
    background: #fee2e2;
    color: #b91c1c;
}

.student-request-status.completed {
    background: #dbeafe;
    color: #1d4ed8;
}


/* =========================================================
   ACTIONS
========================================================= */

/* =========================================================
   ACTIONS - CLEAN COMPACT DROPDOWN
========================================================= */

.student-request-actions {
    width: 125px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    flex-wrap: nowrap;
    overflow: visible;
}


/* MESSAGE - دست نزنیم، فقط کمی مرتب */

.student-message-btn {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    gap: 6px;

    height: 34px;
    padding: 0 10px !important;

    border-radius: 9px !important;

    font-size: 11px !important;
    font-weight: 600 !important;
}


/* MESSAGE COUNT */

.student-message-count {
    min-width: 18px;
    height: 18px;

    padding: 0 5px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 999px;

    background: #0d6efd;
    color: #fff;

    font-size: 9px;
    font-weight: 700;
    line-height: 1;
}


/* =========================================================
   THREE DOT BUTTON
========================================================= */

.student-more-btn {
    width: 35px !important;
    height: 34px !important;
    min-width: 35px !important;

    padding: 0 0 6px 0 !important;

    display: inline-flex !important;
    align-items: center;
    justify-content: center;

    border: 1px solid #b9c7d2 !important;
    border-radius: 9px !important;

    background: #ffffff !important;
    color: #64748b !important;

    font-size: 19px !important;
    font-weight: 700 !important;
    line-height: 1 !important;

    box-shadow: none !important;

    transition:
        background .15s ease,
        border-color .15s ease,
        color .15s ease;
}


.student-more-btn:hover,
.student-more-btn:focus,
.student-more-btn.show {
    background: #f1f5f9 !important;
    border-color: #94a3b8 !important;
    color: #334155 !important;
}


/* =========================================================
   THREE DOT BUTTON - PAYMENT STATUS COLOR
========================================================= */


/*
 * Teacher accepted + payment required
 * RED
 */

 .student-more-btn.student-more-btn-payment-required {
    background: #FEE2E2 !important;
    border-color: #DC2626 !important;
    color: #B91C1C !important;

    box-shadow:
        0 0 0 1px rgba(220, 38, 38, .10) !important;
}


.student-more-btn.student-more-btn-payment-required:hover,
.student-more-btn.student-more-btn-payment-required:focus,
.student-more-btn.student-more-btn-payment-required.show {
    background: #FECACA !important;
    border-color: #B91C1C !important;
    color: #991B1B !important;

    box-shadow:
        0 0 0 2px rgba(220, 38, 38, .12) !important;
}



/*
 * Student paid
 * GREEN
 */

.student-more-btn.student-more-btn-paid {
    background: #DCFCE7 !important;
    border-color: #16A34A !important;
    color: #15803D !important;

    box-shadow:
        0 0 0 1px rgba(22, 163, 74, .10) !important;
}


.student-more-btn.student-more-btn-paid:hover,
.student-more-btn.student-more-btn-paid:focus,
.student-more-btn.student-more-btn-paid.show {
    background: #BBF7D0 !important;
    border-color: #15803D !important;
    color: #166534 !important;

    box-shadow:
        0 0 0 2px rgba(22, 163, 74, .12) !important;
}


/* =========================================================
   DROPDOWN
========================================================= */

.student-actions-dropdown {
    min-width: 155px !important;
    width: auto !important;

    padding: 5px !important;

    margin-top: 5px !important;

    border: 1px solid #d8e2ea !important;
    border-radius: 10px !important;

    background: #ffffff !important;

    box-shadow:
        0 8px 22px rgba(15, 23, 42, .10),
        0 2px 5px rgba(15, 23, 42, .05) !important;

    overflow: hidden;

    z-index: 1080 !important;
}


/* فرم Delete */

.student-actions-dropdown form {
    margin: 0 !important;
    padding: 0 !important;
}


/* =========================================================
   ITEMS
========================================================= */

.student-actions-dropdown .dropdown-item {
    min-height: 34px;

    padding: 7px 9px !important;

    display: flex;
    align-items: center;

    border-radius: 7px !important;

    color: #334155;

    font-size: 11px !important;
    font-weight: 500;

    line-height: 1.2;

    transition:
        background .12s ease,
        color .12s ease;
}


/* NORMAL HOVER */

.student-actions-dropdown .dropdown-item:hover,
.student-actions-dropdown .dropdown-item:focus {
    background: #f3f7fa !important;
    color: #0f172a !important;
}


/* DELETE */

.student-actions-dropdown .dropdown-item.text-danger {
    color: #dc2626 !important;
}

.student-actions-dropdown .dropdown-item.text-danger:hover,
.student-actions-dropdown .dropdown-item.text-danger:focus {
    background: #fff1f2 !important;
    color: #b91c1c !important;
}


/* PAY */

.student-actions-dropdown .dropdown-item.text-success {
    color: #047857 !important;
}

.student-actions-dropdown .dropdown-item.text-success:hover,
.student-actions-dropdown .dropdown-item.text-success:focus {
    background: #ecfdf5 !important;
    color: #047857 !important;
}


/* PAID TEXT */

.student-actions-dropdown .dropdown-item-text {
    min-height: 34px;

    padding: 7px 9px !important;

    display: flex;
    align-items: center;

    font-size: 11px !important;
    font-weight: 600;

    border-radius: 7px;

    color: #047857 !important;

    background: #f0fdf4;
}


/* REVIEW STAR */

.student-actions-dropdown .review-star {
    margin-right: 5px;
    color: #f59e0b;
}


/* DIVIDER */

.student-actions-dropdown .dropdown-divider {
    margin: 4px 3px !important;

    border-top: 1px solid #e6edf2 !important;
}


/* =========================================================
   SMALL OPEN ANIMATION
========================================================= */

.student-actions-dropdown.show {
    animation: studentDropdownOpen .12s ease-out;
}

@keyframes studentDropdownOpen {

    from {
        opacity: 0;
        transform: translateY(-3px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }

}


/* =========================================================
   MESSAGE AREA
========================================================= */

.student-message-area {
    padding: 16px;
    margin: 10px 0 14px;
    border: 1px solid #cfe3f3;
    border-radius: 14px;
    background: rgba(255, 255, 255, .72);
}

.student-conversation-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 12px;
}

.student-conversation-title {
    font-size: 14px;
    font-weight: 700;
}

.student-conversation-teacher {
    margin-top: 2px;
    color: #64748b;
    font-size: 11px;
}

.student-conversation-history {
    display: block !important;
    width: 100% !important;
    height: auto !important;
    min-height: 0 !important;
    max-height: 360px !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    padding: 12px;
    margin-bottom: 14px;
    border: 1px solid #d7e9f5;
    border-radius: 12px;
    background: #ffffff;
}

.student-chat-message-row {
    display: flex !important;
    width: 100% !important;
    height: auto !important;
    min-height: 0 !important;
    align-items: flex-start !important;
    margin: 0 0 10px 0 !important;
    padding: 0 !important;
}

.student-chat-message-row:last-child {
    margin-bottom: 0 !important;
}

.student-chat-message-row.mine {
    justify-content: flex-end !important;
}

.student-chat-message-row.theirs {
    justify-content: flex-start !important;
}

.student-chat-bubble {
    display: block !important;
    flex: 0 1 auto !important;
    width: auto !important;
    height: auto !important;
    min-width: 0 !important;
    min-height: 0 !important;
    max-width: 70% !important;
    padding: 9px 12px !important;
    margin: 0 !important;
    border-radius: 13px;
    font-size: 13px;
    line-height: 1.45;
    word-break: break-word;
    overflow-wrap: anywhere;
    white-space: normal;
    align-self: flex-start !important;
}

.student-chat-message-row.mine .student-chat-bubble {
    background: #0284c7;
    color: #ffffff;
    border-bottom-right-radius: 4px;
}

.student-chat-message-row.theirs .student-chat-bubble {
    background: #edf6fc;
    color: #1f2937;
    border-bottom-left-radius: 4px;
}

.student-chat-meta {
    display: flex !important;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin: 0 0 5px 0 !important;
    font-size: 10px;
    font-weight: 700;
    opacity: .85;
}

.student-chat-date {
    white-space: nowrap;
    font-size: 9px;
    font-weight: 400;
    opacity: .8;
}

.student-chat-bubble {
    text-align: left !important;
    direction: ltr !important;
}

.student-chat-bubble > div:last-child {
    display: block;

    width: 100% !important;
    height: auto !important;
    min-height: 0 !important;

    margin: 0 !important;
    padding: 0 !important;

    text-align: left !important;
    direction: ltr !important;

    /* line breakهای واقعی پیام حفظ میشه،
       ولی فاصله‌های اضافی Blade حذف میشه */
    white-space: pre-line !important;

    word-break: break-word;
    overflow-wrap: anywhere;
}

.student-message-form-label {
    display: block;
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 700;
}

.student-message-textarea {
    display: block;
    width: 100%;
    min-height: 85px;
    resize: vertical;
    padding: 10px 12px;
    border: 1px solid #c9ddea;
    border-radius: 10px;
    background: #ffffff;
    font-size: 13px;
    line-height: 1.5;
    outline: none;
}

.student-message-textarea:focus {
    border-color: #0284c7;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, .10);
}

.student-message-warning {
    display: block;
    margin: 5px 0 11px;
    color: #8495a5;
    font-size: 10px;
    line-height: 1.4;
}

.student-message-action-row {
    display: flex;
    justify-content: flex-end;
}

.student-message-action-row .btn {
    min-width: 150px;
}


/* =========================================================
   EDIT AREA
========================================================= */

.student-request-edit {
    padding: 12px;
    background: rgba(255, 255, 255, .55);
    border-bottom: 1px solid #cfe3f3;
}

.student-request-edit .form-label {
    font-size: 11px;
}


/* =========================================================
   REVIEW AREA
========================================================= */

.student-review-area {
    padding: 14px;
    background: #fffdf4;
    border-bottom: 1px solid #f0df9d;
}

.student-review-area .form-label {
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 4px;
}

.student-review-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 3px;
}

.student-review-stars input {
    display: none;
}

.student-review-stars label {
    cursor: pointer;
    font-size: 27px;
    line-height: 1;
    color: #d1d5db;
    transition: color .15s ease;
}

.student-review-stars input:checked ~ label,
.student-review-stars label:hover,
.student-review-stars label:hover ~ label {
    color: #f5b301;
}

.student-review-existing {
    font-size: 11px;
    color: #047857;
    margin-top: 6px;
}

.student-review-btn {
    background: #ffffff !important;
    border: 1px solid #7C3AED !important;
    color: #5B21B6 !important;
    font-size: 11px !important;
    font-weight: 600;
    padding: 5px 10px !important;
    border-radius: 8px !important;
    white-space: nowrap;
}

.student-review-btn .review-star {
    color: #F5B301;
    margin-right: 3px;
}

.student-review-btn:hover,
.student-review-btn:focus {
    background: #7C3AED !important;
    color: #ffffff !important;
    border-color: #7C3AED !important;
}

.student-review-btn:hover .review-star,
.student-review-btn:focus .review-star {
    color: #FFD54A;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .student-request-row {
        grid-template-columns:
            1fr
            1fr
            1fr;
    }

    .student-request-actions,
    .student-request-status-area {
        width: auto;
    }
}

@media (max-width: 900px) {

    .student-calendar {
        overflow-x: auto;
        grid-template-columns:
            repeat(7, minmax(120px, 1fr));
    }
}

@media (max-width: 700px) {

    .student-request-row {
        grid-template-columns:
            1fr
            1fr;
    }

    .student-request-actions {
        flex-wrap: wrap;
    }
}

</style>



{{-- =========================================================
   MESSAGES
========================================================= --}}

@if(session('success'))

    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger mb-4">
        {{ session('error') }}
    </div>

@endif



{{-- =========================================================
   CALENDAR
========================================================= --}}

<div class="card profile-card p-4 mb-4">

    <div class="calendar-navigation">

        <a
            href="{{ route('student.bookings', [
                'month' => $previousMonth->month,
                'year' => $previousMonth->year
            ]) }}"
            class="btn btn-outline-primary"
        >
            ← {{ __('student.previous') }}
        </a>


        <h3 class="mb-0 fw-bold">

            {{ $firstDay
                ->copy()
                ->locale(app()->getLocale())
                ->translatedFormat('F Y')
            }}

        </h3>


        <a
            href="{{ route('student.bookings', [
                'month' => $nextMonth->month,
                'year' => $nextMonth->year
            ]) }}"
            class="btn btn-outline-primary"
        >
            {{ __('student.next') }} →
        </a>

    </div>


    <div class="student-calendar">

        {{-- DAYS HEADER --}}

        <div class="student-calendar-header">
            {{ __('student.monday_short') }}
        </div>

        <div class="student-calendar-header">
            {{ __('student.tuesday_short') }}
        </div>

        <div class="student-calendar-header">
            {{ __('student.wednesday_short') }}
        </div>

        <div class="student-calendar-header">
            {{ __('student.thursday_short') }}
        </div>

        <div class="student-calendar-header">
            {{ __('student.friday_short') }}
        </div>

        <div class="student-calendar-header">
            {{ __('student.saturday_short') }}
        </div>

        <div class="student-calendar-header">
            {{ __('student.sunday_short') }}
        </div>


        {{-- EMPTY DAYS BEFORE MONTH --}}

        @for($i = 1; $i < $startDay; $i++)

            <div class="student-calendar-day empty"></div>

        @endfor


        {{-- MONTH DAYS --}}

        @for($day = 1; $day <= $daysInMonth; $day++)

            @php

                $currentDate = \Carbon\Carbon::create(
                    $year,
                    $month,
                    $day
                );

                $date = $currentDate->format('Y-m-d');


                $dayBookings = $bookings->filter(
                    function ($booking) use ($date) {

                        if (!$booking->lesson_date) {
                            return false;
                        }

                        $sameDate =
                            \Carbon\Carbon::parse(
                                $booking->lesson_date
                            )->format('Y-m-d') === $date;


                        $visibleStatus =
                            in_array(
                                $booking->status,
                                [
                                    'pending',
                                    'confirmed',
                                    'completed'
                                ]
                            );


                        return
                            $sameDate
                            &&
                            $visibleStatus;
                    }
                );


                $isToday =
                    $currentDate->isToday();

            @endphp


            <div
                class="student-calendar-day
                {{ $isToday ? 'today' : '' }}"
            >

                <div class="student-day-number">
                    {{ $day }}
                </div>


                @foreach($dayBookings as $booking)

                    @php

                        $start =
                            \Carbon\Carbon::parse(
                                $booking->lesson_time
                            );

                        $end =
                            $start->copy()->addMinutes(
                                $booking->duration ?? 60
                            );


                        $calendarClass = '';

                        if ($booking->status === 'pending') {

                            $calendarClass =
                                'booking-pending';

                        } elseif($booking->status === 'confirmed') {

                            $calendarClass =
                                'booking-confirmed';

                        } elseif($booking->status === 'completed') {

                            $calendarClass =
                                'booking-completed';
                        }

                    @endphp


                    <div
                        class="student-calendar-booking {{ $calendarClass }}"
                    >

                        <div class="booking-calendar-teacher">

                            {{ $booking->teacher->user->name ?? __('student.teacher') }}

                        </div>


                        <div class="booking-calendar-style">

                            {{ $booking->danceStyle->name ?? __('student.dance') }}

                        </div>


                        <div class="booking-calendar-time">

                            {{ $start->format('H:i') }}
                            -
                            {{ $end->format('H:i') }}

                        </div>


                        <div class="booking-calendar-status">

                            @if($booking->status === 'pending')

                                {{ __('student.pending') }}

                            @elseif($booking->status === 'confirmed')

                                {{ __('student.accepted') }}

                            @elseif($booking->status === 'completed')

                                {{ __('student.completed') }}

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        @endfor

    </div>

</div>



{{-- =========================================================
   MY REQUESTS
========================================================= --}}

<div class="card profile-card p-4">

    <div class="mb-3">

        <h3 class="mb-1">
            {{ __('student.my_requests') }}
        </h3>

        <small class="text-muted">
            {{ __('student.requests_manage_review_subtitle') }}
        </small>

    </div>


    <div class="student-request-list">

        @forelse($bookings as $booking)

            @php

                $start =
                    \Carbon\Carbon::parse(
                        $booking->lesson_time
                    );


                $end =
                    $start->copy()->addMinutes(
                        $booking->duration ?? 60
                    );


                /*
                 * Payment availability
                 * Teacher accepted + unpaid + lesson date/time is still in the future
                 */

                $lessonDateTime =
                    \Carbon\Carbon::parse(
                        $booking->lesson_date
                    );

                $lessonTime =
                    \Carbon\Carbon::parse(
                        $booking->lesson_time
                    );

                $lessonDateTime->setTime(
                    $lessonTime->hour,
                    $lessonTime->minute,
                    $lessonTime->second
                );

                $canPay =
                    $booking->status === 'confirmed'
                    &&
                    !$booking->paid
                    &&
                    now()->lt($lessonDateTime);


                /*
                 * Availability فقط برای همان Teacher
                 * و همان Dance Style
                 */

                $teacherAvailabilities =
                    $availabilities
                        ->get(
                            $booking->teacher_id,
                            collect()
                        )
                        ->filter(
                            function ($availability) use ($booking) {

                                return
                                    $availability->teacher_id
                                        == $booking->teacher_id
                                    &&
                                    $availability->dance_style_id
                                        == $booking->dance_style_id;
                            }
                        )
                        ->sortBy(
                            function ($availability) {

                                return
                                    $availability->available_date
                                    . ' '
                                    . $availability->start_time;
                            }
                        );


                /*
                 * Review فعلی Student برای همین Booking
                 */

                $studentReview =
                    $booking->reviews
                        ?->firstWhere(
                            'reviewer_type',
                            'student'
                        );

            @endphp



            {{-- =================================================
               REQUEST ROW
            ================================================= --}}

            <div class="student-request-row">


                {{-- TEACHER --}}
                <div>

                    <span class="student-request-label">
                        {{ __('student.teacher') }}
                    </span>

                    <div class="student-request-value">

                        {{ $booking->teacher->user->name ?? __('student.teacher') }}

                    </div>

                </div>


                {{-- DANCE --}}
                <div>

                    <span class="student-request-label">
                        {{ __('student.dance') }}
                    </span>

                    <div class="student-request-value">

                        {{ $booking->danceStyle->name ?? __('student.dance') }}

                    </div>

                </div>


                {{-- DATE --}}
                <div>

                    <span class="student-request-label">
                        {{ __('student.date') }}
                    </span>

                    <div class="student-request-value">

                        {{ \Carbon\Carbon::parse($booking->lesson_date)
                            ->locale(app()->getLocale())
                            ->translatedFormat(
                                app()->getLocale() === 'fr'
                                    ? 'd M Y'
                                    : 'M d, Y'
                            )
                        }}

                    </div>

                </div>


                {{-- TIME --}}
                <div>

                    <span class="student-request-label">
                        {{ __('student.time') }}
                    </span>

                    <div class="student-request-value">

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


                {{-- PRICE --}}
                <div>

                    <span class="student-request-label">
                        {{ __('student.price') }}
                    </span>

                    <div class="student-request-price">

                        ${{ number_format(
                            $booking->price ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                {{-- STATUS --}}
                <div class="student-request-status-area">

                    @if($booking->status === 'pending')

                        <span class="student-request-status pending">
                            {{ __('student.pending') }}
                        </span>


                    @elseif($booking->status === 'confirmed')

                        <span class="student-request-status accepted">
                            {{ __('student.accepted') }}
                        </span>


                    @elseif($booking->status === 'cancelled')

                        <span class="student-request-status refused">
                            {{ __('student.refused') }}
                        </span>


                    @elseif($booking->status === 'completed')

                        <span class="student-request-status completed">
                            {{ __('student.completed') }}
                        </span>


                    @else

                        <span class="student-request-status">

                            {{ ucfirst($booking->status) }}

                        </span>

                    @endif

                </div>



                {{-- =================================================
                   ACTIONS: MESSAGE + MORE ONLY
                ================================================= --}}

                <div class="student-request-actions">

                    @php
                        $receivedMessagesCount = $booking->messages
                            ->where('sender_id', '!=', auth()->id())
                            ->count();
                    @endphp


                    {{-- MESSAGE --}}

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary student-message-btn"
                        data-bs-toggle="collapse"
                        data-bs-target="#studentMessagesBooking{{ $booking->id }}"
                        aria-controls="studentMessagesBooking{{ $booking->id }}"
                        aria-expanded="false"
                    >
                        Message

                        @if($receivedMessagesCount > 0)

                            <span class="student-message-count">
                                {{ $receivedMessagesCount }}
                            </span>

                        @endif

                    </button>


                    {{-- ALL OTHER ACTIONS --}}

                    <div class="dropdown">

                        <button
                            type="button"
                            class="
                                btn
                                btn-sm
                                btn-outline-secondary
                                student-more-btn
                                {{ $booking->paid
                                    ? 'student-more-btn-paid'
                                    : ($canPay
                                        ? 'student-more-btn-payment-required'
                                        : '')
                                }}
                            "
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            aria-label="More actions"
                        >
                            ⋯
                        </button>


                        <div class="dropdown-menu dropdown-menu-end student-actions-dropdown">


                            {{-- EDIT / DELETE ONLY PENDING --}}

                            @if($booking->status === 'pending')

                                <button
                                    type="button"
                                    class="dropdown-item"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#editBooking{{ $booking->id }}"
                                    aria-controls="editBooking{{ $booking->id }}"
                                    aria-expanded="false"
                                >
                                    {{ __('student.edit') }}
                                </button>


                                <form
                                    method="POST"
                                    action="{{ route(
                                        'student.bookings.destroy',
                                        $booking
                                    ) }}"
                                    onsubmit="return confirm('{{ __('student.delete_confirmation') }}')"
                                >

                                    @csrf
                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="dropdown-item text-danger"
                                    >
                                        {{ __('student.delete') }}
                                    </button>

                                </form>


                                <div class="dropdown-divider"></div>

                            @endif



                            {{-- PAYMENT --}}

                            @if($canPay)

                                <a
                                    href="{{ route(
                                        'student.payments.show',
                                        $booking
                                    ) }}"
                                    class="dropdown-item text-success"
                                >

                                    {{ __('student.pay') }}

                                    ${{ number_format(
                                        (float) $booking->price,
                                        2
                                    ) }}

                                </a>

                            @endif



                            {{-- PAID --}}

                            @if($booking->paid)

                                <span class="dropdown-item-text text-success px-2 py-2">

                                    ✓ {{ __('student.paid') }}

                                </span>

                            @endif



                            {{-- REVIEW --}}

                            <button
                                type="button"
                                class="dropdown-item"
                                data-bs-toggle="collapse"
                                data-bs-target="#reviewBooking{{ $booking->id }}"
                                aria-controls="reviewBooking{{ $booking->id }}"
                                aria-expanded="false"
                            >

                                <span class="review-star">★</span>

                                {{ __('student.review_action') }}

                            </button>


                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
               MESSAGES
            ================================================= --}}

            <div
                class="collapse"
                id="studentMessagesBooking{{ $booking->id }}"
            >

                <div class="student-message-area">


                    @if($booking->messages->isNotEmpty())

                        <div class="student-conversation-top">

                            <div>

                                <div class="student-conversation-title">
                                    Conversation
                                </div>

                                <div class="student-conversation-teacher">

                                    {{ $booking->teacher->user->name
                                        ?? __('student.teacher')
                                    }}

                                </div>

                            </div>


                            <span
                                class="student-request-status
                                {{ $booking->status === 'pending'
                                    ? 'pending'
                                    : (
                                        $booking->status === 'confirmed'
                                            ? 'accepted'
                                            : (
                                                $booking->status === 'cancelled'
                                                    ? 'refused'
                                                    : (
                                                        $booking->status === 'completed'
                                                            ? 'completed'
                                                            : ''
                                                    )
                                            )
                                    )
                                }}"
                            >

                                @if($booking->status === 'pending')

                                    {{ __('student.pending') }}

                                @elseif($booking->status === 'confirmed')

                                    {{ __('student.accepted') }}

                                @elseif($booking->status === 'cancelled')

                                    {{ __('student.refused') }}

                                @elseif($booking->status === 'completed')

                                    {{ __('student.completed') }}

                                @else

                                    {{ ucfirst($booking->status) }}

                                @endif

                            </span>

                        </div>



                        {{-- CHAT HISTORY --}}

                        <div class="student-conversation-history">

                            @foreach($booking->messages as $message)

                                @php

                                    $isMine =
                                        (int) $message->sender_id ===
                                        (int) auth()->id();

                                @endphp


                                <div
                                    class="student-chat-message-row
                                    {{ $isMine ? 'mine' : 'theirs' }}"
                                >

                                    <div class="student-chat-bubble">


                                        <div class="student-chat-meta">

                                            <span>

                                                @if($isMine)

                                                    {{ app()->getLocale() === 'fr'
                                                        ? 'Vous'
                                                        : 'You'
                                                    }}

                                                @else

                                                    {{ $message->sender->name
                                                        ?? (
                                                            $booking->teacher->user->name
                                                            ?? __('student.teacher')
                                                        )
                                                    }}

                                                @endif

                                            </span>


                                            <span class="student-chat-date">

                                                @if(app()->getLocale() === 'fr')

                                                    {{ $message->created_at
                                                        ->locale('fr')
                                                        ->translatedFormat('d M Y')
                                                    }}

                                                    ·

                                                    {{ $message->created_at
                                                        ->format('H:i')
                                                    }}

                                                @else

                                                    {{ $message->created_at
                                                        ->locale('en')
                                                        ->translatedFormat('M d, Y')
                                                    }}

                                                    ·

                                                    {{ $message->created_at
                                                        ->format('g:i A')
                                                    }}

                                                @endif

                                            </span>

                                        </div>


                                        <div>
                                            {{ $message->message }}
                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @endif



                    {{-- MESSAGE FORM --}}

                    <form
                        method="POST"
                        action="{{ route(
                            'bookings.messages.store',
                            $booking
                        ) }}"
                    >

                        @csrf


                        <input
                            type="hidden"
                            name="booking_id"
                            value="{{ $booking->id }}"
                        >


                        <label
                            for="student_message_{{ $booking->id }}"
                            class="student-message-form-label"
                        >

                            @if($booking->messages->isNotEmpty())

                                {{ app()->getLocale() === 'fr'
                                    ? 'Répondre au professeur'
                                    : 'Reply to teacher'
                                }}

                            @else

                                {{ app()->getLocale() === 'fr'
                                    ? 'Message au professeur'
                                    : 'Message to teacher'
                                }}

                            @endif

                        </label>


                        <textarea
                            id="student_message_{{ $booking->id }}"
                            name="message"
                            class="student-message-textarea"
                            maxlength="3000"
                            required
                            placeholder="{{ app()->getLocale() === 'fr'
                                ? 'Écrivez votre message...'
                                : 'Write your message...'
                            }}"
                        >{{ old('booking_id') == $booking->id
                            ? old('message')
                            : ''
                        }}</textarea>


                        <small class="student-message-warning">

                            {{ app()->getLocale() === 'fr'
                                ? 'Pour votre sécurité, évitez de partager des coordonnées personnelles avant la confirmation de la réservation.'
                                : 'For your safety, avoid sharing personal contact information before the booking is confirmed.'
                            }}

                        </small>


                        <div class="student-message-action-row">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                {{ app()->getLocale() === 'fr'
                                    ? 'Envoyer'
                                    : 'Send Message'
                                }}

                            </button>

                        </div>

                    </form>

                </div>

            </div>



            {{-- =================================================
               REVIEW TEACHER
            ================================================= --}}

            <div
                class="collapse"
                id="reviewBooking{{ $booking->id }}"
            >

                <div class="student-review-area">

                    <form
                        method="POST"
                        action="{{ route(
                            'student.reviews.store',
                            $booking
                        ) }}"
                    >

                        @csrf


                        <div class="row g-3 align-items-end">


                            {{-- STARS --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    {{ __('student.rate_this_teacher') }}
                                </label>


                                <div class="student-review-stars">

                                    @for($star = 5; $star >= 1; $star--)

                                        <input
                                            type="radio"
                                            name="rating"
                                            value="{{ $star }}"
                                            id="rating_{{ $booking->id }}_{{ $star }}"
                                            {{
                                                (int) old(
                                                    'rating',
                                                    $studentReview?->rating
                                                ) === $star
                                                    ? 'checked'
                                                    : ''
                                            }}
                                        >

                                        <label
                                            for="rating_{{ $booking->id }}_{{ $star }}"
                                            title="{{ $star }} {{ __('student.stars') }}"
                                        >
                                            ★
                                        </label>

                                    @endfor

                                </div>

                            </div>



                            {{-- COMMENT --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    {{ __('student.review_action') }}
                                </label>

                                <input
                                    type="text"
                                    name="comment"
                                    class="form-control form-control-sm"
                                    maxlength="1500"
                                    value="{{ old(
                                        'comment',
                                        $studentReview?->comment
                                    ) }}"
                                    placeholder="{{ __('student.review_teacher_placeholder') }}"
                                >

                            </div>



                            {{-- SAVE --}}
                            <div class="col-md-2">

                                <button
                                    type="submit"
                                    class="btn btn-warning btn-sm w-100"
                                >

                                    @if($studentReview)

                                        {{ __('student.update') }}

                                    @else

                                        {{ __('student.save') }}

                                    @endif

                                </button>

                            </div>

                        </div>


                        @if($studentReview)

                            <div class="student-review-existing">

                                {{ __('student.current_rating') }}

                                {{ str_repeat(
                                    '★',
                                    (int) $studentReview->rating
                                ) }}

                                {{ str_repeat(
                                    '☆',
                                    5 - (int) $studentReview->rating
                                ) }}

                            </div>

                        @endif

                    </form>

                </div>

            </div>



            {{-- =================================================
               EDIT PENDING REQUEST
            ================================================= --}}

            @if($booking->status === 'pending')

                <div
                    class="collapse"
                    id="editBooking{{ $booking->id }}"
                >

                    <div class="student-request-edit">


                        @if($teacherAvailabilities->count())


                            <form
                                method="POST"
                                action="{{ route(
                                    'student.bookings.update',
                                    $booking
                                ) }}"
                            >

                                @csrf
                                @method('PUT')


                                <div class="row g-2 align-items-end">


                                    <div class="col-md-9">

                                        <label class="form-label">
                                            {{ __('student.available_times') }}
                                        </label>


                                        <select
                                            name="availability_id"
                                            class="form-select form-select-sm"
                                            required
                                        >

                                            <option value="">
                                                {{ __('student.select_another_time') }}
                                            </option>


                                            @foreach(
                                                $teacherAvailabilities
                                                as $availability
                                            )

                                                <option
                                                    value="{{ $availability->id }}"
                                                >

                                                    {{ \Carbon\Carbon::parse(
                                                        $availability->available_date
                                                    )
                                                    ->locale(app()->getLocale())
                                                    ->translatedFormat(
                                                        app()->getLocale() === 'fr'
                                                            ? 'd M Y'
                                                            : 'M d, Y'
                                                    ) }}

                                                    —

                                                    {{ \Carbon\Carbon::parse(
                                                        $availability->start_time
                                                    )->format('H:i') }}

                                                    {{ __('student.to') }}

                                                    {{ \Carbon\Carbon::parse(
                                                        $availability->end_time
                                                    )->format('H:i') }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    <div class="col-md-3">

                                        <button
                                            type="submit"
                                            class="btn btn-primary btn-sm w-100"
                                        >
                                            {{ __('student.save_changes') }}
                                        </button>

                                    </div>

                                </div>

                            </form>


                        @else


                            <small class="text-muted">

                                {{ __('student.no_other_availability') }}

                            </small>


                        @endif

                    </div>

                </div>

            @endif


        @empty


            <div class="text-center py-4">

                <h5 class="mb-2">
                    {{ __('student.no_booking_requests') }}
                </h5>


                <p class="text-muted mb-3">
                    {{ __('student.find_first_lesson') }}
                </p>


                <a
                    href="{{ route('student.teachers') }}"
                    class="btn btn-primary"
                >
                    {{ __('student.find_teacher') }}
                </a>

            </div>


        @endforelse

    </div>

</div>



<script>

document.addEventListener('DOMContentLoaded', function () {

    document
        .querySelectorAll('[id^="studentMessagesBooking"]')
        .forEach(function (messagePanel) {

            messagePanel.addEventListener(
                'shown.bs.collapse',
                function () {

                    const history =
                        messagePanel.querySelector(
                            '.student-conversation-history'
                        );

                    if (history) {

                        history.scrollTop =
                            history.scrollHeight;

                    }

                }
            );

        });


    @if($errors->any() && old('booking_id'))

        const messagePanel =
            document.getElementById(
                'studentMessagesBooking{{ old('booking_id') }}'
            );


        if (
            messagePanel
            &&
            typeof bootstrap !== 'undefined'
        ) {

            bootstrap.Collapse
                .getOrCreateInstance(
                    messagePanel,
                    {
                        toggle: false
                    }
                )
                .show();

        }

    @endif

});

</script>


@endsection