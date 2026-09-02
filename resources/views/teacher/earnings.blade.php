@extends('teacher.layout')

@section('title', __('teacher.earnings'))
@section('page-title', __('teacher.earnings'))

@section('content')

<style>

.earnings-page {
    display: flex;
    flex-direction: column;
    gap: 22px;
}


/* =========================================================
   SUMMARY CARDS
========================================================= */

.earnings-summary {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}

.earnings-card {
    background:
        linear-gradient(
            145deg,
            #FFFFFF 0%,
            #F8F6FF 100%
        );

    border: 1px solid #DDD6FE;
    border-radius: 20px;

    padding: 22px;

    box-shadow:
        0 8px 22px rgba(91, 33, 182, .05);
}

.earnings-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.earnings-card-label {
    font-size: 11px;
    font-weight: 700;

    color: #64748B;

    text-transform: uppercase;
    letter-spacing: .4px;
}

.earnings-card-icon {
    width: 40px;
    height: 40px;

    border-radius: 13px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #F1ECFF;

    font-size: 18px;
}

.earnings-card-value {
    margin-top: 15px;

    font-size: 28px;
    font-weight: 800;

    color: #111827;
}

.earnings-card-value.net {
    color: #047857;
}

.earnings-card-value.fee {
    color: #B45309;
}


/* =========================================================
   PAYMENT HISTORY CARD
========================================================= */

.earnings-history {
    background: #FFFFFF;

    border: 1px solid #DDD6FE;
    border-radius: 22px;

    padding: 24px;

    box-shadow:
        0 8px 24px rgba(15, 23, 42, .04);
}

.earnings-history-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 20px;
}

.earnings-history-title {
    margin: 0;

    font-size: 22px;
    font-weight: 800;

    color: #111827;
}

.earnings-history-subtitle {
    margin-top: 3px;

    font-size: 11px;

    color: #64748B;
}


/* =========================================================
   TABLE HEADER
========================================================= */

.earnings-table-header {
    display: grid;

    grid-template-columns:
        1fr
        1.1fr
        1fr
        1fr
        .8fr
        .8fr
        .9fr;

    gap: 12px;

    padding: 10px 14px;

    border-radius: 10px;

    background: #F7F4FF;

    margin-bottom: 8px;
}

.earnings-table-header div {
    font-size: 8px;
    font-weight: 800;

    color: #7C6FA4;

    text-transform: uppercase;
    letter-spacing: .4px;
}


/* =========================================================
   PAYMENT ROW
========================================================= */

.earnings-row {
    display: grid;

    grid-template-columns:
        1fr
        1.1fr
        1fr
        1fr
        .8fr
        .8fr
        .9fr;

    gap: 12px;

    align-items: center;

    padding: 15px 14px;

    border-bottom: 1px solid #EEEAF8;
}

.earnings-row:last-child {
    border-bottom: 0;
}

.earnings-value {
    font-size: 11px;
    font-weight: 600;

    color: #1F2937;
}

.earnings-muted {
    font-size: 10px;

    color: #64748B;
}

.earnings-gross {
    font-weight: 700;

    color: #374151;
}

.earnings-fee {
    font-weight: 700;

    color: #B45309;
}

.earnings-net {
    font-weight: 800;

    color: #047857;
}

.earnings-paid-badge {
    display: inline-block;

    padding: 5px 9px;

    border-radius: 999px;

    background: #D1FAE5;
    color: #047857;

    font-size: 9px;
    font-weight: 700;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.earnings-empty {
    padding: 50px 20px;

    text-align: center;

    color: #94A3B8;

    border: 1px dashed #D8D0F0;
    border-radius: 14px;

    background: #FCFBFF;
}


/* =========================================================
   MOBILE
========================================================= */

@media(max-width: 1100px) {

    .earnings-summary {
        grid-template-columns: repeat(2, 1fr);
    }

    .earnings-table-header {
        display: none;
    }

    .earnings-row {
        grid-template-columns: 1fr 1fr;

        border: 1px solid #EEEAF8;
        border-radius: 14px;

        margin-bottom: 10px;
    }
}

@media(max-width: 650px) {

    .earnings-summary {
        grid-template-columns: 1fr;
    }

    .earnings-row {
        grid-template-columns: 1fr;
    }
}

</style>


<div class="earnings-page">


    {{-- =====================================================
       SUMMARY
    ====================================================== --}}

    <div class="earnings-summary">


        {{-- TOTAL EARNINGS --}}
        <div class="earnings-card">

            <div class="earnings-card-top">

                <div class="earnings-card-label">
                    {{ __('teacher.your_earnings') }}
                </div>

                <div class="earnings-card-icon">
                    $
                </div>

            </div>

            <div class="earnings-card-value net">

                ${{ number_format(
                    (float) $totalEarnings,
                    2
                ) }}

            </div>

        </div>


        {{-- GROSS REVENUE --}}
        <div class="earnings-card">

            <div class="earnings-card-top">

                <div class="earnings-card-label">
                    {{ __('teacher.gross_revenue') }}
                </div>

                <div class="earnings-card-icon">
                    💳
                </div>

            </div>

            <div class="earnings-card-value">

                ${{ number_format(
                    (float) $grossRevenue,
                    2
                ) }}

            </div>

        </div>


        {{-- DANCEPAIR FEES --}}
        <div class="earnings-card">

            <div class="earnings-card-top">

                <div class="earnings-card-label">
                    {{ __('teacher.dancepair_fees') }}
                </div>

                <div class="earnings-card-icon">
                    %
                </div>

            </div>

            <div class="earnings-card-value fee">

                ${{ number_format(
                    (float) $platformFees,
                    2
                ) }}

            </div>

        </div>


        {{-- PAID LESSONS --}}
        <div class="earnings-card">

            <div class="earnings-card-top">

                <div class="earnings-card-label">
                    {{ __('teacher.paid_lessons') }}
                </div>

                <div class="earnings-card-icon">
                    ✓
                </div>

            </div>

            <div class="earnings-card-value">

                {{ $paidLessons }}

            </div>

        </div>

    </div>



    {{-- =====================================================
       PAYMENT HISTORY
    ====================================================== --}}

    <div class="earnings-history">

        <div class="earnings-history-header">

            <div>

                <h3 class="earnings-history-title">
                    {{ __('teacher.payment_history') }}
                </h3>

                <div class="earnings-history-subtitle">
                    {{ __('teacher.payment_history_subtitle') }}
                </div>

            </div>

        </div>


        @if($payments->count())


            {{-- HEADER --}}
            <div class="earnings-table-header">

                <div>
                    {{ __('teacher.student') }}
                </div>

                <div>
                    {{ __('teacher.dance') }}
                </div>

                <div>
                    {{ __('teacher.lesson_date') }}
                </div>

                <div>
                    {{ __('teacher.time') }}
                </div>

                <div>
                    {{ __('teacher.gross') }}
                </div>

                <div>
                    {{ __('teacher.dancepair_fee') }}
                </div>

                <div>
                    {{ __('teacher.you_earned') }}
                </div>

            </div>


            {{-- ROWS --}}
            @foreach($payments as $payment)

                @php

                    $booking =
                        $payment->booking;

                    $startTime =
                        $booking
                            ? \Carbon\Carbon::parse(
                                $booking->lesson_time
                            )
                            : null;

                    $endTime =
                        $startTime
                            ? $startTime
                                ->copy()
                                ->addMinutes(
                                    $booking->duration ?? 60
                                )
                            : null;

                @endphp


                <div class="earnings-row">


                    {{-- STUDENT --}}
                    <div>

                        <div class="earnings-value">

                            {{ $booking
                                ?->student
                                ?->user
                                ?->name
                                ?? __('teacher.student')
                            }}

                        </div>

                        @if($payment->paid_at)

                            <div class="earnings-muted">

                                {{ __('teacher.paid') }}

                                {{ \Carbon\Carbon::parse(
                                    $payment->paid_at
                                )
                                ->locale(app()->getLocale())
                                ->translatedFormat(
                                    app()->getLocale() === 'fr'
                                        ? 'd M Y'
                                        : 'M d, Y'
                                ) }}

                            </div>

                        @endif

                    </div>


                    {{-- DANCE --}}
                    <div class="earnings-value">

                        {{ $booking
                            ?->danceStyle
                            ?->name
                            ?? __('teacher.dance')
                        }}

                    </div>


                    {{-- LESSON DATE --}}
                    <div class="earnings-value">

                        @if($booking)

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


                    {{-- TIME --}}
                    <div class="earnings-value">

                        @if($startTime && $endTime)

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


                    {{-- GROSS --}}
                    <div
                        class="
                            earnings-value
                            earnings-gross
                        "
                    >

                        ${{ number_format(
                            (float) $payment->amount,
                            2
                        ) }}

                    </div>


                    {{-- DANCEPAIR FEE --}}
                    <div
                        class="
                            earnings-value
                            earnings-fee
                        "
                    >

                        -${{ number_format(
                            (float) $payment->platform_fee,
                            2
                        ) }}

                    </div>


                    {{-- TEACHER EARNINGS --}}
                    <div>

                        <div
                            class="
                                earnings-value
                                earnings-net
                            "
                        >

                            ${{ number_format(
                                (float) $payment->teacher_amount,
                                2
                            ) }}

                        </div>

                        <span class="earnings-paid-badge">
                            {{ __('teacher.paid') }}
                        </span>

                    </div>

                </div>

            @endforeach


        @else

            <div class="earnings-empty">

                <div
                    style="
                        font-size:28px;
                        margin-bottom:8px;
                    "
                >
                    $
                </div>

                {{ __('teacher.no_earnings_yet') }}

            </div>

        @endif

    </div>

</div>

@endsection