@extends('student.layout')

@section('title', __('student.payments'))
@section('page-title', __('student.payments'))

@section('content')

<style>

.student-payments-card {
    background: #EAF6FF;
    border: 1px solid #CDE9F8;
    border-radius: 22px;
    padding: 28px;
}

.student-payments-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.student-payments-header h3 {
    margin: 0 0 5px;
    font-size: 26px;
    font-weight: 700;
}

.student-payments-header p {
    margin: 0;
    color: #6B7280;
    font-size: 13px;
}

.student-payments-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.student-payment-row {
    display: grid;
    grid-template-columns:
        125px   /* Payment Date */
        140px   /* Teacher */
        120px   /* Dance */
        120px   /* Lesson Date */
        145px   /* Time */
        80px    /* Duration */
        90px    /* Amount */
        90px    /* Status */
        110px;  /* Receipt */

    align-items: center;
    gap: 12px;

    padding: 15px 16px;

    background: rgba(255,255,255,.78);
    border: 1px solid #CDE9F8;
    border-radius: 14px;
}

.payment-label {
    display: block;
    font-size: 9px;
    font-weight: 600;
    color: #7C96A8;
    text-transform: uppercase;
    margin-bottom: 3px;
}

.payment-value {
    font-size: 13px;
    font-weight: 600;
    color: #1F2937;
}

.payment-amount {
    color: #0284C7;
    font-size: 14px;
    font-weight: 700;
}

.payment-status {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
}

.payment-status.pending {
    background: #FFF3CD;
    color: #7A5B00;
}

.payment-status.paid {
    background: #D1FAE5;
    color: #047857;
}

.payment-status.failed {
    background: #FEE2E2;
    color: #B91C1C;
}

.payment-status.refunded {
    background: #E0E7FF;
    color: #4338CA;
}

.payment-receipt-btn {
    display: inline-block;
    padding: 6px 10px;

    border: 1px solid #0284C7;
    border-radius: 8px;

    color: #0284C7;
    background: #FFFFFF;

    text-decoration: none;

    font-size: 11px;
    font-weight: 600;
}

.payment-receipt-btn:hover {
    background: #0284C7;
    color: #FFFFFF;
}

.payment-no-receipt {
    color: #94A3B8;
    font-size: 11px;
}

.student-payments-empty {
    padding: 50px 20px;
    text-align: center;

    background: rgba(255,255,255,.55);

    border: 1px dashed #CDE9F8;
    border-radius: 14px;
}

@media (max-width: 1200px) {

    .student-payment-row {
        grid-template-columns:
            1fr
            1fr
            1fr;
    }
}

@media (max-width: 700px) {

    .student-payment-row {
        grid-template-columns: 1fr 1fr;
    }
}

</style>


<div class="student-payments-card">

    <div class="student-payments-header">

        <div>

            <h3>
                {{ __('student.payments') }}
            </h3>

            <p>
                {{ __('student.payment_history_subtitle') }}
            </p>

        </div>

    </div>


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


    @if($payments->count())

        <div class="student-payments-list">

            @foreach($payments as $payment)

                @php
                    $booking = $payment->booking;
                @endphp

                <div class="student-payment-row">


                    {{-- PAYMENT DATE --}}
                    <div>

                        <span class="payment-label">
                            {{ __('student.payment_date') }}
                        </span>

                        <div class="payment-value">

                            @if($payment->paid_at)

                                {{ $payment->paid_at
                                    ->copy()
                                    ->locale(app()->getLocale())
                                    ->translatedFormat(
                                        app()->getLocale() === 'fr'
                                            ? 'd M Y'
                                            : 'M d, Y'
                                    )
                                }}

                            @elseif($payment->created_at)

                                {{ $payment->created_at
                                    ->copy()
                                    ->locale(app()->getLocale())
                                    ->translatedFormat(
                                        app()->getLocale() === 'fr'
                                            ? 'd M Y'
                                            : 'M d, Y'
                                    )
                                }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    {{-- TEACHER --}}
                    <div>

                        <span class="payment-label">
                            {{ __('student.teacher') }}
                        </span>

                        <div class="payment-value">
                            {{ $booking?->teacher?->user?->name ?? __('student.teacher') }}
                        </div>

                    </div>


                    {{-- DANCE --}}
                    <div>

                        <span class="payment-label">
                            {{ __('student.dance') }}
                        </span>

                        <div class="payment-value">
                            {{ $booking?->danceStyle?->name ?? __('student.dance') }}
                        </div>

                    </div>


                    {{-- LESSON DATE --}}
                    <div>

                        <span class="payment-label">
                            {{ __('student.lesson_date') }}
                        </span>

                        <div class="payment-value">

                            @if($booking?->lesson_date)

                                {{ \Carbon\Carbon::parse(
                                    $booking->lesson_date
                                )
                                ->locale(app()->getLocale())
                                ->translatedFormat(
                                    app()->getLocale() === 'fr'
                                        ? 'd M Y'
                                        : 'M d, Y'
                                ) }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    {{-- TIME --}}
                    <div>

                        <span class="payment-label">
                            {{ __('student.time') }}
                        </span>

                        <div class="payment-value">

                            @if($booking?->lesson_time)

                                @php

                                    $startTime =
                                        \Carbon\Carbon::parse(
                                            $booking->lesson_time
                                        );

                                    $endTime =
                                        $startTime
                                            ->copy()
                                            ->addMinutes(
                                                $booking->duration ?? 60
                                            );

                                @endphp


                                @if(app()->getLocale() === 'fr')

                                    {{ $startTime->format('H:i') }}
                                    -
                                    {{ $endTime->format('H:i') }}

                                @else

                                    {{ $startTime->format('g:i A') }}
                                    -
                                    {{ $endTime->format('g:i A') }}

                                @endif

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    {{-- AMOUNT --}}
                    <div>

                        <span class="payment-label">
                            {{ __('student.amount') }}
                        </span>

                        <div class="payment-amount">

                            ${{ number_format(
                                (float) $payment->amount,
                                2
                            ) }}

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div>

                        <span class="payment-label">
                            {{ __('student.status') }}
                        </span>

                        <span class="payment-status {{ $payment->status }}">

                            @if($payment->status === 'pending')

                                {{ __('student.pending') }}

                            @elseif($payment->status === 'paid')

                                {{ __('student.paid') }}

                            @elseif($payment->status === 'failed')

                                {{ __('student.failed') }}

                            @elseif($payment->status === 'refunded')

                                {{ __('student.refunded') }}

                            @else

                                {{ ucfirst($payment->status) }}

                            @endif

                        </span>

                    </div>


                    {{-- RECEIPT --}}
                    <div>

                        <span class="payment-label">
                            {{ __('student.receipt') }}
                        </span>

                        @if($payment->status === 'paid')

                            <a
                                href="{{ route(
                                    'student.payments.receipt',
                                    $payment
                                ) }}"
                                class="payment-receipt-btn"
                            >
                                {{ __('student.view_receipt') }}
                            </a>

                        @else

                            <span class="payment-no-receipt">
                                {{ __('student.not_available') }}
                            </span>

                        @endif

                    </div>


                </div>

            @endforeach

        </div>

    @else

        <div class="student-payments-empty">

            <h5 class="mb-2">
                {{ __('student.no_payments_yet') }}
            </h5>

            <p class="text-muted mb-0">
                {{ __('student.payment_history_empty') }}
            </p>

        </div>

    @endif

</div>

@endsection