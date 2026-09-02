@extends('admin.layout')

@section('title', 'Payments')
@section('page-title', 'Payments')

@section('content')

<style>

.admin-payments-page {
    display: flex;
    flex-direction: column;
    gap: 22px;
    padding-bottom: 40px;
}


/* =========================================================
   OVERVIEW
========================================================= */

.payment-overview {
    padding: 22px;

    background: #FFFFFF;

    border: 1px solid #E2E8F0;
    border-top: 4px solid #10B981;
    border-radius: 20px;

    box-shadow:
        0 8px 24px rgba(15, 23, 42, .035);
}

.payment-overview-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 16px;

    margin-bottom: 18px;
}

.payment-overview-header h3 {
    margin: 0;

    font-size: 22px;
    font-weight: 850;

    color: #0F172A;
}

.payment-overview-header p {
    margin: 5px 0 0;

    font-size: 10px;

    color: #64748B;
}

.payment-total-pill {
    padding: 7px 12px;

    border: 1px solid #BBF7D0;
    border-radius: 999px;

    background: #F0FDF4;

    color: #047857;

    font-size: 9px;
    font-weight: 800;

    white-space: nowrap;
}


/* =========================================================
   KPI
========================================================= */

.payment-stats-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0,1fr));

    gap: 12px;
}

.payment-stat-card {
    min-width: 0;
    min-height: 110px;

    padding: 16px;

    display: flex;
    flex-direction: column;
    justify-content: space-between;

    border: 1px solid #E2E8F0;
    border-radius: 15px;

    background: #FCFDFE;
}

.payment-stat-label {
    font-size: 8.5px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .45px;

    color: #64748B;
}

.payment-stat-value {
    margin-top: 10px;

    max-width: 100%;

    font-size: clamp(19px, 1.4vw, 25px);
    font-weight: 850;

    line-height: 1.05;

    color: #0F172A;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;

    font-variant-numeric: tabular-nums;
}

.payment-stat-value.platform {
    color: #DC2626;
}

.payment-stat-value.teacher {
    color: #047857;
}

.payment-stat-description {
    margin-top: 8px;

    font-size: 8.5px;
    font-weight: 650;

    color: #94A3B8;
}


/* =========================================================
   STATUS STRIP
========================================================= */

.payment-status-strip {
    margin-top: 14px;

    display: flex;
    flex-wrap: wrap;

    gap: 8px;
}

.payment-status-chip {
    display: inline-flex;
    align-items: center;

    padding: 6px 10px;

    border-radius: 999px;

    font-size: 8px;
    font-weight: 800;
}

.payment-status-chip.paid {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    color: #047857;
}

.payment-status-chip.refunded {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #B91C1C;
}


/* =========================================================
   FILTER
========================================================= */

.payment-filter-card {
    padding: 20px;

    background: #FFFFFF;

    border: 1px solid #E2E8F0;
    border-radius: 18px;

    box-shadow:
        0 6px 18px rgba(15,23,42,.03);
}

.payment-filter-header {
    margin-bottom: 16px;
}

.payment-filter-header h4 {
    margin: 0;

    font-size: 19px;
    font-weight: 850;

    color: #0F172A;
}

.payment-filter-header p {
    margin: 4px 0 0;

    font-size: 10px;

    color: #64748B;
}

.payment-filter-card .form-label {
    margin-bottom: 5px;

    font-size: 9px;
    font-weight: 750;

    color: #475569;
}

.payment-filter-card .form-control,
.payment-filter-card .form-select {
    min-height: 42px;

    border: 1px solid #CBD5E1;
    border-radius: 10px;

    background: #FBFDFC;

    font-size: 10.5px;
}

.payment-filter-card .form-control:focus,
.payment-filter-card .form-select:focus {
    border-color: #10B981;

    box-shadow:
        0 0 0 3px rgba(16,185,129,.08);
}

.payment-filter-actions {
    display: flex;
    align-items: end;

    gap: 8px;
}

.payment-filter-btn {
    min-height: 42px;

    padding: 0 16px;

    border: 0;
    border-radius: 10px;

    background: #047857;

    color: #FFFFFF;

    font-size: 9.5px;
    font-weight: 800;
}

.payment-filter-btn:hover {
    background: #065F46;
}

.payment-reset-btn {
    min-height: 42px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 14px;

    border: 1px solid #CBD5E1;
    border-radius: 10px;

    background: #FFFFFF;

    color: #64748B;

    text-decoration: none;

    font-size: 9.5px;
    font-weight: 700;
}


/* =========================================================
   RESULTS
========================================================= */

.payment-results-card {
    padding: 20px;

    background: #FFFFFF;

    border: 1px solid #E2E8F0;
    border-radius: 18px;

    box-shadow:
        0 6px 18px rgba(15,23,42,.03);
}

.payment-results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    gap: 16px;

    margin-bottom: 15px;
}

.payment-results-header h4 {
    margin: 0;

    font-size: 19px;
    font-weight: 850;

    color: #0F172A;
}

.payment-results-header p {
    margin: 4px 0 0;

    font-size: 10px;

    color: #64748B;
}

.payment-results-count {
    padding: 6px 10px;

    border: 1px solid #E2E8F0;
    border-radius: 999px;

    background: #F8FAFC;

    color: #475569;

    font-size: 8.5px;
    font-weight: 800;
}


/* =========================================================
   TABLE
========================================================= */

.payment-table-wrap {
    overflow-x: auto;

    border: 1px solid #E8EEF3;
    border-radius: 14px;
}

.payment-table {
    width: 100%;
    min-width: 1150px;

    border-collapse: collapse;
}

.payment-table thead {
    background: #F8FAFC;
}

.payment-table th {
    padding: 11px 12px;

    border-bottom: 1px solid #E2E8F0;

    font-size: 7.5px;
    font-weight: 850;

    text-transform: uppercase;
    letter-spacing: .45px;

    color: #64748B;

    text-align: left;
}

.payment-table td {
    padding: 14px 12px;

    border-bottom: 1px solid #EEF2F7;

    font-size: 9.5px;

    color: #334155;

    vertical-align: middle;
}

.payment-row {
    cursor: pointer;

    transition:
        background .15s ease,
        box-shadow .15s ease;
}

.payment-row:hover {
    background: #F5FCF8;
}

.payment-row:hover td:first-child {
    box-shadow:
        inset 3px 0 0 #10B981;
}

.payment-person {
    font-weight: 800;

    color: #0F172A;
}

.payment-email {
    margin-top: 2px;

    font-size: 8px;

    color: #94A3B8;
}

.payment-money {
    font-weight: 850;

    color: #0F172A;
}

.payment-fee {
    color: #DC2626;
    font-weight: 850;
}

.payment-teacher-share {
    color: #047857;
    font-weight: 850;
}

.payment-status {
    display: inline-flex;

    padding: 5px 8px;

    border-radius: 999px;

    background: #DCFCE7;

    color: #047857;

    font-size: 7.5px;
    font-weight: 800;
}

.payment-status.refunded {
    background: #FEE2E2;

    color: #B91C1C;
}

.payment-arrow {
    width: 30px;
    height: 30px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #ECFDF5;

    color: #047857;

    font-weight: 850;
}

.payment-row:hover .payment-arrow {
    background: #047857;

    color: #FFFFFF;
}

.payment-empty {
    padding: 42px 20px;

    text-align: center;

    color: #94A3B8;

    font-size: 10.5px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1200px) {

    .payment-stats-grid {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }
}

@media(max-width: 750px) {

    .payment-stats-grid {
        grid-template-columns: 1fr;
    }

    .payment-overview-header,
    .payment-results-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .payment-filter-actions {
        align-items: stretch;
    }
}

</style>


<div class="admin-payments-page">


{{-- =========================================================
   OVERVIEW
========================================================= --}}

<div class="payment-overview">

    <div class="payment-overview-header">

        <div>

            <h3>
                Payment Overview
            </h3>

            <p>
                Platform payments, commissions and teacher earnings.
            </p>

        </div>


        <div class="payment-total-pill">

            {{ number_format($totalPayments) }}

            transactions

        </div>

    </div>


    <div class="payment-stats-grid">


        <div class="payment-stat-card">

            <div class="payment-stat-label">
                Gross Payments
            </div>

            <div
                class="payment-stat-value"
                title="${{ number_format($grossPayments, 2) }}"
            >
                ${{ number_format($grossPayments, 2) }}
            </div>

            <div class="payment-stat-description">
                Paid by students
            </div>

        </div>



        <div class="payment-stat-card">

            <div class="payment-stat-label">
                DancePair Revenue
            </div>

            <div
                class="payment-stat-value platform"
                title="${{ number_format($dancePairRevenue, 2) }}"
            >
                ${{ number_format($dancePairRevenue, 2) }}
            </div>

            <div class="payment-stat-description">
                Platform fees
            </div>

        </div>



        <div class="payment-stat-card">

            <div class="payment-stat-label">
                Teacher Earnings
            </div>

            <div
                class="payment-stat-value teacher"
                title="${{ number_format($teacherEarnings, 2) }}"
            >
                ${{ number_format($teacherEarnings, 2) }}
            </div>

            <div class="payment-stat-description">
                Teacher share
            </div>

        </div>



        <div class="payment-stat-card">

            <div class="payment-stat-label">
                Refunded Amount
            </div>

            <div
                class="payment-stat-value"
                title="${{ number_format($refundedAmount, 2) }}"
            >
                ${{ number_format($refundedAmount, 2) }}
            </div>

            <div class="payment-stat-description">
                Total refunds
            </div>

        </div>

    </div>


    <div class="payment-status-strip">

        <span class="payment-status-chip paid">

            Paid
            {{ number_format($paidPaymentsCount) }}

        </span>


        <span class="payment-status-chip refunded">

            Refunded
            {{ number_format($refundedPaymentsCount) }}

        </span>

    </div>

</div>



{{-- =========================================================
   FILTERS
========================================================= --}}

<div class="payment-filter-card">

    <div class="payment-filter-header">

        <h4>
            Find Payments
        </h4>

        <p>
            Search transactions without loading the entire payment history.
        </p>

    </div>


    <form
        method="GET"
        action="{{ route('admin.payments') }}"
    >

        <div class="row g-3">


            <div class="col-xl-4 col-md-6">

                <label class="form-label">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    value="{{ request('search') }}"
                    placeholder="Name, email, transaction ID or dance..."
                >

            </div>



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

                    @foreach($statuses as $status)

                        <option
                            value="{{ $status }}"
                            @selected(
                                request('status') === $status
                            )
                        >
                            {{ ucfirst($status) }}
                        </option>

                    @endforeach

                </select>

            </div>



            <div class="col-xl-2 col-md-3">

                <label class="form-label">
                    Provider
                </label>

                <select
                    name="provider"
                    class="form-select"
                >

                    <option value="">
                        All
                    </option>

                    @foreach($providers as $provider)

                        <option
                            value="{{ $provider }}"
                            @selected(
                                request('provider') === $provider
                            )
                        >
                            {{ ucfirst($provider) }}
                        </option>

                    @endforeach

                </select>

            </div>



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
                            @selected(
                                (string) request('teacher_id')
                                ===
                                (string) $teacher->id
                            )
                        >
                            {{ $teacher->user?->name ?? 'Teacher' }}
                        </option>

                    @endforeach

                </select>

            </div>



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
                            @selected(
                                (string) request('student_id')
                                ===
                                (string) $student->id
                            )
                        >
                            {{ $student->user?->name ?? 'Student' }}
                        </option>

                    @endforeach

                </select>

            </div>



            <div class="col-xl-2 col-md-4">

                <label class="form-label">
                    Refund
                </label>

                <select
                    name="refund"
                    class="form-select"
                >

                    <option value="">
                        All
                    </option>

                    <option
                        value="not_refunded"
                        @selected(request('refund') === 'not_refunded')
                    >
                        Not Refunded
                    </option>

                    <option
                        value="refunded"
                        @selected(request('refund') === 'refunded')
                    >
                        Refunded
                    </option>

                </select>

            </div>



            <div class="col-xl-2 col-md-8 payment-filter-actions">

                <button
                    type="submit"
                    class="payment-filter-btn"
                >
                    Apply Filters
                </button>


                <a
                    href="{{ route('admin.payments') }}"
                    class="payment-reset-btn"
                >
                    Reset
                </a>

            </div>

        </div>

    </form>

</div>



{{-- =========================================================
   RESULTS ONLY AFTER FILTERING
========================================================= --}}

@if($hasFilters)

    <div class="payment-results-card">

        <div class="payment-results-header">

            <div>

                <h4>
                    Search Results
                </h4>

                <p>
                    Transactions matching your selected filters.
                </p>

            </div>


            <div class="payment-results-count">

                {{ number_format(
                    $payments?->total() ?? 0
                ) }}

                results

            </div>

        </div>


        @if($payments && $payments->count())

            <div class="payment-table-wrap">

                <table class="payment-table">

                    <thead>

                        <tr>

                            <th>Student</th>
                            <th>Teacher</th>
                            <th>Dance</th>
                            <th>Gross</th>
                            <th>DancePair</th>
                            <th>Teacher</th>
                            <th>Provider</th>
                            <th>Paid At</th>
                            <th>Status</th>
                            <th></th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($payments as $payment)

                            <tr
                                class="payment-row"
                                onclick="window.location.href='{{ route('admin.payments.show', $payment) }}'"
                            >


                                <td>

                                    <div class="payment-person">

                                        {{ $payment
                                            ->student
                                            ?->user
                                            ?->name
                                            ?? '—'
                                        }}

                                    </div>

                                    <div class="payment-email">

                                        {{ $payment
                                            ->student
                                            ?->user
                                            ?->email
                                            ?? ''
                                        }}

                                    </div>

                                </td>



                                <td>

                                    <div class="payment-person">

                                        {{ $payment
                                            ->teacher
                                            ?->user
                                            ?->name
                                            ?? '—'
                                        }}

                                    </div>

                                </td>



                                <td>

                                    {{ $payment
                                        ->booking
                                        ?->danceStyle
                                        ?->name
                                        ?? '—'
                                    }}

                                </td>



                                <td class="payment-money">

                                    ${{ number_format(
                                        (float) $payment->amount,
                                        2
                                    ) }}

                                </td>



                                <td class="payment-fee">

                                    ${{ number_format(
                                        (float) $payment->platform_fee,
                                        2
                                    ) }}

                                </td>



                                <td class="payment-teacher-share">

                                    ${{ number_format(
                                        (float) $payment->teacher_amount,
                                        2
                                    ) }}

                                </td>



                                <td>

                                    {{ ucfirst(
                                        $payment->payment_provider
                                        ?? '—'
                                    ) }}

                                </td>



                                <td>

                                    {{ optional(
                                        $payment->paid_at
                                    )->format(
                                        'M d, Y · g:i A'
                                    ) ?? '—' }}

                                </td>



                                <td>

                                    @if($payment->refunded_at)

                                        <span class="payment-status refunded">
                                            Refunded
                                        </span>

                                    @else

                                        <span class="payment-status">
                                            {{ ucfirst(
                                                $payment->status ?? 'Paid'
                                            ) }}
                                        </span>

                                    @endif

                                </td>



                                <td>

                                    <span class="payment-arrow">
                                        →
                                    </span>

                                </td>


                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            @if($payments->hasPages())

                <div class="mt-4">

                    {{ $payments->links() }}

                </div>

            @endif


        @else

            <div class="payment-empty">

                No payments match your search or filters.

            </div>

        @endif

    </div>

@endif


</div>

@endsection