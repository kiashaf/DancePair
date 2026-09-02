@extends('student.layout')

@section('title', __('student.payment_receipt'))
@section('page-title', __('student.payment_receipt'))

@section('content')

<style>

.receipt-wrapper {
    max-width: 760px;
    margin: 0 auto;
}

.receipt-card {
    background: #FFFFFF;
    border: 1px solid #D7E8F3;
    border-radius: 18px;
    padding: 30px;
}

.receipt-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;

    padding-bottom: 20px;
    margin-bottom: 20px;

    border-bottom: 1px solid #E5E7EB;
}

.receipt-brand {
    font-size: 25px;
    font-weight: 800;
    color: #0284C7;
}

.receipt-title {
    text-align: right;
}

.receipt-title h3 {
    margin: 0 0 4px;
    font-size: 22px;
}

.receipt-title span {
    font-size: 11px;
    color: #6B7280;
}

.receipt-status {
    display: inline-block;

    margin-bottom: 20px;

    padding: 6px 11px;

    background: #D1FAE5;
    color: #047857;

    border-radius: 999px;

    font-size: 11px;
    font-weight: 700;
}

.receipt-details {
    border: 1px solid #E5E7EB;
    border-radius: 14px;
    overflow: hidden;
}

.receipt-row {
    display: flex;
    justify-content: space-between;
    gap: 20px;

    padding: 13px 16px;

    border-bottom: 1px solid #E5E7EB;
}

.receipt-row:last-child {
    border-bottom: 0;
}

.receipt-label {
    color: #6B7280;
    font-size: 12px;
}

.receipt-value {
    color: #111827;
    font-size: 13px;
    font-weight: 600;
    text-align: right;
}

.receipt-total {
    margin-top: 20px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    padding: 17px 18px;

    border-radius: 14px;

    background: #EAF6FF;
}

.receipt-total-label {
    font-weight: 700;
}

.receipt-total-value {
    font-size: 24px;
    font-weight: 800;
    color: #0284C7;
}

.receipt-actions {
    display: flex;
    justify-content: space-between;
    gap: 10px;

    margin-top: 20px;
}

.receipt-btn {
    border-radius: 9px;
    padding: 9px 15px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
}

.receipt-back {
    border: 1px solid #CBD5E1;
    background: #FFFFFF;
    color: #475569;
}

.receipt-print {
    border: 0;
    background: #0284C7;
    color: #FFFFFF;
}

@media print {

    .sidebar,
    .topbar,
    .receipt-actions {
        display: none !important;
    }

    .main-content {
        margin: 0 !important;
        padding: 0 !important;
        background: #FFFFFF !important;
    }

    .receipt-wrapper {
        max-width: 100%;
    }

    .receipt-card {
        border: 0;
        box-shadow: none;
    }
}

</style>


@php
    $booking = $payment->booking;
@endphp


<div class="receipt-wrapper">

    <div class="receipt-card">

        <div class="receipt-header">

            <div class="receipt-brand">
                DANCEPAIR
            </div>

            <div class="receipt-title">

                <h3>
                    {{ __('student.payment_receipt') }}
                </h3>

                <span>
                    {{ __('student.receipt_number') }}
                    #{{ str_pad(
                        $payment->id,
                        6,
                        '0',
                        STR_PAD_LEFT
                    ) }}
                </span>

            </div>

        </div>


        <span class="receipt-status">
            ✓ {{ __('student.paid') }}
        </span>


        <div class="receipt-details">


            {{-- TEACHER --}}
            <div class="receipt-row">

                <span class="receipt-label">
                    {{ __('student.teacher') }}
                </span>

                <span class="receipt-value">
                    {{ $booking?->teacher?->user?->name ?? __('student.teacher') }}
                </span>

            </div>


            {{-- DANCE --}}
            <div class="receipt-row">

                <span class="receipt-label">
                    {{ __('student.dance') }}
                </span>

                <span class="receipt-value">
                    {{ $booking?->danceStyle?->name ?? __('student.dance') }}
                </span>

            </div>


            {{-- LESSON DATE --}}
            <div class="receipt-row">

                <span class="receipt-label">
                    {{ __('student.lesson_date') }}
                </span>

                <span class="receipt-value">

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

                </span>

            </div>


            {{-- LESSON TIME --}}
            <div class="receipt-row">

                <span class="receipt-label">
                    {{ __('student.lesson_time') }}
                </span>

                <span class="receipt-value">

                    @if($booking?->lesson_time)

                        @php
                            $lessonTime =
                                \Carbon\Carbon::parse(
                                    $booking->lesson_time
                                );
                        @endphp

                        @if(app()->getLocale() === 'fr')

                            {{ $lessonTime->format('H:i') }}

                        @else

                            {{ $lessonTime->format('g:i A') }}

                        @endif

                    @else

                        —

                    @endif

                </span>

            </div>


            {{-- PAYMENT DATE --}}
            <div class="receipt-row">

                <span class="receipt-label">
                    {{ __('student.payment_date') }}
                </span>

                <span class="receipt-value">

                    @if($payment->paid_at)

                        {{ $payment->paid_at
                            ->copy()
                            ->locale(app()->getLocale())
                            ->translatedFormat(
                                app()->getLocale() === 'fr'
                                    ? 'd M Y • H:i'
                                    : 'M d, Y • g:i A'
                            )
                        }}

                    @elseif($payment->updated_at)

                        {{ $payment->updated_at
                            ->copy()
                            ->locale(app()->getLocale())
                            ->translatedFormat(
                                app()->getLocale() === 'fr'
                                    ? 'd M Y • H:i'
                                    : 'M d, Y • g:i A'
                            )
                        }}

                    @else

                        —

                    @endif

                </span>

            </div>


            {{-- PAYMENT METHOD --}}
            <div class="receipt-row">

                <span class="receipt-label">
                    {{ __('student.payment_method') }}
                </span>

                <span class="receipt-value">

                    @if($payment->payment_provider)

                        {{ ucfirst($payment->payment_provider) }}

                    @else

                        {{ __('student.online') }}

                    @endif

                </span>

            </div>


            {{-- TRANSACTION ID --}}
            @if($payment->transaction_id)

                <div class="receipt-row">

                    <span class="receipt-label">
                        {{ __('student.transaction_id') }}
                    </span>

                    <span class="receipt-value">
                        {{ $payment->transaction_id }}
                    </span>

                </div>

            @endif


        </div>


        {{-- TOTAL --}}
        <div class="receipt-total">

            <div class="receipt-total-label">
                {{ __('student.total_paid') }}
            </div>

            <div class="receipt-total-value">

                ${{ number_format(
                    (float) $payment->amount,
                    2
                ) }}

                CAD

            </div>

        </div>


        {{-- ACTIONS --}}
        <div class="receipt-actions">

            <a
                href="{{ route('student.payments.index') }}"
                class="receipt-btn receipt-back"
            >
                {{ __('student.back_to_payments') }}
            </a>


            <button
                type="button"
                class="receipt-btn receipt-print"
                onclick="window.print()"
            >
                {{ __('student.print_receipt') }}
            </button>

        </div>

    </div>

</div>

@endsection