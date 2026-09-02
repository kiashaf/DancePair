@extends('student.layout')

@section('title', __('student.payment'))
@section('page-title', __('student.payment'))

@section('content')

<style>

/* =========================================================
   PAYMENT PAGE
========================================================= */

.payment-wrapper {
    max-width: 820px;
    margin: 0 auto;
}

.payment-card {
    background: #EAF6FF;
    border: 1px solid #CDE9F8;
    border-radius: 22px;
    padding: 28px;
}


/* =========================================================
   HEADER
========================================================= */

.payment-header {
    margin-bottom: 24px;
}

.payment-header h3 {
    margin: 0 0 5px;
    font-size: 25px;
    font-weight: 700;
}

.payment-header p {
    margin: 0;
    color: #6B7280;
    font-size: 13px;
}


/* =========================================================
   LESSON DETAILS
========================================================= */

.payment-details {
    background: rgba(255,255,255,.78);
    border: 1px solid #CDE9F8;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
}

.payment-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;

    padding: 11px 0;
    border-bottom: 1px solid #E5E7EB;
}

.payment-row:last-child {
    border-bottom: 0;
}

.payment-label {
    color: #6B7280;
    font-size: 12px;
}

.payment-value {
    color: #1F2937;
    font-size: 13px;
    font-weight: 600;
    text-align: right;
}


/* =========================================================
   TOTAL
========================================================= */

.payment-total {
    display: flex;
    align-items: center;
    justify-content: space-between;

    background: #FFFFFF;
    border: 1px solid #B9DFF2;
    border-radius: 14px;

    padding: 18px 20px;
    margin-bottom: 24px;
}

.payment-total-label {
    font-size: 14px;
    font-weight: 700;
}

.payment-total-amount {
    font-size: 27px;
    font-weight: 800;
    color: #0369A1;
}

.payment-total-currency {
    font-size: 11px;
    color: #64748B;
    font-weight: 700;
}


/* =========================================================
   PAYMENT METHODS
========================================================= */

.payment-method-section {
    margin-bottom: 24px;
}

.payment-method-title {
    margin-bottom: 12px;
    font-size: 14px;
    font-weight: 700;
    color: #1F2937;
}

.payment-method-options {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}


/* Hide default ugly radio */

.payment-method-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}


/* Card */

.payment-method-option {
    position: relative;
    display: block;
    cursor: pointer;
}

.payment-method-box {
    height: 105px;

    display: flex;
    flex-direction: column;
    justify-content: space-between;

    padding: 15px;

    background: #FFFFFF;

    border: 2px solid #DCEAF3;
    border-radius: 14px;

    transition:
        border-color .15s ease,
        box-shadow .15s ease,
        transform .15s ease;
}

.payment-method-option:hover .payment-method-box {
    border-color: #8ACBEA;
    transform: translateY(-1px);
}

.payment-method-option input:checked + .payment-method-box {
    border-color: #0284C7;
    box-shadow: 0 0 0 3px rgba(2,132,199,.10);
}


/* selected mark */

.payment-method-check {
    position: absolute;

    width: 18px;
    height: 18px;

    top: 9px;
    right: 9px;

    border-radius: 50%;

    background: #FFFFFF;
    border: 1px solid #CBD5E1;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 11px;
    color: transparent;
}

.payment-method-option input:checked
+ .payment-method-box
.payment-method-check {
    background: #0284C7;
    border-color: #0284C7;
    color: #FFFFFF;
}


/* =========================================================
   PAYMENT BRAND
========================================================= */

.payment-brand-line {
    height: 38px;

    display: flex;
    align-items: center;

    gap: 8px;
}


/* Card icon */

.card-symbol {
    width: 43px;
    height: 29px;

    border-radius: 6px;

    background: #111827;

    position: relative;

    flex-shrink: 0;
}

.card-symbol::before {
    content: '';

    position: absolute;

    left: 0;
    right: 0;
    top: 7px;

    height: 5px;

    background: #FFFFFF;
}

.card-symbol::after {
    content: '';

    position: absolute;

    left: 7px;
    bottom: 6px;

    width: 12px;
    height: 3px;

    border-radius: 2px;

    background: #FFFFFF;
}


/* Visa / Mastercard / Amex */

.card-brands {
    display: flex;
    gap: 4px;
    align-items: center;
}

.card-brand {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;

    border-radius: 4px;

    padding: 3px 5px;

    font-size: 8px;
    line-height: 1;

    font-weight: 800;

    color: #334155;
}

.card-brand.visa {
    color: #1434CB;
}

.card-brand.mastercard {
    color: #EB001B;
}

.card-brand.amex {
    color: #006FCF;
}


/* PayPal */

.paypal-mark {
    display: flex;
    align-items: center;
    gap: 6px;

    font-size: 22px;
    font-weight: 800;

    letter-spacing: -.8px;

    color: #003087;
}

.paypal-p {
    font-size: 30px;
    font-weight: 900;

    color: #0070E0;

    font-style: italic;

    line-height: 1;
}


/* Interac */

.interac-mark {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 88px;
    height: 34px;

    padding: 0 11px;

    background: #FFB81C;

    border-radius: 7px;

    color: #111111;

    font-size: 16px;
    font-weight: 900;

    letter-spacing: -.4px;
}


/* Names */

.payment-method-name {
    font-size: 12px;
    font-weight: 700;
    color: #1F2937;
}


/* =========================================================
   ACTIONS
========================================================= */

.payment-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.payment-back-btn {
    text-decoration: none;

    padding: 10px 18px;

    border: 1px solid #CBD5E1;
    border-radius: 10px;

    color: #475569;
    background: #FFFFFF;

    font-size: 13px;
    font-weight: 600;
}

.payment-back-btn:hover {
    color: #1F2937;
    background: #F8FAFC;
}

.payment-pay-btn {
    min-width: 135px;

    border: 0;
    border-radius: 10px;

    padding: 11px 24px;

    background: #111827;
    color: #FFFFFF;

    font-size: 13px;
    font-weight: 700;
}

.payment-pay-btn:hover {
    background: #000000;
}

.payment-pay-btn:disabled {
    opacity: .55;
    cursor: not-allowed;
}

.payment-secure {
    margin-top: 16px;

    text-align: center;

    color: #94A3B8;

    font-size: 11px;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 700px) {

    .payment-card {
        padding: 20px;
    }

    .payment-method-options {
        grid-template-columns: 1fr;
    }

    .payment-method-box {
        height: 90px;
    }

    .payment-actions {
        flex-direction: column-reverse;
    }

    .payment-back-btn,
    .payment-pay-btn {
        width: 100%;
        text-align: center;
    }
}

</style>


@php

    $startTime = \Carbon\Carbon::parse(
        $booking->lesson_time
    );

    $endTime = $startTime
        ->copy()
        ->addMinutes(
            $booking->duration ?? 60
        );

@endphp


<div class="payment-wrapper">

    <div class="payment-card">


        {{-- =====================================================
           HEADER
        ====================================================== --}}

        <div class="payment-header">

            <h3>
                {{ __('student.complete_payment') }}
            </h3>

            <p>
                {{ __('student.payment_review_subtitle') }}
            </p>

        </div>



        {{-- =====================================================
           LESSON DETAILS
        ====================================================== --}}

        <div class="payment-details">


            {{-- TEACHER --}}
            <div class="payment-row">

                <span class="payment-label">
                    {{ __('student.teacher') }}
                </span>

                <span class="payment-value">
                    {{ $booking->teacher->user->name ?? __('student.teacher') }}
                </span>

            </div>


            {{-- DANCE --}}
            <div class="payment-row">

                <span class="payment-label">
                    {{ __('student.dance') }}
                </span>

                <span class="payment-value">
                    {{ $booking->danceStyle->name ?? __('student.dance') }}
                </span>

            </div>


            {{-- DANCE RATE --}}
            <div class="payment-row">

                <span class="payment-label">
                    {{ __('student.dance_rate') }}
                </span>

                <span class="payment-value">

                    ${{ number_format(
                        (float) ($hourlyRate ?? 0),
                        2
                    ) }}

                    / {{ __('student.hour') }}

                </span>

            </div>


            {{-- DATE --}}
            <div class="payment-row">

                <span class="payment-label">
                    {{ __('student.date') }}
                </span>

                <span class="payment-value">

                    {{ \Carbon\Carbon::parse(
                        $booking->lesson_date
                    )
                    ->locale(app()->getLocale())
                    ->translatedFormat(
                        app()->getLocale() === 'fr'
                            ? 'd M Y'
                            : 'M d, Y'
                    ) }}

                </span>

            </div>


            {{-- TIME --}}
            <div class="payment-row">

                <span class="payment-label">
                    {{ __('student.time') }}
                </span>

                <span class="payment-value">

                    @if(app()->getLocale() === 'fr')

                        {{ $startTime->format('H:i') }}
                        -
                        {{ $endTime->format('H:i') }}

                    @else

                        {{ $startTime->format('g:i A') }}
                        -
                        {{ $endTime->format('g:i A') }}

                    @endif

                </span>

            </div>


            {{-- DURATION --}}
            <div class="payment-row">

                <span class="payment-label">
                    {{ __('student.duration') }}
                </span>

                <span class="payment-value">
                    {{ $booking->duration }} {{ __('student.minutes') }}
                </span>

            </div>


        </div>



        {{-- =====================================================
           TOTAL
        ====================================================== --}}

        <div class="payment-total">

            <div class="payment-total-label">
                {{ __('student.total') }}
            </div>

            <div class="payment-total-amount">

                ${{ number_format(
                    (float) $payment->amount,
                    2
                ) }}

                <span class="payment-total-currency">
                    CAD
                </span>

            </div>

        </div>



        {{-- =====================================================
           PAYMENT METHOD
        ====================================================== --}}

        <div class="payment-method-section">

            <div class="payment-method-title">
                {{ __('student.payment_method') }}
            </div>


            <div class="payment-method-options">


                {{-- =============================================
                   CREDIT / DEBIT CARD
                ============================================== --}}

                <label class="payment-method-option">

                    <input
                        type="radio"
                        name="payment_method"
                        value="card"
                        checked
                    >

                    <div class="payment-method-box">

                        <span class="payment-method-check">
                            ✓
                        </span>


                        <div class="payment-brand-line">

                            <div class="card-symbol"></div>


                            <div class="card-brands">

                                <span class="card-brand visa">
                                    VISA
                                </span>

                                <span class="card-brand mastercard">
                                    MC
                                </span>

                                <span class="card-brand amex">
                                    AMEX
                                </span>

                            </div>

                        </div>


                        <div class="payment-method-name">
                            {{ __('student.credit_debit_card') }}
                        </div>

                    </div>

                </label>



                {{-- =============================================
                   PAYPAL
                ============================================== --}}

                <label class="payment-method-option">

                    <input
                        type="radio"
                        name="payment_method"
                        value="paypal"
                    >

                    <div class="payment-method-box">

                        <span class="payment-method-check">
                            ✓
                        </span>


                        <div class="payment-brand-line">

                            <div class="paypal-mark">

                                <span class="paypal-p">
                                    P
                                </span>

                                <span>
                                    PayPal
                                </span>

                            </div>

                        </div>


                        <div class="payment-method-name">
                            PayPal
                        </div>

                    </div>

                </label>



                {{-- =============================================
                   INTERAC
                ============================================== --}}

                <label class="payment-method-option">

                    <input
                        type="radio"
                        name="payment_method"
                        value="interac"
                    >

                    <div class="payment-method-box">

                        <span class="payment-method-check">
                            ✓
                        </span>


                        <div class="payment-brand-line">

                            <div class="interac-mark">
                                Interac
                            </div>

                        </div>


                        <div class="payment-method-name">
                            Interac
                        </div>

                    </div>

                </label>


            </div>

        </div>



        {{-- =====================================================
           ACTIONS
        ====================================================== --}}

        <div class="payment-actions">

            <a
                href="{{ route('student.bookings') }}"
                class="payment-back-btn"
            >
                {{ __('student.back_to_bookings') }}
            </a>


            <form
                method="POST"
                action="{{ route(
                    'student.payments.checkout',
                    $booking
                ) }}"
            >
                @csrf

                <button
                    type="submit"
                    class="payment-pay-btn"
                >
                    {{ __('student.pay') }}

                    ${{ number_format(
                        (float) $payment->amount,
                        2
                    ) }}
                </button>

            </form>

        </div>


        <div class="payment-secure">
            {{ __('student.secure_payment_processing') }}
        </div>


    </div>

</div>

@endsection