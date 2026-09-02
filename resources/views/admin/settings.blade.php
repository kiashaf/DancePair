@extends('admin.layout')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')

<style>

.settings-section {
    padding: 22px;

    border: 1px solid #D8E8DE;
    border-radius: 16px;

    background: rgba(255, 255, 255, .55);
}

.settings-section + .settings-section {
    margin-top: 22px;
}

.settings-section-title {
    font-size: 18px;
    font-weight: 700;

    margin-bottom: 4px;
}

.settings-section-subtitle {
    font-size: 12px;

    color: #6B7280;

    margin-bottom: 18px;
}


/* =========================================================
   COMMISSION INPUT
========================================================= */

.commission-input-wrap {
    position: relative;
}

.commission-input-wrap input {
    padding-right: 48px;
}

.commission-symbol {
    position: absolute;

    right: 16px;
    top: 50%;

    transform: translateY(-50%);

    color: #6B7280;

    font-weight: 700;
}


/* =========================================================
   CURRENT COMMISSION
========================================================= */

.current-commission-card {
    padding: 18px;

    border: 1px solid #D1FAE5;
    border-radius: 14px;

    background:
        linear-gradient(
            135deg,
            #F0FDF4 0%,
            #ECFDF5 100%
        );
}

.current-commission-label {
    margin-bottom: 5px;

    font-size: 9px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .5px;

    color: #64748B;
}

.current-commission-value {
    font-size: 30px;
    line-height: 1;

    font-weight: 850;

    color: #047857;
}


/* =========================================================
   COMMISSION PREVIEW
========================================================= */

.commission-preview {
    margin-top: 16px;

    padding: 15px 16px;

    border-radius: 12px;

    background: #F8FAFC;

    border: 1px solid #E2E8F0;
}

.commission-preview-title {
    margin-bottom: 10px;

    font-size: 10px;
    font-weight: 800;

    color: #334155;
}

.commission-preview-row {
    display: flex;
    justify-content: space-between;
    align-items: center;

    gap: 20px;

    margin-bottom: 6px;

    font-size: 11px;

    color: #64748B;
}

.commission-preview-row:last-child {
    margin-bottom: 0;
}

.commission-preview-row strong {
    font-size: 11px;
}

.commission-preview-row.platform strong {
    color: #DC2626;
}

.commission-preview-row.teacher strong {
    color: #047857;
}


/* =========================================================
   COMMISSION HISTORY
========================================================= */

.commission-history-list {
    display: flex;
    flex-direction: column;

    gap: 10px;
}

.commission-history-item {
    display: grid;

    grid-template-columns:
        150px
        minmax(160px, .8fr)
        minmax(180px, 1fr);

    gap: 18px;

    align-items: center;

    padding: 14px 16px;

    border: 1px solid #E2E8F0;
    border-radius: 12px;

    background: #FFFFFF;
}

.commission-history-date {
    font-size: 9px;

    color: #64748B;
}

.commission-history-date strong {
    display: block;

    margin-bottom: 2px;

    font-size: 10px;
    font-weight: 800;

    color: #0F172A;
}

.commission-history-change {
    display: flex;
    align-items: center;

    gap: 8px;
}

.commission-history-old {
    padding: 5px 8px;

    border-radius: 8px;

    background: #F1F5F9;

    color: #475569;

    font-size: 9px;
    font-weight: 800;
}

.commission-history-arrow {
    color: #94A3B8;

    font-size: 12px;
}

.commission-history-new {
    padding: 5px 8px;

    border-radius: 8px;

    background: #FEE2E2;

    color: #DC2626;

    font-size: 9px;
    font-weight: 850;
}

.commission-history-user {
    font-size: 9px;

    color: #64748B;
}

.commission-history-user strong {
    display: block;

    margin-bottom: 2px;

    color: #0F172A;

    font-size: 10px;
    font-weight: 750;
}

.commission-history-empty {
    padding: 35px 20px;

    text-align: center;

    border: 1px dashed #CBD5E1;
    border-radius: 12px;

    background: #F8FAFC;

    color: #94A3B8;

    font-size: 10px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 850px) {

    .commission-history-item {
        grid-template-columns: 1fr;

        gap: 9px;
    }
}

</style>


<div class="admin-page-card">

    <div class="admin-page-header">

        <div>

            <h3 class="mb-1">
                Admin Settings
            </h3>

            <p class="text-muted mb-0">
                Manage your administrator account and platform settings.
            </p>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger mb-4">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('admin.settings.update') }}"
    >

        @csrf
        @method('PUT')


        {{-- =====================================================
           ADMIN ACCOUNT
        ====================================================== --}}

        <div class="settings-section">

            <div class="settings-section-title">
                Administrator Account
            </div>

            <div class="settings-section-subtitle">
                Manage your name, email and password.
            </div>


            <div class="row g-4">


                <div class="col-md-6">

                    <label class="form-label">
                        Admin Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $admin->name) }}"
                        required
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $admin->email) }}"
                        required
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        New Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Leave blank to keep current password"
                        autocomplete="new-password"
                    >

                    <small class="text-muted">
                        Minimum 8 characters.
                    </small>

                </div>

            </div>

        </div>



        {{-- =====================================================
           PLATFORM COMMISSION
        ====================================================== --}}

        <div class="settings-section">

            <div class="settings-section-title">
                DancePair Commission
            </div>

            <div class="settings-section-subtitle">
                Set the percentage DancePair keeps from each lesson payment.
            </div>


            <div class="row g-4 align-items-start">


                {{-- CURRENT / INPUT --}}
                <div class="col-lg-4">


                    <div class="current-commission-card mb-3">

                        <div class="current-commission-label">
                            Current Commission
                        </div>

                        <div class="current-commission-value">

                            {{ number_format(
                                (float) $platformCommissionPercent,
                                2
                            ) }}%

                        </div>

                    </div>


                    <label class="form-label">
                        New Commission Percentage
                    </label>


                    <div class="commission-input-wrap">

                        <input
                            type="number"
                            id="platformCommissionPercent"
                            name="platform_commission_percent"
                            class="form-control"
                            min="0"
                            max="100"
                            step="0.01"
                            value="{{ old(
                                'platform_commission_percent',
                                $platformCommissionPercent
                            ) }}"
                            required
                        >

                        <span class="commission-symbol">
                            %
                        </span>

                    </div>


                    <small class="text-muted">
                        Enter a value between 0% and 100%.
                    </small>

                </div>



     <!--            {{-- LIVE PREVIEW --}}
                <div class="col-lg-5">

                    <div class="commission-preview">

                        <div class="commission-preview-title">
                            Example on a $100 lesson
                        </div>


                        <div class="commission-preview-row">

                            <span>
                                Student pays
                            </span>

                            <strong>
                                $100.00
                            </strong>

                        </div>


                        <div class="commission-preview-row platform">

                            <span>
                                DancePair receives
                            </span>

                            <strong id="platformPreview">
                                $0.00
                            </strong>

                        </div>


                        <div class="commission-preview-row teacher">

                            <span>
                                Teacher receives
                            </span>

                            <strong id="teacherPreview">
                                $0.00
                            </strong>

                        </div>

                    </div>

                </div>


            </div>

        </div>

 -->

        {{-- =====================================================
           SAVE
        ====================================================== --}}

        <div class="mt-4">

            <button
                type="submit"
                class="btn btn-dark px-4"
            >
                Save Changes
            </button>

        </div>

    </form>



    {{-- =====================================================
       COMMISSION HISTORY
    ====================================================== --}}

    <div class="settings-section">

        <div class="settings-section-title">
            Commission History
        </div>

        <div class="settings-section-subtitle">
            Complete history of DancePair commission changes.
        </div>


        @if($commissionHistory->count())


            <div class="commission-history-list">


                @foreach($commissionHistory as $history)


                    <div class="commission-history-item">


                        {{-- DATE --}}
                        <div class="commission-history-date">

                            <strong>
                                {{ $history
                                    ->created_at
                                    ->format('M d, Y')
                                }}
                            </strong>

                            {{ $history
                                ->created_at
                                ->format('g:i A')
                            }}

                        </div>



                        {{-- OLD → NEW --}}
                        <div class="commission-history-change">


                            <span class="commission-history-old">

                                @if($history->old_percentage !== null)

                                    {{ number_format(
                                        (float) $history->old_percentage,
                                        2
                                    ) }}%

                                @else

                                    —

                                @endif

                            </span>


                            <span class="commission-history-arrow">
                                →
                            </span>


                            <span class="commission-history-new">

                                {{ number_format(
                                    (float) $history->new_percentage,
                                    2
                                ) }}%

                            </span>


                        </div>



                        {{-- CHANGED BY --}}
                        <div class="commission-history-user">

                            <strong>

                                {{ $history
                                    ->changedBy
                                    ?->name
                                    ?? 'Unknown Admin'
                                }}

                            </strong>

                            Changed platform commission

                        </div>


                    </div>


                @endforeach


            </div>


        @else


            <div class="commission-history-empty">

                No commission changes have been recorded yet.

            </div>


        @endif

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const commissionInput =
            document.getElementById(
                'platformCommissionPercent'
            );

        const platformPreview =
            document.getElementById(
                'platformPreview'
            );

        const teacherPreview =
            document.getElementById(
                'teacherPreview'
            );


        function updateCommissionPreview() {

            let percentage =
                parseFloat(
                    commissionInput.value
                );


            if (isNaN(percentage)) {

                percentage = 0;
            }


            if (percentage < 0) {

                percentage = 0;
            }


            if (percentage > 100) {

                percentage = 100;
            }


            const exampleAmount = 100;


            const platformAmount =
                exampleAmount *
                (percentage / 100);


            const teacherAmount =
                exampleAmount -
                platformAmount;


            platformPreview.textContent =
                '$' +
                platformAmount.toFixed(2);


            teacherPreview.textContent =
                '$' +
                teacherAmount.toFixed(2);
        }


        commissionInput.addEventListener(
            'input',
            updateCommissionPreview
        );


        updateCommissionPreview();

    }
);

</script>

@endsection