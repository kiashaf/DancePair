@extends('admin.layout')

@section('title', 'Payment #' . $payment->id)
@section('page-title', 'Payment Details')

@section('content')

@php

    $booking = $payment->booking;
    $student = $payment->student;
    $teacher = $payment->teacher;

@endphp


<style>

.payment-detail-page {
    max-width: 1250px;

    margin: 0 auto;

    display: flex;
    flex-direction: column;

    gap: 18px;

    padding-bottom: 40px;
}


/* =========================================================
   HEADER
========================================================= */

.payment-detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    gap: 18px;
}

.payment-detail-header h2 {
    margin: 0;

    font-size: 23px;
    font-weight: 850;

    color: #0F172A;
}

.payment-detail-header p {
    margin: 4px 0 0;

    font-size: 10px;

    color: #64748B;
}

.payment-back-btn {
    min-height: 40px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 14px;

    border: 1px solid #CBD5E1;
    border-radius: 10px;

    background: #FFFFFF;

    color: #334155;

    text-decoration: none;

    font-size: 10px;
    font-weight: 750;
}


/* =========================================================
   SUMMARY
========================================================= */

.payment-detail-summary {
    display: grid;

    grid-template-columns:
        minmax(250px, 1.6fr)
        repeat(4, minmax(120px, .7fr));

    overflow: hidden;

    border: 1px solid #BBF7D0;
    border-radius: 18px;

    background: #FFFFFF;
}

.payment-summary-main {
    padding: 21px;

    background: #F0FDF4;
}

.payment-summary-main span {
    display: inline-flex;

    padding: 5px 9px;

    border-radius: 999px;

    background: #DCFCE7;

    color: #047857;

    font-size: 7.5px;
    font-weight: 850;

    text-transform: uppercase;
}

.payment-summary-main span.refunded {
    background: #FEE2E2;

    color: #B91C1C;
}

.payment-summary-main h3 {
    margin: 10px 0 4px;

    font-size: 21px;
    font-weight: 850;

    color: #0F172A;
}

.payment-summary-main p {
    margin: 0;

    font-size: 9px;

    color: #64748B;
}

.payment-summary-item {
    padding: 17px 15px;

    display: flex;
    flex-direction: column;
    justify-content: center;

    border-left: 1px solid #D8F2E3;
}

.payment-summary-item span {
    margin-bottom: 5px;

    font-size: 7px;
    font-weight: 850;

    text-transform: uppercase;

    color: #94A3B8;
}

.payment-summary-item strong {
    font-size: 11px;
    font-weight: 800;

    color: #0F172A;

    overflow-wrap: anywhere;
}

.payment-summary-item.amount strong {
    font-size: 18px;
}

.platform-money {
    color: #DC2626 !important;
}

.teacher-money {
    color: #047857 !important;
}


/* =========================================================
   GRID / CARDS
========================================================= */

.payment-detail-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0,1fr));

    gap: 16px;
}

.payment-detail-card {
    padding: 20px;

    border: 1px solid #E2E8F0;
    border-radius: 15px;

    background: #FFFFFF;

    box-shadow:
        0 5px 16px rgba(15,23,42,.03);
}

.payment-detail-card.full {
    grid-column: 1 / -1;
}

.payment-detail-card.student {
    border-top: 3px solid #38BDF8;
}

.payment-detail-card.teacher {
    border-top: 3px solid #A78BFA;
}

.payment-detail-card.booking {
    border-top: 3px solid #6366F1;
}

.payment-detail-card.transaction {
    border-top: 3px solid #10B981;
}


/* =========================================================
   CARD HEADER
========================================================= */

.payment-card-header {
    margin-bottom: 16px;
    padding-bottom: 11px;

    border-bottom: 1px solid #EEF2F7;
}

.payment-card-header h4 {
    margin: 0;

    font-size: 14px;
    font-weight: 850;

    color: #0F172A;
}

.payment-card-header p {
    margin: 3px 0 0;

    font-size: 8px;

    color: #94A3B8;
}


/* =========================================================
   PERSON
========================================================= */

.payment-person-row {
    display: flex;
    align-items: center;

    gap: 11px;

    margin-bottom: 16px;
}

.payment-avatar {
    width: 42px;
    height: 42px;

    flex: 0 0 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    font-size: 14px;
    font-weight: 850;
}

.student .payment-avatar {
    background: #E0F2FE;
    color: #0369A1;
}

.teacher .payment-avatar {
    background: #F3E8FF;
    color: #7E22CE;
}

.payment-person-row strong {
    display: block;

    font-size: 11px;
    font-weight: 850;

    color: #0F172A;
}

.payment-person-row small {
    display: block;

    margin-top: 2px;

    font-size: 8.5px;

    color: #64748B;
}


/* =========================================================
   INFO
========================================================= */

.payment-info-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0,1fr));

    gap: 15px 20px;
}

.payment-info-item span {
    display: block;

    margin-bottom: 4px;

    font-size: 7px;
    font-weight: 850;

    text-transform: uppercase;
    letter-spacing: .45px;

    color: #94A3B8;
}

.payment-info-item strong {
    display: block;

    font-size: 10.5px;
    font-weight: 750;

    color: #1E293B;

    overflow-wrap: anywhere;
}


/* =========================================================
   FINANCIAL SPLIT
========================================================= */

.payment-finance-grid {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0,1fr));

    gap: 10px;

    margin-top: 16px;
}

.payment-finance-box {
    padding: 14px;

    border: 1px solid #E2E8F0;
    border-radius: 11px;

    background: #F8FAFC;
}

.payment-finance-box span {
    display: block;

    margin-bottom: 5px;

    font-size: 7px;
    font-weight: 850;

    text-transform: uppercase;

    color: #94A3B8;
}

.payment-finance-box strong {
    font-size: 14px;
    font-weight: 850;
}


/* =========================================================
   BOOKING BUTTON
========================================================= */

.payment-booking-link {
    margin-top: 15px;

    display: inline-flex;

    min-height: 38px;

    align-items: center;
    justify-content: center;

    padding: 0 14px;

    border-radius: 10px;

    background: #4F46E5;

    color: #FFFFFF;

    text-decoration: none;

    font-size: 9.5px;
    font-weight: 800;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1050px) {

    .payment-detail-summary {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }

    .payment-summary-main {
        grid-column: 1 / -1;
    }
}

@media(max-width: 750px) {

    .payment-detail-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .payment-detail-grid {
        grid-template-columns: 1fr;
    }

    .payment-detail-card.full {
        grid-column: auto;
    }

    .payment-detail-summary,
    .payment-info-grid,
    .payment-finance-grid {
        grid-template-columns: 1fr;
    }

    .payment-summary-main {
        grid-column: auto;
    }
}

</style>


<div class="payment-detail-page">


{{-- HEADER --}}

<div class="payment-detail-header">

    <div>

        <h2>
            Payment #{{ $payment->id }}
        </h2>

        <p>
            Complete transaction, booking and client information.
        </p>

    </div>


    <a
        href="{{ route('admin.payments') }}"
        class="payment-back-btn"
    >
        ← Back to Payments
    </a>

</div>



{{-- SUMMARY --}}

<div class="payment-detail-summary">

    <div class="payment-summary-main">


        <span class="{{ $payment->refunded_at ? 'refunded' : '' }}">

            {{ $payment->refunded_at
                ? 'Refunded'
                : ucfirst($payment->status ?? 'Paid')
            }}

        </span>


        <h3>
            {{ ucfirst($payment->payment_provider ?? 'Payment') }}
        </h3>


        <p>
            {{ $payment->transaction_id ?? 'No transaction ID' }}
        </p>

    </div>



    <div class="payment-summary-item amount">

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



    <div class="payment-summary-item">

        <span>
            DancePair
        </span>

        <strong class="platform-money">
            ${{ number_format(
                (float) $payment->platform_fee,
                2
            ) }}
        </strong>

    </div>



    <div class="payment-summary-item">

        <span>
            Teacher
        </span>

        <strong class="teacher-money">
            ${{ number_format(
                (float) $payment->teacher_amount,
                2
            ) }}
        </strong>

    </div>



    <div class="payment-summary-item">

        <span>
            Currency
        </span>

        <strong>
            {{ strtoupper($payment->currency ?? 'CAD') }}
        </strong>

    </div>

</div>



<div class="payment-detail-grid">


{{-- STUDENT --}}

<div class="payment-detail-card student">

    <div class="payment-card-header">

        <h4>
            Student
        </h4>

        <p>
            Client who made the payment
        </p>

    </div>


    <div class="payment-person-row">

        <div class="payment-avatar">

            {{ strtoupper(
                substr(
                    $student?->user?->name ?? 'S',
                    0,
                    1
                )
            ) }}

        </div>


        <div>

            <strong>
                {{ $student?->user?->name ?? '—' }}
            </strong>

            <small>
                {{ $student?->user?->email ?? '—' }}
            </small>

        </div>

    </div>


    <div class="payment-info-grid">

        <div class="payment-info-item">

            <span>
                Student ID
            </span>

            <strong>
                #{{ $payment->student_id }}
            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                City
            </span>

            <strong>
                {{ $student?->city ?? '—' }}
            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Province
            </span>

            <strong>
                {{ $student?->province ?? '—' }}
            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Country
            </span>

            <strong>
                {{ $student?->country ?? '—' }}
            </strong>

        </div>

    </div>

</div>



{{-- TEACHER --}}

<div class="payment-detail-card teacher">

    <div class="payment-card-header">

        <h4>
            Teacher
        </h4>

        <p>
            Teacher receiving this lesson payment
        </p>

    </div>


    <div class="payment-person-row">

        <div class="payment-avatar">

            {{ strtoupper(
                substr(
                    $teacher?->user?->name ?? 'T',
                    0,
                    1
                )
            ) }}

        </div>


        <div>

            <strong>
                {{ $teacher?->user?->name ?? '—' }}
            </strong>

            <small>
                {{ $teacher?->user?->email ?? '—' }}
            </small>

        </div>

    </div>


    <div class="payment-info-grid">

        <div class="payment-info-item">

            <span>
                Teacher ID
            </span>

            <strong>
                #{{ $payment->teacher_id }}
            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                City
            </span>

            <strong>
                {{ $teacher?->city ?? '—' }}
            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Province
            </span>

            <strong>
                {{ $teacher?->province ?? '—' }}
            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Country
            </span>

            <strong>
                {{ $teacher?->country ?? '—' }}
            </strong>

        </div>

    </div>

</div>



{{-- TRANSACTION --}}

<div class="payment-detail-card transaction">

    <div class="payment-card-header">

        <h4>
            Transaction
        </h4>

        <p>
            Payment provider and transaction data
        </p>

    </div>


    <div class="payment-info-grid">

        <div class="payment-info-item">

            <span>
                Payment ID
            </span>

            <strong>
                #{{ $payment->id }}
            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Provider
            </span>

            <strong>
                {{ ucfirst($payment->payment_provider ?? '—') }}
            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Transaction ID
            </span>

            <strong>
                {{ $payment->transaction_id ?? '—' }}
            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Status
            </span>

            <strong>
                {{ ucfirst($payment->status ?? '—') }}
            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Paid At
            </span>

            <strong>

                {{ optional(
                    $payment->paid_at
                )->format(
                    'M d, Y · g:i A'
                ) ?? '—' }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Refunded At
            </span>

            <strong>

                {{ optional(
                    $payment->refunded_at
                )->format(
                    'M d, Y · g:i A'
                ) ?? 'Not refunded' }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Created
            </span>

            <strong>

                {{ optional(
                    $payment->created_at
                )->format(
                    'M d, Y · g:i A'
                ) }}

            </strong>

        </div>

    </div>


    <div class="payment-finance-grid">

        <div class="payment-finance-box">

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


        <div class="payment-finance-box">

            <span>
                DancePair Revenue
            </span>

            <strong class="platform-money">
                ${{ number_format(
                    (float) $payment->platform_fee,
                    2
                ) }}
            </strong>

        </div>


        <div class="payment-finance-box">

            <span>
                Teacher Earnings
            </span>

            <strong class="teacher-money">
                ${{ number_format(
                    (float) $payment->teacher_amount,
                    2
                ) }}
            </strong>

        </div>

    </div>

</div>



{{-- BOOKING --}}

<div class="payment-detail-card booking">

    <div class="payment-card-header">

        <h4>
            Related Booking
        </h4>

        <p>
            Lesson connected to this transaction
        </p>

    </div>


    @if($booking)

        <div class="payment-info-grid">

            <div class="payment-info-item">

                <span>
                    Booking ID
                </span>

                <strong>
                    #{{ $booking->id }}
                </strong>

            </div>


            <div class="payment-info-item">

                <span>
                    Dance Style
                </span>

                <strong>
                    {{ $booking->danceStyle?->name ?? '—' }}
                </strong>

            </div>


            <div class="payment-info-item">

                <span>
                    Lesson Date
                </span>

                <strong>

                    {{ $booking->lesson_date
                        ? \Carbon\Carbon::parse(
                            $booking->lesson_date
                        )->format(
                            'M d, Y'
                        )
                        : '—'
                    }}

                </strong>

            </div>


            <div class="payment-info-item">

                <span>
                    Booking Status
                </span>

                <strong>
                    {{ ucfirst($booking->status ?? '—') }}
                </strong>

            </div>


            <div class="payment-info-item">

                <span>
                    Lesson Price
                </span>

                <strong>
                    ${{ number_format(
                        (float) ($booking->price ?? 0),
                        2
                    ) }}
                </strong>

            </div>

        </div>


        <a
            href="{{ route(
                'admin.bookings.show',
                $booking
            ) }}"
            class="payment-booking-link"
        >
            View Full Booking →
        </a>


    @else

        No booking is connected to this payment.

    @endif

</div>


</div>

</div>

@endsection