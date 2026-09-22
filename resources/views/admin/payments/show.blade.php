@extends('admin.layout')

@section(
    'title',
    app()->getLocale() === 'fr'
        ? 'Paiement #' . $payment->id
        : 'Payment #' . $payment->id
)

@section(
    'page-title',
    app()->getLocale() === 'fr'
        ? 'Détails du paiement'
        : 'Payment Details'
)

@section('content')

@php

    $booking = $payment->booking;
    $student = $payment->student;
    $teacher = $payment->teacher;


    /*
    |--------------------------------------------------------------------------
    | REFUND STATE
    |--------------------------------------------------------------------------
    */

    $isRefunded =
        !is_null($payment->refunded_at)
        ||
        $payment->status === 'refunded';


    $isRefundPending =
        $payment->status === 'refund_pending';


    $canAdminRefund =
        !$isRefunded
        &&
        !$isRefundPending
        &&
        !is_null($payment->paid_at)
        &&
        $payment->payment_provider === 'stripe'
        &&
        !empty($payment->transaction_id);


    $refundConfirmation =
        app()->getLocale() === 'fr'
            ? 'Confirmer le remboursement complet de 100 % ? Le montant sera remboursé à l’élève sur le mode de paiement d’origine. La part transférée au professeur ainsi que les frais DancePair seront également remboursés.'
            : 'Confirm the 100% full refund? The student will be refunded to the original payment method. The teacher transfer and DancePair application fee will also be refunded.';

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
   ALERTS
========================================================= */

.payment-admin-alert {
    padding: 12px 15px;

    border-radius: 11px;

    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.payment-admin-alert.success {
    border: 1px solid #A7F3D0;

    background: #ECFDF5;

    color: #047857;
}

.payment-admin-alert.error {
    border: 1px solid #FECACA;

    background: #FEF2F2;

    color: #B91C1C;
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

.payment-detail-summary.refunded {
    border-color: #FECACA;
}

.payment-detail-summary.refund-pending {
    border-color: #FDE68A;
}

.payment-summary-main {
    padding: 21px;

    background: #F0FDF4;
}

.payment-detail-summary.refunded
.payment-summary-main {
    background: #FEF2F2;
}

.payment-detail-summary.refund-pending
.payment-summary-main {
    background: #FFFBEB;
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

.payment-summary-main span.refund-pending {
    background: #FEF3C7;

    color: #92400E;
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
   ADMIN REFUND
========================================================= */

.admin-refund-panel {
    margin-top: 18px;

    padding: 16px;

    border: 1px solid #FECACA;
    border-radius: 12px;

    background: #FFF7F7;
}

.admin-refund-panel.pending {
    border-color: #FDE68A;

    background: #FFFBEB;
}

.admin-refund-panel.refunded {
    border-color: #BBF7D0;

    background: #F0FDF4;
}

.admin-refund-panel.unavailable {
    border-color: #E2E8F0;

    background: #F8FAFC;
}

.admin-refund-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;
}

.admin-refund-heading h5 {
    margin: 0;

    font-size: 12px;
    font-weight: 850;

    color: #0F172A;
}

.admin-refund-heading span {
    padding: 4px 8px;

    border-radius: 999px;

    background: #FEE2E2;

    color: #B91C1C;

    font-size: 7px;
    font-weight: 850;

    text-transform: uppercase;
}

.admin-refund-panel.pending
.admin-refund-heading span {
    background: #FEF3C7;

    color: #92400E;
}

.admin-refund-panel.refunded
.admin-refund-heading span {
    background: #DCFCE7;

    color: #047857;
}

.admin-refund-panel.unavailable
.admin-refund-heading span {
    background: #E2E8F0;

    color: #475569;
}

.admin-refund-description {
    margin: 7px 0 13px;

    max-width: 760px;

    color: #64748B;

    font-size: 9px;
    line-height: 1.55;
}

.admin-refund-breakdown {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0,1fr));

    gap: 8px;

    margin-bottom: 13px;
}

.admin-refund-breakdown-item {
    padding: 10px 11px;

    border: 1px solid rgba(248,113,113,.20);
    border-radius: 9px;

    background: rgba(255,255,255,.75);
}

.admin-refund-breakdown-item span {
    display: block;

    margin-bottom: 3px;

    color: #94A3B8;

    font-size: 7px;
    font-weight: 850;

    text-transform: uppercase;
}

.admin-refund-breakdown-item strong {
    color: #0F172A;

    font-size: 11px;
    font-weight: 850;
}

.admin-refund-actions {
    display: flex;
    align-items: center;

    gap: 10px;

    flex-wrap: wrap;
}

.admin-refund-button {
    min-height: 39px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 15px;

    border: 1px solid #DC2626;
    border-radius: 9px;

    background: #DC2626;

    color: #FFFFFF;

    font-size: 9.5px;
    font-weight: 850;

    cursor: pointer;

    transition:
        background .15s ease,
        border-color .15s ease;
}

.admin-refund-button:hover {
    border-color: #B91C1C;

    background: #B91C1C;
}

.admin-refund-warning {
    color: #991B1B;

    font-size: 8px;
    font-weight: 700;
}

.admin-refund-status-text {
    margin: 0;

    font-size: 9px;
    font-weight: 700;
    line-height: 1.5;
}

.admin-refund-panel.pending
.admin-refund-status-text {
    color: #92400E;
}

.admin-refund-panel.refunded
.admin-refund-status-text {
    color: #047857;
}

.admin-refund-panel.unavailable
.admin-refund-status-text {
    color: #64748B;
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
    .payment-finance-grid,
    .admin-refund-breakdown {
        grid-template-columns: 1fr;
    }

    .payment-summary-main {
        grid-column: auto;
    }

    .admin-refund-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .admin-refund-button {
        width: 100%;
    }
}

</style>


<div class="payment-detail-page">


{{-- =========================================================
   MESSAGES
========================================================= --}}

@if(session('success'))

    <div class="payment-admin-alert success">

        {{ session('success') }}

    </div>

@endif


@if(session('error'))

    <div class="payment-admin-alert error">

        {{ session('error') }}

    </div>

@endif



{{-- =========================================================
   HEADER
========================================================= --}}

<div class="payment-detail-header">

    <div>

        <h2>

            {{ app()->getLocale() === 'fr'
                ? 'Paiement #' . $payment->id
                : 'Payment #' . $payment->id
            }}

        </h2>


        <p>

            {{ app()->getLocale() === 'fr'
                ? 'Informations complètes sur la transaction, la réservation et le client.'
                : 'Complete transaction, booking and client information.'
            }}

        </p>

    </div>


    <a
        href="{{ route('admin.payments') }}"
        class="payment-back-btn"
    >

        {{ app()->getLocale() === 'fr'
            ? '← Retour aux paiements'
            : '← Back to Payments'
        }}

    </a>

</div>



{{-- =========================================================
   SUMMARY
========================================================= --}}

<div
    class="
        payment-detail-summary

        {{ $isRefunded
            ? 'refunded'
            : (
                $isRefundPending
                    ? 'refund-pending'
                    : ''
            )
        }}
    "
>

    <div class="payment-summary-main">


        <span
            class="
                {{ $isRefunded
                    ? 'refunded'
                    : (
                        $isRefundPending
                            ? 'refund-pending'
                            : ''
                    )
                }}
            "
        >

            @if($isRefunded)

                {{ app()->getLocale() === 'fr'
                    ? 'Remboursé'
                    : 'Refunded'
                }}

            @elseif($isRefundPending)

                {{ app()->getLocale() === 'fr'
                    ? 'Remboursement en cours'
                    : 'Refund processing'
                }}

            @else

                {{ ucfirst(
                    $payment->status
                    ??
                    (
                        app()->getLocale() === 'fr'
                            ? 'Payé'
                            : 'Paid'
                    )
                ) }}

            @endif

        </span>


        <h3>

            {{ ucfirst(
                $payment->payment_provider
                ??
                (
                    app()->getLocale() === 'fr'
                        ? 'Paiement'
                        : 'Payment'
                )
            ) }}

        </h3>


        <p>

            {{ $payment->transaction_id
                ?? (
                    app()->getLocale() === 'fr'
                        ? 'Aucun identifiant de transaction'
                        : 'No transaction ID'
                )
            }}

        </p>

    </div>



    <div class="payment-summary-item amount">

        <span>

            {{ app()->getLocale() === 'fr'
                ? 'Montant brut'
                : 'Gross'
            }}

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

            {{ app()->getLocale() === 'fr'
                ? 'Professeur'
                : 'Teacher'
            }}

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

            {{ app()->getLocale() === 'fr'
                ? 'Devise'
                : 'Currency'
            }}

        </span>


        <strong>

            {{ strtoupper(
                $payment->currency
                ??
                'CAD'
            ) }}

        </strong>

    </div>

</div>



<div class="payment-detail-grid">


{{-- =========================================================
   STUDENT
========================================================= --}}

<div class="payment-detail-card student">

    <div class="payment-card-header">

        <h4>

            {{ app()->getLocale() === 'fr'
                ? 'Élève'
                : 'Student'
            }}

        </h4>


        <p>

            {{ app()->getLocale() === 'fr'
                ? 'Client ayant effectué le paiement'
                : 'Client who made the payment'
            }}

        </p>

    </div>


    <div class="payment-person-row">

        <div class="payment-avatar">

            {{ strtoupper(
                substr(
                    $student?->user?->name
                    ??
                    'S',
                    0,
                    1
                )
            ) }}

        </div>


        <div>

            <strong>

                {{ $student?->user?->name
                    ??
                    '—'
                }}

            </strong>


            <small>

                {{ $student?->user?->email
                    ??
                    '—'
                }}

            </small>

        </div>

    </div>


    <div class="payment-info-grid">

        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'ID élève'
                    : 'Student ID'
                }}

            </span>


            <strong>

                #{{ $payment->student_id }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'Ville'
                    : 'City'
                }}

            </span>


            <strong>

                {{ $student?->city
                    ??
                    '—'
                }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Province
            </span>


            <strong>

                {{ $student?->province
                    ??
                    '—'
                }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'Pays'
                    : 'Country'
                }}

            </span>


            <strong>

                {{ $student?->country
                    ??
                    '—'
                }}

            </strong>

        </div>

    </div>

</div>



{{-- =========================================================
   TEACHER
========================================================= --}}

<div class="payment-detail-card teacher">

    <div class="payment-card-header">

        <h4>

            {{ app()->getLocale() === 'fr'
                ? 'Professeur'
                : 'Teacher'
            }}

        </h4>


        <p>

            {{ app()->getLocale() === 'fr'
                ? 'Professeur recevant le paiement de ce cours'
                : 'Teacher receiving this lesson payment'
            }}

        </p>

    </div>


    <div class="payment-person-row">

        <div class="payment-avatar">

            {{ strtoupper(
                substr(
                    $teacher?->user?->name
                    ??
                    'T',
                    0,
                    1
                )
            ) }}

        </div>


        <div>

            <strong>

                {{ $teacher?->user?->name
                    ??
                    '—'
                }}

            </strong>


            <small>

                {{ $teacher?->user?->email
                    ??
                    '—'
                }}

            </small>

        </div>

    </div>


    <div class="payment-info-grid">

        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'ID professeur'
                    : 'Teacher ID'
                }}

            </span>


            <strong>

                #{{ $payment->teacher_id }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'Ville'
                    : 'City'
                }}

            </span>


            <strong>

                {{ $teacher?->city
                    ??
                    '—'
                }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>
                Province
            </span>


            <strong>

                {{ $teacher?->province
                    ??
                    '—'
                }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'Pays'
                    : 'Country'
                }}

            </span>


            <strong>

                {{ $teacher?->country
                    ??
                    '—'
                }}

            </strong>

        </div>

    </div>

</div>



{{-- =========================================================
   TRANSACTION
========================================================= --}}

<div class="payment-detail-card transaction">

    <div class="payment-card-header">

        <h4>
            Transaction
        </h4>


        <p>

            {{ app()->getLocale() === 'fr'
                ? 'Fournisseur de paiement et données de transaction'
                : 'Payment provider and transaction data'
            }}

        </p>

    </div>


    <div class="payment-info-grid">

        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'ID paiement'
                    : 'Payment ID'
                }}

            </span>


            <strong>

                #{{ $payment->id }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'Fournisseur'
                    : 'Provider'
                }}

            </span>


            <strong>

                {{ ucfirst(
                    $payment->payment_provider
                    ??
                    '—'
                ) }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'ID transaction'
                    : 'Transaction ID'
                }}

            </span>


            <strong>

                {{ $payment->transaction_id
                    ??
                    '—'
                }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'Statut'
                    : 'Status'
                }}

            </span>


            <strong>

                @if($isRefunded)

                    {{ app()->getLocale() === 'fr'
                        ? 'Remboursé'
                        : 'Refunded'
                    }}

                @elseif($isRefundPending)

                    {{ app()->getLocale() === 'fr'
                        ? 'Remboursement en cours'
                        : 'Refund processing'
                    }}

                @else

                    {{ ucfirst(
                        $payment->status
                        ??
                        '—'
                    ) }}

                @endif

            </strong>

        </div>


        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'Payé le'
                    : 'Paid At'
                }}

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

                {{ app()->getLocale() === 'fr'
                    ? 'Remboursé le'
                    : 'Refunded At'
                }}

            </span>


            <strong>

                {{ optional(
                    $payment->refunded_at
                )->format(
                    'M d, Y · g:i A'
                )
                ??
                (
                    app()->getLocale() === 'fr'
                        ? 'Non remboursé'
                        : 'Not refunded'
                )
                }}

            </strong>

        </div>


        <div class="payment-info-item">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'Créé le'
                    : 'Created'
                }}

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



    {{-- =====================================================
       FINANCIAL SPLIT
    ====================================================== --}}

    <div class="payment-finance-grid">

        <div class="payment-finance-box">

            <span>

                {{ app()->getLocale() === 'fr'
                    ? 'Montant brut'
                    : 'Gross'
                }}

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

                {{ app()->getLocale() === 'fr'
                    ? 'Revenu DancePair'
                    : 'DancePair Revenue'
                }}

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

                {{ app()->getLocale() === 'fr'
                    ? 'Revenu professeur'
                    : 'Teacher Earnings'
                }}

            </span>


            <strong class="teacher-money">

                ${{ number_format(
                    (float) $payment->teacher_amount,
                    2
                ) }}

            </strong>

        </div>

    </div>



    {{-- =====================================================
       ADMIN REFUND
    ====================================================== --}}

    @if($isRefunded)

        <div class="admin-refund-panel refunded">

            <div class="admin-refund-heading">

                <h5>

                    {{ app()->getLocale() === 'fr'
                        ? 'Remboursement complet'
                        : 'Full Refund'
                    }}

                </h5>


                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Remboursé'
                        : 'Refunded'
                    }}

                </span>

            </div>


            <p class="admin-refund-description">

                {{ app()->getLocale() === 'fr'
                    ? 'Ce paiement a déjà été remboursé. Aucun autre remboursement automatique n’est disponible pour cette transaction.'
                    : 'This payment has already been refunded. No additional automatic refund is available for this transaction.'
                }}

            </p>


            <p class="admin-refund-status-text">

                ✓

                {{ app()->getLocale() === 'fr'
                    ? 'Remboursement enregistré'
                    : 'Refund recorded'
                }}

                @if($payment->refunded_at)

                    —

                    {{ $payment->refunded_at->format(
                        'M d, Y · g:i A'
                    ) }}

                @endif

            </p>

        </div>


    @elseif($isRefundPending)

        <div class="admin-refund-panel pending">

            <div class="admin-refund-heading">

                <h5>

                    {{ app()->getLocale() === 'fr'
                        ? 'Remboursement en cours'
                        : 'Refund Processing'
                    }}

                </h5>


                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'En cours'
                        : 'Pending'
                    }}

                </span>

            </div>


            <p class="admin-refund-description">

                {{ app()->getLocale() === 'fr'
                    ? 'Stripe traite actuellement le remboursement complet de cette transaction. Ne soumettez pas un second remboursement.'
                    : 'Stripe is currently processing the full refund for this transaction. Do not submit a second refund.'
                }}

            </p>


            <p class="admin-refund-status-text">

                {{ app()->getLocale() === 'fr'
                    ? 'En attente de la confirmation finale de Stripe.'
                    : 'Waiting for final confirmation from Stripe.'
                }}

            </p>

        </div>


    @elseif($canAdminRefund)

        <div class="admin-refund-panel">

            <div class="admin-refund-heading">

                <h5>

                    {{ app()->getLocale() === 'fr'
                        ? 'Remboursement administrateur'
                        : 'Admin Refund'
                    }}

                </h5>


                <span>

                    {{ app()->getLocale() === 'fr'
                        ? '100 %'
                        : '100% Full Refund'
                    }}

                </span>

            </div>


            <p class="admin-refund-description">

                {{ app()->getLocale() === 'fr'
                    ? 'Cette action rembourse 100 % du montant payé par l’élève vers le mode de paiement d’origine. La part du professeur sera récupérée et les frais DancePair seront également remboursés.'
                    : 'This action refunds 100% of the amount paid by the student to the original payment method. The teacher transfer will be reversed and the DancePair application fee will also be refunded.'
                }}

            </p>


            <div class="admin-refund-breakdown">

                <div class="admin-refund-breakdown-item">

                    <span>

                        {{ app()->getLocale() === 'fr'
                            ? 'Élève'
                            : 'Student Refund'
                        }}

                    </span>


                    <strong>

                        ${{ number_format(
                            (float) $payment->amount,
                            2
                        ) }}

                    </strong>

                </div>


                <div class="admin-refund-breakdown-item">

                    <span>

                        {{ app()->getLocale() === 'fr'
                            ? 'Part professeur'
                            : 'Teacher Transfer'
                        }}

                    </span>


                    <strong>

                        ${{ number_format(
                            (float) $payment->teacher_amount,
                            2
                        ) }}

                    </strong>

                </div>


                <div class="admin-refund-breakdown-item">

                    <span>

                        {{ app()->getLocale() === 'fr'
                            ? 'Frais DancePair'
                            : 'DancePair Fee'
                        }}

                    </span>


                    <strong>

                        ${{ number_format(
                            (float) $payment->platform_fee,
                            2
                        ) }}

                    </strong>

                </div>

            </div>


            <div class="admin-refund-actions">

                <form
                    method="POST"
                    action="{{ route(
                        'admin.payments.refund',
                        $payment
                    ) }}"
                    onsubmit="return confirm(@js($refundConfirmation));"
                >

                    @csrf


                    <button
                        type="submit"
                        class="admin-refund-button"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Rembourser 100 %'
                            : 'Issue 100% Refund'
                        }}

                    </button>

                </form>


                <span class="admin-refund-warning">

                    {{ app()->getLocale() === 'fr'
                        ? 'Vérifiez la transaction avant de confirmer.'
                        : 'Verify the transaction before confirming.'
                    }}

                </span>

            </div>

        </div>


    @elseif(
        !is_null($payment->paid_at)
        &&
        $payment->payment_provider !== 'stripe'
    )

        <div class="admin-refund-panel unavailable">

            <div class="admin-refund-heading">

                <h5>

                    {{ app()->getLocale() === 'fr'
                        ? 'Remboursement automatique indisponible'
                        : 'Automatic Refund Unavailable'
                    }}

                </h5>


                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Non Stripe'
                        : 'Non-Stripe'
                    }}

                </span>

            </div>


            <p class="admin-refund-status-text">

                {{ app()->getLocale() === 'fr'
                    ? 'Ce paiement n’a pas été traité par Stripe et ne peut donc pas être remboursé automatiquement depuis cette page.'
                    : 'This payment was not processed by Stripe, so it cannot be refunded automatically from this page.'
                }}

            </p>

        </div>


    @elseif(
        !is_null($payment->paid_at)
        &&
        empty($payment->transaction_id)
    )

        <div class="admin-refund-panel unavailable">

            <div class="admin-refund-heading">

                <h5>

                    {{ app()->getLocale() === 'fr'
                        ? 'Remboursement indisponible'
                        : 'Refund Unavailable'
                    }}

                </h5>


                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Transaction manquante'
                        : 'Missing Transaction'
                    }}

                </span>

            </div>


            <p class="admin-refund-status-text">

                {{ app()->getLocale() === 'fr'
                    ? 'Aucun identifiant de transaction Stripe n’est associé à ce paiement.'
                    : 'No Stripe transaction ID is associated with this payment.'
                }}

            </p>

        </div>

    @endif

</div>



{{-- =========================================================
   BOOKING
========================================================= --}}

<div class="payment-detail-card booking">

    <div class="payment-card-header">

        <h4>

            {{ app()->getLocale() === 'fr'
                ? 'Réservation associée'
                : 'Related Booking'
            }}

        </h4>


        <p>

            {{ app()->getLocale() === 'fr'
                ? 'Cours associé à cette transaction'
                : 'Lesson connected to this transaction'
            }}

        </p>

    </div>


    @if($booking)

        <div class="payment-info-grid">

            <div class="payment-info-item">

                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'ID réservation'
                        : 'Booking ID'
                    }}

                </span>


                <strong>

                    #{{ $booking->id }}

                </strong>

            </div>


            <div class="payment-info-item">

                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Style de danse'
                        : 'Dance Style'
                    }}

                </span>


                <strong>

                    {{ $booking->danceStyle?->name
                        ??
                        '—'
                    }}

                </strong>

            </div>


            <div class="payment-info-item">

                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Date du cours'
                        : 'Lesson Date'
                    }}

                </span>


                <strong>

                    {{ $booking->lesson_date
                        ? \Carbon\Carbon::parse(
                            $booking->lesson_date
                        )
                            ->locale(
                                app()->getLocale()
                            )
                            ->translatedFormat(
                                app()->getLocale() === 'fr'
                                    ? 'd M Y'
                                    : 'M d, Y'
                            )
                        : '—'
                    }}

                </strong>

            </div>


            <div class="payment-info-item">

                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Statut réservation'
                        : 'Booking Status'
                    }}

                </span>


                <strong>

                    @if($booking->status === 'cancelled')

                        {{ app()->getLocale() === 'fr'
                            ? 'Annulé'
                            : 'Cancelled'
                        }}

                    @elseif($booking->status === 'confirmed')

                        {{ app()->getLocale() === 'fr'
                            ? 'Confirmé'
                            : 'Confirmed'
                        }}

                    @elseif($booking->status === 'pending')

                        {{ app()->getLocale() === 'fr'
                            ? 'En attente'
                            : 'Pending'
                        }}

                    @elseif($booking->status === 'completed')

                        {{ app()->getLocale() === 'fr'
                            ? 'Terminé'
                            : 'Completed'
                        }}

                    @else

                        {{ ucfirst(
                            $booking->status
                            ??
                            '—'
                        ) }}

                    @endif

                </strong>

            </div>


            <div class="payment-info-item">

                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Prix du cours'
                        : 'Lesson Price'
                    }}

                </span>


                <strong>

                    ${{ number_format(
                        (float) (
                            $booking->price
                            ??
                            0
                        ),
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

            {{ app()->getLocale() === 'fr'
                ? 'Voir la réservation complète →'
                : 'View Full Booking →'
            }}

        </a>


    @else

        {{ app()->getLocale() === 'fr'
            ? 'Aucune réservation n’est associée à ce paiement.'
            : 'No booking is connected to this payment.'
        }}

    @endif

</div>


</div>

</div>

@endsection