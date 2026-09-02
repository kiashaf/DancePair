@extends('admin.layout')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')

@section('content')

@php
    $start = \Carbon\Carbon::parse($booking->lesson_time);

    $end = $start
        ->copy()
        ->addMinutes($booking->duration ?? 60);

    $payment = $booking->payment;
@endphp


<style>

/* =========================================================
   PAGE
========================================================= */

.booking-detail-page {
    max-width: 1250px;
    margin: 0 auto;
}


/* =========================================================
   HEADER
========================================================= */

.booking-detail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    margin-bottom: 18px;
}

.booking-detail-header h2 {
    margin: 0;

    font-size: 22px;
    font-weight: 800;

    color: #0F172A;
}

.booking-detail-header p {
    margin: 4px 0 0;

    font-size: 10px;

    color: #64748B;
}

.booking-back {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 38px;
    padding: 0 14px;

    border: 1px solid #CBD5E1;
    border-radius: 9px;

    background: #FFFFFF;

    color: #334155;

    text-decoration: none;

    font-size: 10px;
    font-weight: 700;
}

.booking-back:hover {
    background: #F8FAFC;
    color: #0F172A;
}


/* =========================================================
   SUMMARY
========================================================= */

.booking-summary {
    display: grid;

    grid-template-columns:
        minmax(260px, 1.8fr)
        repeat(4, minmax(110px, .7fr));

    align-items: stretch;

    margin-bottom: 18px;

    overflow: hidden;

    border: 1px solid #DDD6FE;
    border-radius: 16px;

    background: #FFFFFF;
}

.booking-summary-main {
    padding: 19px 22px;

    background: #F8F6FF;
}

.booking-summary-status {
    display: inline-flex;

    margin-bottom: 8px;

    padding: 4px 8px;

    border-radius: 999px;

    font-size: 7.5px;
    font-weight: 800;

    text-transform: uppercase;
}

.booking-summary-status.pending {
    background: #FEF3C7;
    color: #92400E;
}

.booking-summary-status.confirmed {
    background: #EDE9FE;
    color: #6D28D9;
}

.booking-summary-status.completed {
    background: #D1FAE5;
    color: #047857;
}

.booking-summary-status.cancelled {
    background: #FEE2E2;
    color: #B91C1C;
}

.booking-summary-main h3 {
    margin: 0;

    font-size: 21px;
    font-weight: 850;

    color: #0F172A;
}

.booking-summary-main p {
    margin: 5px 0 0;

    font-size: 9.5px;

    color: #64748B;
}

.summary-item {
    padding: 18px 15px;

    display: flex;
    flex-direction: column;
    justify-content: center;

    border-left: 1px solid #E8E4F2;
}

.summary-item span {
    margin-bottom: 5px;

    font-size: 7px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .45px;

    color: #94A3B8;
}

.summary-item strong {
    font-size: 11px;
    font-weight: 800;

    color: #0F172A;

    white-space: nowrap;
}

.summary-item.price strong {
    font-size: 18px;
}

.summary-item .paid {
    color: #047857;
}

.summary-item .unpaid {
    color: #C2410C;
}


/* =========================================================
   GRID
========================================================= */

.booking-content-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 16px;
}


/* =========================================================
   CARD
========================================================= */

.detail-card {
    padding: 20px;

    border: 1px solid #E2E8F0;
    border-radius: 14px;

    background: #FFFFFF;

    box-shadow:
        0 4px 14px rgba(15,23,42,.025);
}

.detail-card.full {
    grid-column: 1 / -1;
}

.detail-card.student {
    border-top: 3px solid #38BDF8;
}

.detail-card.teacher {
    border-top: 3px solid #A78BFA;
}

.detail-card.lesson {
    border-top: 3px solid #6366F1;
}

.detail-card.finance {
    border-top: 3px solid #10B981;
}

.detail-card.payment {
    border-top: 3px solid #F59E0B;
}


/* =========================================================
   CARD HEADER
========================================================= */

.detail-card-header {
    margin-bottom: 17px;
    padding-bottom: 12px;

    border-bottom: 1px solid #EEF2F7;
}

.detail-card-header h4 {
    margin: 0;

    font-size: 14px;
    font-weight: 800;

    color: #0F172A;
}

.detail-card-header p {
    margin: 3px 0 0;

    font-size: 8px;

    color: #94A3B8;
}


/* =========================================================
   PERSON
========================================================= */

.person-row {
    display: flex;
    align-items: center;

    gap: 11px;

    margin-bottom: 17px;
}

.person-avatar {
    width: 40px;
    height: 40px;

    flex: 0 0 40px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    font-size: 14px;
    font-weight: 800;
}

.student .person-avatar {
    background: #E0F2FE;
    color: #0369A1;
}

.teacher .person-avatar {
    background: #F3E8FF;
    color: #7E22CE;
}

.person-row strong {
    display: block;

    font-size: 11px;
    font-weight: 800;

    color: #0F172A;
}

.person-row small {
    display: block;

    margin-top: 2px;

    font-size: 8.5px;

    color: #64748B;
}


/* =========================================================
   INFO
========================================================= */

.detail-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0,1fr));

    gap: 15px 22px;
}

.detail-item span {
    display: block;

    margin-bottom: 4px;

    font-size: 7px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .45px;

    color: #94A3B8;
}

.detail-item strong {
    display: block;

    font-size: 10.5px;
    font-weight: 700;

    color: #1E293B;

    overflow-wrap: anywhere;
}


/* =========================================================
   LESSON TIME
========================================================= */

.lesson-time-row {
    grid-column: 1 / -1;

    display: flex;
    align-items: center;

    gap: 12px;

    margin-bottom: 4px;
}

.lesson-time {
    flex: 1;

    padding: 12px;

    border-radius: 10px;

    background: #F8FAFC;

    border: 1px solid #E2E8F0;
}

.lesson-time span {
    display: block;

    margin-bottom: 3px;

    font-size: 7px;
    font-weight: 800;

    text-transform: uppercase;

    color: #94A3B8;
}

.lesson-time strong {
    font-size: 13px;
    font-weight: 800;

    color: #0F172A;
}

.lesson-duration {
    color: #7C3AED;

    font-size: 9px;
    font-weight: 800;

    white-space: nowrap;
}


/* =========================================================
   FINANCIAL SUMMARY
========================================================= */

.finance-summary {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 10px;

    margin-top: 17px;
}

.finance-box {
    padding: 13px;

    border: 1px solid #E2E8F0;
    border-radius: 10px;

    background: #F8FAFC;
}

.finance-box span {
    display: block;

    margin-bottom: 5px;

    font-size: 7px;
    font-weight: 800;

    text-transform: uppercase;

    color: #94A3B8;
}

.finance-box strong {
    font-size: 13px;
    font-weight: 850;
}

.finance-box.gross strong {
    color: #0F172A;
}

.finance-box.dancepair strong {
    color: #DC2626;
}

.finance-box.teacher-share strong {
    color: #047857;
}


/* =========================================================
   PAYMENT
========================================================= */

.payment-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 10px;
}

.payment-item {
    padding: 12px;

    border: 1px solid #E8EDF3;
    border-radius: 9px;

    background: #FCFDFE;
}

.payment-item span {
    display: block;

    margin-bottom: 5px;

    font-size: 7px;
    font-weight: 800;

    text-transform: uppercase;

    color: #94A3B8;
}

.payment-item strong {
    display: block;

    font-size: 9.5px;
    font-weight: 700;

    color: #1E293B;

    overflow-wrap: anywhere;
}

.text-paid {
    color: #047857 !important;
}

.text-unpaid {
    color: #C2410C !important;
}

.text-danger {
    color: #DC2626 !important;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1050px) {

    .booking-summary {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }

    .booking-summary-main {
        grid-column: 1 / -1;
    }

    .summary-item {
        border-top: 1px solid #E8E4F2;
    }

    .payment-grid {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }
}

@media(max-width: 800px) {

    .booking-content-grid {
        grid-template-columns: 1fr;
    }

    .detail-card.full {
        grid-column: auto;
    }
}

@media(max-width: 600px) {

    .booking-detail-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .booking-summary {
        grid-template-columns: 1fr;
    }

    .booking-summary-main {
        grid-column: auto;
    }

    .summary-item {
        border-left: 0;
    }

    .detail-grid,
    .finance-summary,
    .payment-grid {
        grid-template-columns: 1fr;
    }

    .lesson-time-row {
        align-items: stretch;
        flex-direction: column;
    }
}

</style>


<div class="booking-detail-page">


    {{-- HEADER --}}
    <div class="booking-detail-header">

        <div>

            <h2>
                Booking #{{ $booking->id }}
            </h2>

            <p>
                Lesson and transaction details.
            </p>

        </div>

        <a
            href="{{ route('admin.bookings') }}"
            class="booking-back"
        >
            ← Back to Bookings
        </a>

    </div>



    {{-- SUMMARY --}}
    <div class="booking-summary">

        <div class="booking-summary-main">

            <span
                class="
                    booking-summary-status
                    {{ $booking->status }}
                "
            >
                {{ ucfirst($booking->status) }}
            </span>

            <h3>
                {{ $booking->danceStyle?->name ?? 'Dance Lesson' }}
            </h3>

            <p>
                Booking #{{ $booking->id }}
            </p>

        </div>


        <div class="summary-item">

            <span>Date</span>

            <strong>
                {{ \Carbon\Carbon::parse(
                    $booking->lesson_date
                )->format('M d, Y') }}
            </strong>

        </div>


        <div class="summary-item">

            <span>Time</span>

            <strong>
                {{ $start->format('g:i A') }}
            </strong>

        </div>


        <div class="summary-item">

            <span>Duration</span>

            <strong>
                {{ $booking->duration ?? 60 }} min
            </strong>

        </div>


        <div class="summary-item price">

            <span>Price</span>

            <strong>
                ${{ number_format(
                    (float) $booking->price,
                    2
                ) }}
            </strong>

            <small
                class="{{ $booking->paid
                    ? 'paid'
                    : 'unpaid'
                }}"
            >
                {{ $booking->paid
                    ? 'Paid'
                    : 'Unpaid'
                }}
            </small>

        </div>

    </div>



    <div class="booking-content-grid">


        {{-- STUDENT --}}
        <div class="detail-card student">

            <div class="detail-card-header">

                <h4>
                    Student
                </h4>

                <p>
                    Client information
                </p>

            </div>


            <div class="person-row">

                <div class="person-avatar">

                    {{ strtoupper(
                        substr(
                            $booking->student?->user?->name ?? 'S',
                            0,
                            1
                        )
                    ) }}

                </div>


                <div>

                    <strong>
                        {{ $booking->student?->user?->name ?? '—' }}
                    </strong>

                    <small>
                        {{ $booking->student?->user?->email ?? '—' }}
                    </small>

                </div>

            </div>


            <div class="detail-grid">

                <div class="detail-item">

                    <span>Student ID</span>

                    <strong>
                        #{{ $booking->student_id }}
                    </strong>

                </div>


                <div class="detail-item">

                    <span>City</span>

                    <strong>
                        {{ $booking->student?->city ?? '—' }}
                    </strong>

                </div>


                <div class="detail-item">

                    <span>Province</span>

                    <strong>
                        {{ $booking->student?->province ?? '—' }}
                    </strong>

                </div>


                <div class="detail-item">

                    <span>Country</span>

                    <strong>
                        {{ $booking->student?->country ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>



        {{-- TEACHER --}}
        <div class="detail-card teacher">

            <div class="detail-card-header">

                <h4>
                    Teacher
                </h4>

                <p>
                    Teacher information
                </p>

            </div>


            <div class="person-row">

                <div class="person-avatar">

                    {{ strtoupper(
                        substr(
                            $booking->teacher?->user?->name ?? 'T',
                            0,
                            1
                        )
                    ) }}

                </div>


                <div>

                    <strong>
                        {{ $booking->teacher?->user?->name ?? '—' }}
                    </strong>

                    <small>
                        {{ $booking->teacher?->user?->email ?? '—' }}
                    </small>

                </div>

            </div>


            <div class="detail-grid">

                <div class="detail-item">

                    <span>Teacher ID</span>

                    <strong>
                        #{{ $booking->teacher_id }}
                    </strong>

                </div>


                <div class="detail-item">

                    <span>City</span>

                    <strong>
                        {{ $booking->teacher?->city ?? '—' }}
                    </strong>

                </div>


                <div class="detail-item">

                    <span>Province</span>

                    <strong>
                        {{ $booking->teacher?->province ?? '—' }}
                    </strong>

                </div>


                <div class="detail-item">

                    <span>Country</span>

                    <strong>
                        {{ $booking->teacher?->country ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>



        {{-- LESSON --}}
        <div class="detail-card lesson">

            <div class="detail-card-header">

                <h4>
                    Lesson
                </h4>

                <p>
                    Lesson schedule and status
                </p>

            </div>


            <div class="detail-grid">

                <div class="lesson-time-row">

                    <div class="lesson-time">

                        <span>Start</span>

                        <strong>
                            {{ $start->format('g:i A') }}
                        </strong>

                    </div>


                    <div class="lesson-duration">
                        {{ $booking->duration ?? 60 }} min
                    </div>


                    <div class="lesson-time">

                        <span>End</span>

                        <strong>
                            {{ $end->format('g:i A') }}
                        </strong>

                    </div>

                </div>


                <div class="detail-item">

                    <span>Dance Style</span>

                    <strong>
                        {{ $booking->danceStyle?->name ?? '—' }}
                    </strong>

                </div>


                <div class="detail-item">

                    <span>Lesson Date</span>

                    <strong>
                        {{ \Carbon\Carbon::parse(
                            $booking->lesson_date
                        )->format('M d, Y') }}
                    </strong>

                </div>


                <div class="detail-item">

                    <span>Status</span>

                    <strong>
                        {{ ucfirst($booking->status) }}
                    </strong>

                </div>


                <div class="detail-item">

                    <span>Created</span>

                    <strong>
                        {{ optional(
                            $booking->created_at
                        )->format('M d, Y · g:i A') }}
                    </strong>

                </div>


                <div class="detail-item">

                    <span>Last Updated</span>

                    <strong>
                        {{ optional(
                            $booking->updated_at
                        )->format('M d, Y · g:i A') }}
                    </strong>

                </div>

            </div>

        </div>



        {{-- FINANCE --}}
        <div class="detail-card finance">

            <div class="detail-card-header">

                <h4>
                    Financials
                </h4>

                <p>
                    Price and revenue split
                </p>

            </div>


            <div class="detail-grid">

                <div class="detail-item">

                    <span>Lesson Price</span>

                    <strong>
                        ${{ number_format(
                            (float) $booking->price,
                            2
                        ) }}
                    </strong>

                </div>


                <div class="detail-item">

                    <span>Payment Status</span>

                    <strong
                        class="{{ $booking->paid
                            ? 'text-paid'
                            : 'text-unpaid'
                        }}"
                    >
                        {{ $booking->paid
                            ? 'Paid'
                            : 'Unpaid'
                        }}
                    </strong>

                </div>

            </div>


            @if($payment)

                <div class="finance-summary">

                    <div class="finance-box gross">

                        <span>
                            Gross
                        </span>

                        <strong>
                            ${{ number_format(
                                (float) $payment->amount,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="finance-box dancepair">

                        <span>
                            DancePair
                        </span>

                        <strong>
                            ${{ number_format(
                                (float) $payment->platform_fee,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="finance-box teacher-share">

                        <span>
                            Teacher
                        </span>

                        <strong>
                            ${{ number_format(
                                (float) $payment->teacher_amount,
                                2
                            ) }}
                        </strong>

                    </div>

                </div>

            @endif

        </div>



        {{-- PAYMENT --}}
        <div class="detail-card payment full">

            <div class="detail-card-header">

                <h4>
                    Payment Details
                </h4>

                <p>
                    Transaction information
                </p>

            </div>


            @if($payment)

                <div class="payment-grid">

                    <div class="payment-item">

                        <span>Payment ID</span>

                        <strong>
                            #{{ $payment->id }}
                        </strong>

                    </div>


                    <div class="payment-item">

                        <span>Provider</span>

                        <strong>
                            Stripe
                        </strong>

                    </div>


                    <div class="payment-item">

                        <span>Paid At</span>

                        <strong>
                            {{ optional(
                                $payment->created_at
                            )->format('M d, Y · g:i A') }}
                        </strong>

                    </div>


                    <div class="payment-item">

                        <span>Amount</span>

                        <strong class="text-paid">
                            ${{ number_format(
                                (float) $payment->amount,
                                2
                            ) }}
                        </strong>

                    </div>


                    @if(!empty($payment->stripe_session_id))

                        <div class="payment-item">

                            <span>Stripe Session</span>

                            <strong>
                                {{ $payment->stripe_session_id }}
                            </strong>

                        </div>

                    @endif


                    @if(!empty($payment->stripe_payment_intent_id))

                        <div class="payment-item">

                            <span>Payment Intent</span>

                            <strong>
                                {{ $payment->stripe_payment_intent_id }}
                            </strong>

                        </div>

                    @endif


                    @if(!empty($payment->status))

                        <div class="payment-item">

                            <span>Transaction Status</span>

                            <strong class="text-paid">
                                {{ ucfirst($payment->status) }}
                            </strong>

                        </div>

                    @endif

                </div>

            @else

                <div class="payment-item">

                    <span>Payment</span>

                    <strong class="text-unpaid">
                        No payment transaction for this booking.
                    </strong>

                </div>

            @endif

        </div>


    </div>

</div>

@endsection