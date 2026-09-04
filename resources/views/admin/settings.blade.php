@extends('admin.layout')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')

<style>

/* =========================================================
   SETTINGS
========================================================= */

.settings-section {
    padding: 22px;
    border: 1px solid #D8E8DE;
    border-radius: 16px;
    background: rgba(255, 255, 255, .62);
}

.settings-section + .settings-section {
    margin-top: 22px;
}

.settings-section-title {
    margin-bottom: 4px;
    font-size: 18px;
    font-weight: 750;
    color: #0F172A;
}

.settings-section-subtitle {
    margin-bottom: 18px;
    font-size: 12px;
    color: #6B7280;
}

.settings-label {
    display: block;
    margin-bottom: 6px;
    font-size: 11px;
    font-weight: 700;
    color: #334155;
}

.settings-help {
    display: block;
    margin-top: 6px;
    font-size: 10px;
    color: #94A3B8;
}


/* =========================================================
   COMMISSION
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

.commission-preview {
    padding: 18px;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    background: #F8FAFC;
}

.commission-preview-title {
    margin-bottom: 14px;
    font-size: 11px;
    font-weight: 800;
    color: #334155;
}

.commission-preview-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 7px 0;
    font-size: 11px;
    color: #64748B;
}

.commission-preview-row + .commission-preview-row {
    border-top: 1px solid #E8EEF4;
}

.commission-preview-row strong {
    font-size: 11px;
    color: #0F172A;
}

.commission-preview-row.platform strong {
    color: #DC2626;
}

.commission-preview-row.teacher strong {
    color: #047857;
}


/* =========================================================
   BUTTONS
========================================================= */

.dp-settings-btn {
    height: 38px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 15px;

    border: 1px solid transparent;
    border-radius: 9px;

    font-family: inherit;
    font-size: 10px;
    font-weight: 750;

    line-height: 1;
    white-space: nowrap;

    cursor: pointer;

    transition:
        background .16s ease,
        border-color .16s ease,
        color .16s ease,
        box-shadow .16s ease,
        transform .16s ease;
}

.dp-settings-btn:hover {
    transform: translateY(-1px);
}


/* GENERAL SAVE */

.dp-btn-general-save {
    color: #FFFFFF;

    background:
        linear-gradient(
            135deg,
            #111827,
            #334155
        );

    box-shadow:
        0 7px 17px
        rgba(15, 23, 42, .13);
}


/* ADD */

.dp-btn-add {
    color: #FFFFFF;

    background:
        linear-gradient(
            110deg,
            #F72585,
            #8B3DFF
        );

    box-shadow:
        0 8px 20px
        rgba(180, 40, 170, .18);
}

.dp-btn-add:hover {
    box-shadow:
        0 10px 24px
        rgba(180, 40, 170, .26);
}


/* EDIT */

.dp-btn-edit {
    min-width: 70px;

    color: #1D4ED8;

    border-color: #BFDBFE;

    background:
        linear-gradient(
            135deg,
            #EFF6FF,
            #DBEAFE
        );
}

.dp-btn-edit:hover {
    border-color: #93C5FD;
    background: #DBEAFE;
}


/* SAVE DANCE TYPE */

.dp-btn-save {
    display: none;

    min-width: 70px;

    color: #FFFFFF;

    background: #168756;

    border-color: #168756;

    box-shadow:
        0 6px 15px
        rgba(22, 135, 86, .14);
}

.dp-btn-save:hover {
    background: #117247;
}


/* CANCEL */

.dp-btn-cancel {
    display: none;

    min-width: 70px;

    color: #475569;

    border-color: #CBD5E1;

    background: #FFFFFF;
}

.dp-btn-cancel:hover {
    background: #F8FAFC;
}


/* =========================================================
   SAVE GENERAL SETTINGS
========================================================= */

.settings-save-bar {
    display: flex;
    justify-content: flex-end;

    margin-top: 20px;
}


/* =========================================================
   DANCE TYPES HEADER
========================================================= */

.dance-types-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 20px;

    flex-wrap: wrap;
}

.dance-types-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 72px;

    padding: 7px 11px;

    border: 1px solid #D8E8DE;
    border-radius: 999px;

    background: #F8FBF9;

    font-size: 10px;
    font-weight: 750;

    color: #475569;
}


/* =========================================================
   ADD DANCE TYPE
========================================================= */

.dance-type-add-card {
    margin-top: 20px;

    padding: 15px 16px;

    border: 1px solid #DCE7E1;
    border-radius: 13px;

    background:
        linear-gradient(
            135deg,
            #F8FBF9,
            #F4F8F6
        );
}

.dance-type-add-title {
    margin-bottom: 13px;

    font-size: 11px;
    font-weight: 800;

    color: #0F172A;
}

.dance-type-add-row {
    display: grid;

    grid-template-columns:
        minmax(240px, 1fr)
        150px
        165px;

    gap: 12px;

    align-items: end;
}

.dance-type-add-row .form-control {
    height: 40px;
}

.dance-active-toggle {
    height: 40px;

    display: flex;
    align-items: center;
}


/* =========================================================
   DANCE TYPE LIST
========================================================= */

.dance-types-list {
    display: flex;
    flex-direction: column;

    gap: 8px;

    margin-top: 18px;
}


/* EACH ROW */

.dance-type-item {
    padding: 10px 14px;

    border: 1px solid #E2E8F0;
    border-radius: 11px;

    background: #FFFFFF;

    transition:
        border-color .18s ease,
        box-shadow .18s ease,
        background .18s ease;
}

.dance-type-item:hover {
    border-color: #CADCD1;

    background: #FDFEFE;

    box-shadow:
        0 4px 12px
        rgba(15, 23, 42, .025);
}


/* =========================================================
   ONE ROW LAYOUT
========================================================= */

/*
    LEFT:
    Dance Type name

    MIDDLE:
    intentionally empty

    RIGHT:
    Status + Edit
*/

.dance-type-row {
    display: grid;

    grid-template-columns:
        300px
        minmax(80px, 1fr)
        150px
        165px;

    grid-template-areas:
        "name . status edit";

    column-gap: 16px;

    align-items: end;

    width: 100%;

    min-height: 44px;
}


/*
|--------------------------------------------------------------------------
| UPDATE FORM CHILDREN JOIN THE PARENT GRID
|--------------------------------------------------------------------------
*/

.dance-type-update-form {
    display: contents;
}


/* =========================================================
   NAME
========================================================= */

.dance-type-name-cell {
    grid-area: name;

    width: 300px;
    min-width: 0;
}

.dance-type-field-label {
    display: block;

    margin-bottom: 5px;

    font-size: 8px;
    font-weight: 700;

    letter-spacing: .04em;

    text-transform: uppercase;

    color: #64748B;
}


/* NORMAL NAME */

.dance-type-name-display {
    height: 38px;

    display: flex;
    align-items: center;

    padding: 0 4px;

    border: 0;

    background: transparent;

    color: #1F2937;

    font-size: 11px;

    font-weight: 500;
}


/* EDIT INPUT */

.dance-type-name-input {
    display: none;

    width: 300px;
    height: 38px;

    padding: 0 11px;

    border: 1px solid #A7D7B8;
    border-radius: 8px;

    background: #FFFFFF;

    color: #1F2937;

    font-size: 11px;
    font-weight: 500;

    outline: none;
}

.dance-type-name-input:focus {
    border-color: #57A778;

    box-shadow:
        0 0 0 3px
        rgba(22, 135, 86, .08);
}


/* =========================================================
   STATUS
========================================================= */

.dance-record-status-form {
    grid-area: status;

    width: 150px;

    margin: 0;

    justify-self: end;
}

.dance-record-status-wrap {
    height: 38px;

    display: flex;
    align-items: center;
}

.dance-record-status-wrap .form-check {
    display: flex;
    align-items: center;

    gap: 8px;

    margin: 0;
    padding-left: 0;
}

.dance-record-status-wrap .form-check-input {
    position: relative;

    float: none;

    width: 38px;
    height: 20px;

    margin: 0;

    flex: 0 0 38px;

    cursor: pointer;
}

.dance-record-status-wrap .form-check-label {
    margin: 0;

    color: #334155;

    font-size: 10px;
    font-weight: 500;

    cursor: pointer;
}


/* =========================================================
   EDIT
========================================================= */

.dance-edit-column {
    grid-area: edit;

    min-width: 165px;

    justify-self: end;
}

.dance-edit-actions {
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: flex-end;

    gap: 7px;
}


/* =========================================================
   EDIT MODE
========================================================= */

.dance-type-item.is-editing {
    border-color: #B9DCC7;

    background: #FBFDFC;
}

.dance-type-item.is-editing .dance-type-name-display {
    display: none;
}

.dance-type-item.is-editing .dance-type-name-input {
    display: block;
}

.dance-type-item.is-editing .dp-btn-edit {
    display: none;
}

.dance-type-item.is-editing .dp-btn-save,
.dance-type-item.is-editing .dp-btn-cancel {
    display: inline-flex;
}


/* =========================================================
   EMPTY
========================================================= */

.dance-types-empty {
    padding: 30px 20px;

    text-align: center;

    border: 1px dashed #CBD5E1;
    border-radius: 12px;

    background: #F8FAFC;

    color: #94A3B8;

    font-size: 10px;
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

@media(max-width: 950px) {

    .dance-type-row {
        grid-template-columns:
            260px
            minmax(20px, 1fr)
            145px
            165px;

        grid-template-areas:
            "name . status edit";
    }

    .dance-type-name-cell {
        width: 260px;
    }

    .dance-type-name-input {
        width: 260px;
    }
}


@media(max-width: 850px) {

    .commission-history-item {
        grid-template-columns: 1fr;

        gap: 9px;
    }


    .dance-type-add-row {
        grid-template-columns:
            1fr
            140px;
    }


    .dance-type-add-action {
        grid-column: 1 / -1;
    }


    .dance-type-add-action .dp-settings-btn {
        width: 100%;
    }


    .dance-type-row {
        grid-template-columns:
            minmax(200px, 1fr)
            140px
            160px;

        grid-template-areas:
            "name status edit";

        column-gap: 12px;
    }


    .dance-type-name-cell {
        width: auto;
    }


    .dance-type-name-input {
        width: 100%;
    }
}


@media(max-width: 600px) {

    .settings-section {
        padding: 17px;
    }


    .dance-types-header {
        display: block;
    }


    .dance-types-count {
        margin-top: 10px;
    }


    .dance-type-add-row {
        grid-template-columns: 1fr;
    }


    .dance-type-add-action {
        grid-column: auto;
    }


    .dance-type-row {
        grid-template-columns:
            1fr
            auto;

        grid-template-areas:
            "name name"
            "status edit";

        row-gap: 8px;
        column-gap: 12px;
    }


    .dance-type-name-cell {
        width: 100%;
    }


    .dance-type-name-input {
        width: 100%;
    }


    .dance-record-status-form {
        width: auto;

        justify-self: start;
    }


    .dance-edit-column {
        min-width: 0;

        justify-self: end;
    }


    .settings-save-bar {
        justify-content: stretch;
    }


    .settings-save-bar .dp-settings-btn {
        width: 100%;
    }


    
}

</style>



<div class="admin-page-card">


    {{-- =====================================================
       PAGE HEADER
    ====================================================== --}}

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



    {{-- =====================================================
       SUCCESS MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div class="alert alert-success mb-4">

            {{ session('success') }}

        </div>

    @endif



    {{-- =====================================================
       VALIDATION ERRORS
    ====================================================== --}}

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



    {{-- =====================================================
       GENERAL SETTINGS FORM
    ====================================================== --}}

    <form
        method="POST"
        action="{{ route('admin.settings.update') }}"
    >

        @csrf
        @method('PUT')



        {{-- =================================================
           ADMIN ACCOUNT
        ================================================== --}}

        <div class="settings-section">

            <div class="settings-section-title">
                Administrator Account
            </div>


            <div class="settings-section-subtitle">
                Manage your name, email and password.
            </div>


            <div class="row g-4">


                <div class="col-md-6">

                    <label class="settings-label">
                        Admin Name
                    </label>


                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old(
                            'name',
                            $admin->name
                        ) }}"
                        required
                    >

                </div>



                <div class="col-md-6">

                    <label class="settings-label">
                        Email
                    </label>


                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old(
                            'email',
                            $admin->email
                        ) }}"
                        required
                    >

                </div>



                <div class="col-md-6">

                    <label class="settings-label">
                        New Password
                    </label>


                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Leave blank to keep current password"
                        autocomplete="new-password"
                    >


                    <small class="settings-help">
                        Minimum 8 characters.
                    </small>

                </div>

            </div>

        </div>



        {{-- =================================================
           PLATFORM COMMISSION
        ================================================== --}}

        <div class="settings-section">

            <div class="settings-section-title">
                DancePair Commission
            </div>


            <div class="settings-section-subtitle">
                Set the percentage DancePair keeps from each lesson payment.
            </div>


            <div class="row g-4 align-items-start">


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



                    <label class="settings-label">
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


                    <small class="settings-help">
                        Enter a value between 0% and 100%.
                    </small>

                </div>



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



        <div class="settings-save-bar">


            <button
                type="submit"
                class="
                    dp-settings-btn
                    dp-btn-general-save
                "
            >
                Save Changes
            </button>


        </div>

    </form>



    {{-- =====================================================
       DANCE TYPES
    ====================================================== --}}

    <div class="settings-section">


        <div class="dance-types-header">


            <div>


                <div class="settings-section-title">

                    {{ app()->getLocale() === 'fr'
                        ? 'Types de danse'
                        : 'Dance Types'
                    }}

                </div>


                <div class="settings-section-subtitle mb-0">

                    {{ app()->getLocale() === 'fr'
                        ? 'Ajoutez, modifiez, activez ou désactivez les styles disponibles sur DancePair.'
                        : 'Add, edit, activate or deactivate the dance styles available on DancePair.'
                    }}

                </div>


            </div>



            <div class="dance-types-count">

                {{ $danceStyles->count() }}

                {{ app()->getLocale() === 'fr'
                    ? ' styles'
                    : ' styles'
                }}

            </div>


        </div>



        {{-- =================================================
           ADD NEW DANCE TYPE
        ================================================== --}}

        <div class="dance-type-add-card">


            <div class="dance-type-add-title">

                + {{ app()->getLocale() === 'fr'
                    ? 'Ajouter un type de danse'
                    : 'Add Dance Type'
                }}

            </div>



            <form
                method="POST"
                action="{{ route(
                    'admin.settings.dance-styles.store'
                ) }}"
            >

                @csrf


                <div class="dance-type-add-row">


                    <div>


                        <label class="settings-label">

                            {{ app()->getLocale() === 'fr'
                                ? 'Nom'
                                : 'Name'
                            }}

                        </label>


                        <input
                            type="text"
                            name="dance_style_name"
                            class="form-control"
                            value="{{ old(
                                'dance_style_name'
                            ) }}"
                            placeholder="{{ app()->getLocale() === 'fr'
                                ? 'Ex. Kizomba'
                                : 'e.g. Kizomba'
                            }}"
                            required
                        >


                    </div>



                    <div>


                        <label class="settings-label">

                            {{ app()->getLocale() === 'fr'
                                ? 'Statut'
                                : 'Status'
                            }}

                        </label>


                        <input
                            type="hidden"
                            name="dance_style_active"
                            value="0"
                        >


                        <div class="dance-active-toggle">


                            <div class="form-check form-switch mb-0">


                                <input
                                    type="checkbox"
                                    name="dance_style_active"
                                    value="1"
                                    class="form-check-input"
                                    id="newDanceStyleActive"
                                    checked
                                >


                                <label
                                    class="form-check-label"
                                    for="newDanceStyleActive"
                                >

                                    {{ app()->getLocale() === 'fr'
                                        ? 'Actif'
                                        : 'Active'
                                    }}

                                </label>


                            </div>


                        </div>


                    </div>



                    <div class="dance-type-add-action">


                        <label class="settings-label">
                            &nbsp;
                        </label>


                        <button
                            type="submit"
                            class="
                                dp-settings-btn
                                dp-btn-add
                                w-100
                            "
                        >

                            {{ app()->getLocale() === 'fr'
                                ? 'Ajouter'
                                : 'Add Dance Type'
                            }}

                        </button>


                    </div>


                </div>


            </form>


        </div>



        {{-- =================================================
           EXISTING DANCE TYPES
        ================================================== --}}

        <div class="dance-types-list">


            @forelse($danceStyles as $danceStyle)


                <div
                    class="dance-type-item"
                    data-dance-item
                >


                    <div class="dance-type-row">


                        {{-- UPDATE NAME FORM --}}

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.settings.dance-styles.update',
                                $danceStyle
                            ) }}"
                            class="dance-type-update-form"
                        >

                            @csrf
                            @method('PUT')


                            <input
                                type="hidden"
                                name="dance_style_active"
                                value="{{ $danceStyle->active ? 1 : 0 }}"
                            >


                            <input
                                type="hidden"
                                name="dance_style_description"
                                value="{{ $danceStyle->description }}"
                            >



                            {{-- NAME --}}

                            <div class="dance-type-name-cell">


                            <!--     <label class="dance-type-field-label">

                                    {{ app()->getLocale() === 'fr'
                                        ? 'Nom'
                                        : 'Name'
                                    }}

                                </label> -->



                                <div
                                    class="dance-type-name-display"
                                    data-name-display
                                >

                                    {{ $danceStyle->name }}

                                </div>



                                <input
                                    type="text"
                                    name="dance_style_name"
                                    class="
                                        form-control
                                        dance-type-name-input
                                    "
                                    value="{{ $danceStyle->name }}"
                                    data-name-input
                                    required
                                >


                            </div>



                            {{-- EDIT --}}

                            <div class="dance-edit-column">


                            <!--     <label class="dance-type-field-label">

                                    {{ app()->getLocale() === 'fr'
                                        ? 'Modifier'
                                        : 'Edit'
                                    }}

                                </label> -->


                                <div class="dance-edit-actions">


                                    <button
                                        type="button"
                                        class="
                                            dp-settings-btn
                                            dp-btn-edit
                                        "
                                        data-edit-button
                                    >

                                        {{ app()->getLocale() === 'fr'
                                            ? 'Modifier'
                                            : 'Edit'
                                        }}

                                    </button>



                                    <button
                                        type="submit"
                                        class="
                                            dp-settings-btn
                                            dp-btn-save
                                        "
                                    >

                                        {{ app()->getLocale() === 'fr'
                                            ? 'Enregistrer'
                                            : 'Save'
                                        }}

                                    </button>



                                    <button
                                        type="button"
                                        class="
                                            dp-settings-btn
                                            dp-btn-cancel
                                        "
                                        data-cancel-button
                                    >

                                        {{ app()->getLocale() === 'fr'
                                            ? 'Annuler'
                                            : 'Cancel'
                                        }}

                                    </button>


                                </div>


                            </div>


                        </form>



                        {{-- STATUS AT FAR RIGHT --}}

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.settings.dance-styles.toggle',
                                $danceStyle
                            ) }}"
                            class="dance-record-status-form"
                            data-status-form
                        >

                            @csrf
                            @method('PATCH')


                        <!--     <label class="dance-type-field-label">

                                {{ app()->getLocale() === 'fr'
                                    ? 'Statut'
                                    : 'Status'
                                }}

                            </label> -->



                            <div class="dance-record-status-wrap">


                                <div class="form-check form-switch">


                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        id="danceStyleActive{{ $danceStyle->id }}"
                                        data-status-switch
                                        @checked($danceStyle->active)
                                    >


                                    <label
                                        class="form-check-label"
                                        for="danceStyleActive{{ $danceStyle->id }}"
                                    >

                                        {{ $danceStyle->active
                                            ? (
                                                app()->getLocale() === 'fr'
                                                    ? 'Actif'
                                                    : 'Active'
                                            )
                                            : (
                                                app()->getLocale() === 'fr'
                                                    ? 'Inactif'
                                                    : 'Inactive'
                                            )
                                        }}

                                    </label>


                                </div>


                            </div>


                        </form>


                    </div>


                </div>


            @empty


                <div class="dance-types-empty">

                    {{ app()->getLocale() === 'fr'
                        ? 'Aucun type de danse.'
                        : 'No dance types found.'
                    }}

                </div>


            @endforelse


        </div>


    </div>



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



                        <div class="commission-history-change">


                            <span class="commission-history-old">


                                @if(
                                    $history->old_percentage
                                    !==
                                    null
                                )

                                    {{ number_format(
                                        (float)
                                        $history->old_percentage,
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
                                    (float)
                                    $history->new_percentage,
                                    2
                                ) }}%

                            </span>


                        </div>



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


        /*
        |--------------------------------------------------------------------------
        | COMMISSION PREVIEW
        |--------------------------------------------------------------------------
        */

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


            if (
                !commissionInput
                ||
                !platformPreview
                ||
                !teacherPreview
            ) {
                return;
            }


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


            const exampleAmount =
                100;


            const platformAmount =
                exampleAmount
                *
                (
                    percentage / 100
                );


            const teacherAmount =
                exampleAmount
                -
                platformAmount;


            platformPreview.textContent =
                '$'
                +
                platformAmount.toFixed(
                    2
                );


            teacherPreview.textContent =
                '$'
                +
                teacherAmount.toFixed(
                    2
                );

        }



        if (commissionInput) {


            commissionInput.addEventListener(
                'input',
                updateCommissionPreview
            );


            updateCommissionPreview();

        }



        /*
        |--------------------------------------------------------------------------
        | DANCE TYPE INLINE EDIT
        |--------------------------------------------------------------------------
        */

        const danceItems =
            document.querySelectorAll(
                '[data-dance-item]'
            );



        danceItems.forEach(
            function (item) {


                const editButton =
                    item.querySelector(
                        '[data-edit-button]'
                    );


                const cancelButton =
                    item.querySelector(
                        '[data-cancel-button]'
                    );


                const input =
                    item.querySelector(
                        '[data-name-input]'
                    );


                const display =
                    item.querySelector(
                        '[data-name-display]'
                    );



                if (
                    !editButton
                    ||
                    !cancelButton
                    ||
                    !input
                    ||
                    !display
                ) {
                    return;
                }



                let originalValue =
                    input.value;



                editButton.addEventListener(
                    'click',
                    function () {


                        originalValue =
                            input.value;


                        danceItems.forEach(
                            function (otherItem) {


                                if (
                                    otherItem
                                    !==
                                    item
                                ) {

                                    otherItem
                                        .classList
                                        .remove(
                                            'is-editing'
                                        );

                                }


                            }
                        );


                        item
                            .classList
                            .add(
                                'is-editing'
                            );


                        input.focus();


                        input.select();


                    }
                );



                cancelButton.addEventListener(
                    'click',
                    function () {


                        input.value =
                            originalValue;


                        item
                            .classList
                            .remove(
                                'is-editing'
                            );


                    }
                );



                input.addEventListener(
                    'keydown',
                    function (event) {


                        if (
                            event.key
                            ===
                            'Escape'
                        ) {


                            input.value =
                                originalValue;


                            item
                                .classList
                                .remove(
                                    'is-editing'
                                );


                        }


                    }
                );


            }
        );



        /*
        |--------------------------------------------------------------------------
        | ACTIVE / INACTIVE SWITCH
        |--------------------------------------------------------------------------
        */

        const statusSwitches =
            document.querySelectorAll(
                '[data-status-switch]'
            );



        statusSwitches.forEach(
            function (statusSwitch) {


                statusSwitch.addEventListener(
                    'change',
                    function () {


                        const form =
                            statusSwitch.closest(
                                '[data-status-form]'
                            );


                        if (form) {


                            statusSwitch.disabled =
                                true;


                            form.submit();


                        }


                    }
                );


            }
        );


    }
);

</script>

@endsection