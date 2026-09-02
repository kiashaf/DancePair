@extends('admin.layout')

@section(
    'title',
    app()->getLocale() === 'fr'
        ? 'Messages DancePair'
        : 'DancePair Messages'
)

@section(
    'page-title',
    app()->getLocale() === 'fr'
        ? 'Messages DancePair'
        : 'DancePair Messages'
)

@section('content')

@php

    $fr = app()->getLocale() === 'fr';

    $audienceLabels = [

        'all_users' =>
            $fr
                ? 'Tous les professeurs et étudiants'
                : 'All Teachers & Students',

        'all_teachers' =>
            $fr
                ? 'Tous les professeurs'
                : 'All Teachers',

        'all_students' =>
            $fr
                ? 'Tous les étudiants'
                : 'All Students',

        'single_user' =>
            $fr
                ? 'Un utilisateur'
                : 'One User',

        'selected_users' =>
            $fr
                ? 'Utilisateurs sélectionnés'
                : 'Selected Users',
    ];

@endphp


<style>

/* =========================================================
   PAGE
========================================================= */

.dpm-page {

    max-width: 1180px;

    margin: 0 auto;

    display: flex;
    flex-direction: column;

    gap: 18px;
}


/* =========================================================
   ALERT
========================================================= */

.dpm-alert {

    padding: 12px 14px;

    border-radius: 10px;

    font-size: 11px;
}


.dpm-alert.success {

    background: #ecfdf5;

    border: 1px solid #a7f3d0;

    color: #047857;
}


.dpm-alert.error {

    background: #fef2f2;

    border: 1px solid #fecaca;

    color: #b91c1c;
}


.dpm-alert ul {

    margin: 0;

    padding-left: 18px;
}


/* =========================================================
   CARD
========================================================= */

.dpm-card {

    position: relative;

    background: #ffffff;

    border: 1px solid #d9e7ef;

    border-radius: 16px;

    box-shadow:
        0 6px 20px rgba(15, 23, 42, .04);
}


.dpm-card.form-card {

    overflow: visible;
}


.dpm-card.list-card {

    overflow: hidden;
}


/* =========================================================
   CARD HEADER
========================================================= */

.dpm-card-header {

    padding: 15px 20px;

    border-bottom: 1px solid #e8eef3;

    border-radius: 16px 16px 0 0;

    background: #f8fbfd;
}


.dpm-card-header h3 {

    margin: 0;

    color: #0f172a;

    font-size: 17px;
    font-weight: 800;
}


/* =========================================================
   FORM
========================================================= */

.dpm-form {

    position: relative;

    padding: 20px;
}


.dpm-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 16px;
}


.dpm-field {

    min-width: 0;
}


.dpm-field.full {

    grid-column: 1 / -1;
}


.dpm-label {

    display: block;

    margin-bottom: 6px;

    color: #334155;

    font-size: 10px;
    font-weight: 700;
}


/* =========================================================
   INPUTS
========================================================= */

.dpm-input,
.dpm-select,
.dpm-textarea {

    width: 100%;

    border: 1px solid #c8dbe7;

    border-radius: 8px;

    background: #ffffff;

    color: #1e293b;

    font-size: 11px;

    outline: none;

    transition:
        border-color .15s ease,
        box-shadow .15s ease;
}


.dpm-input,
.dpm-select {

    height: 40px;

    padding: 0 11px;
}


.dpm-textarea {

    min-height: 112px;

    padding: 10px 11px;

    resize: vertical;

    line-height: 1.5;
}


.dpm-input:focus,
.dpm-select:focus,
.dpm-textarea:focus {

    border-color: #0284c7;

    box-shadow:
        0 0 0 3px rgba(2, 132, 199, .07);
}


/* =========================================================
   IMPORTANCE
========================================================= */

.dpm-importance {

    grid-column: 1 / -1;
}


.dpm-importance-options {

    display: flex;
    align-items: center;

    gap: 24px;

    min-height: 32px;
}


.dpm-radio {

    display: inline-flex;
    align-items: center;

    gap: 6px;

    margin: 0;

    cursor: pointer;

    font-size: 10px;
    font-weight: 650;
}


.dpm-radio input {

    width: 15px;
    height: 15px;

    margin: 0;

    cursor: pointer;
}


.dpm-radio.normal {

    color: #0369a1;
}


.dpm-radio.normal input {

    accent-color: #0284c7;
}


.dpm-radio.important {

    color: #c2410c;
}


.dpm-radio.important input {

    accent-color: #f97316;
}


.dpm-radio.critical {

    color: #b91c1c;
}


.dpm-radio.critical input {

    accent-color: #dc2626;
}


.dpm-importance-help {

    margin-top: 2px;

    color: #94a3b8;

    font-size: 8px;
}


/* =========================================================
   AUDIENCE EXTRA
========================================================= */

.dpm-audience-extra {

    display: none;

    grid-column: 1 / -1;
}


.dpm-audience-extra.active {

    display: block;
}


/* =========================================================
   USER PICKER
========================================================= */

.dpm-user-picker {

    position: relative;

    width: 100%;
}


/* =========================================================
   PICKER BUTTON
========================================================= */

.dpm-user-picker-button {

    width: 100%;
    height: 40px;

    padding: 0 11px;

    display: flex;
    align-items: center;

    gap: 8px;

    border: 1px solid #c8dbe7;
    border-radius: 8px;

    background: #ffffff;

    color: #334155;

    font-family: inherit;
    font-size: 11px;
    font-weight: 500;

    text-align: left;

    cursor: pointer;

    transition:
        border-color .15s ease,
        box-shadow .15s ease;
}


.dpm-user-picker-button:hover {

    border-color: #9eb9ca;
}


.dpm-user-picker-button.open {

    border-color: #0284c7;

    box-shadow:
        0 0 0 3px rgba(2, 132, 199, .07);
}


.dpm-picker-placeholder {

    flex: 1;
}


/* =========================================================
   PICKER COUNT
========================================================= */

.dpm-picker-count {

    min-width: 24px;
    height: 20px;

    padding: 0 7px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 999px;

    background: #e0f2fe;

    color: #0369a1;

    font-size: 8px;
    font-weight: 800;
}


.dpm-picker-arrow {

    margin-left: 3px;

    color: #64748b;

    font-size: 12px;

    transition: transform .15s ease;
}


.dpm-user-picker-button.open
.dpm-picker-arrow {

    transform: rotate(180deg);
}


/* =========================================================
   PICKER DROPDOWN
========================================================= */

.dpm-user-picker-dropdown {

    display: none;

    position: absolute;

    top: calc(100% + 6px);

    left: 0;
    right: 0;

    z-index: 5000;

    overflow: hidden;

    border: 1px solid #d4e2eb;
    border-radius: 11px;

    background: #ffffff;

    box-shadow:
        0 16px 38px rgba(15, 23, 42, .14),
        0 4px 12px rgba(15, 23, 42, .06);
}


.dpm-user-picker-dropdown.open {

    display: block;
}


/* =========================================================
   SEARCH
========================================================= */

.dpm-picker-search-area {

    padding: 9px;

    border-bottom: 1px solid #e8eef3;

    background: #f8fbfd;
}


.dpm-picker-search {

    width: 100% !important;
    height: 36px !important;

    padding: 0 11px !important;

    border: 1px solid #c9dce7 !important;
    border-radius: 7px !important;

    background: #ffffff !important;

    color: #334155 !important;

    font-size: 10px !important;

    outline: none !important;
}


.dpm-picker-search:focus {

    border-color: #0284c7 !important;

    box-shadow:
        0 0 0 3px rgba(2, 132, 199, .06) !important;
}


/* =========================================================
   USERS LIST
========================================================= */

.dpm-picker-users {

    max-height: 260px;

    padding: 5px;

    overflow-y: auto;
}


/* =========================================================
   USER ROW
========================================================= */

.dpm-picker-user {

    width: 100%;

    min-height: 54px;

    margin: 0;

    padding: 7px 9px;

    display: flex;
    align-items: center;

    gap: 10px;

    border-radius: 8px;

    cursor: pointer;

    transition: background .12s ease;
}


.dpm-picker-user:hover {

    background: #f1f7fa;
}


.dpm-picker-user input {

    position: absolute;

    opacity: 0;

    pointer-events: none;
}


/* =========================================================
   CUSTOM CHECKBOX
========================================================= */

.dpm-picker-check {

    width: 17px;
    height: 17px;

    flex: 0 0 17px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1.5px solid #afc1cc;
    border-radius: 5px;

    background: #ffffff;

    transition:
        background .12s ease,
        border-color .12s ease;
}


.dpm-picker-user input:checked
+
.dpm-picker-check {

    background: #0284c7;

    border-color: #0284c7;
}


.dpm-picker-user input:checked
+
.dpm-picker-check::after {

    content: "✓";

    color: #ffffff;

    font-size: 10px;
    font-weight: 900;
}


/* =========================================================
   AVATAR
========================================================= */

.dpm-picker-avatar {

    width: 32px;
    height: 32px;

    flex: 0 0 32px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #e9f5fc;

    color: #0369a1;

    font-size: 11px;
    font-weight: 800;
}


/* =========================================================
   USER TEXT
========================================================= */

.dpm-picker-user-info {

    min-width: 0;

    flex: 1;
}


.dpm-picker-user-name {

    display: block;

    color: #0f172a;

    font-size: 10.5px;
    font-weight: 750;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}


.dpm-picker-user-email {

    display: block;

    margin-top: 2px;

    color: #94a3b8;

    font-size: 8.5px;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}


/* =========================================================
   ROLE
========================================================= */

.dpm-picker-role {

    flex: 0 0 auto;

    padding: 4px 7px;

    border-radius: 999px;

    background: #f1f5f9;

    color: #64748b;

    font-size: 7.5px;
    font-weight: 800;

    text-transform: uppercase;
}


.dpm-picker-role.teacher {

    background: #f3e8ff;

    color: #7e22ce;
}


.dpm-picker-role.student {

    background: #dbeafe;

    color: #1d4ed8;
}


/* =========================================================
   NO USER
========================================================= */

.dpm-no-user {

    padding: 24px 10px;

    text-align: center;

    color: #94a3b8;

    font-size: 9px;
}


/* =========================================================
   PICKER FOOTER
========================================================= */

.dpm-picker-footer {

    min-height: 44px;

    padding: 7px 10px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    border-top: 1px solid #e8eef3;

    background: #f8fbfd;
}


.dpm-picker-footer-count {

    color: #64748b;

    font-size: 9px;
}


.dpm-picker-footer-count strong {

    color: #0284c7;

    font-weight: 800;
}


.dpm-picker-done {

    height: 30px;

    padding: 0 14px;

    border: 0;
    border-radius: 7px;

    background: #0284c7;

    color: #ffffff;

    font-family: inherit;

    font-size: 9px;
    font-weight: 750;

    cursor: pointer;
}


.dpm-picker-done:hover {

    background: #0369a1;
}


/* =========================================================
   ACTIVE
========================================================= */

.dpm-active-row {

    grid-column: 1 / -1;

    display: flex;
    align-items: center;

    gap: 7px;
}


.dpm-active-row input {

    width: 16px;
    height: 16px;

    margin: 0;

    accent-color: #16a34a;
}


.dpm-active-row label {

    margin: 0;

    color: #334155;

    font-size: 10px;
    font-weight: 700;
}


/* =========================================================
   SEND / PREVIEW BUTTONS
========================================================= */

.dpm-submit-row {

    grid-column: 1 / -1;

    display: flex;
    justify-content: flex-end;

    gap: 8px;
}


.dpm-send-btn,
.dpm-preview-btn {

    height: 40px;

    padding: 0 18px;

    border-radius: 8px;

    font-family: inherit;

    font-size: 10px;
    font-weight: 800;

    cursor: pointer;
}


.dpm-send-btn {

    min-width: 145px;

    border: 0;

    background: #0284c7;

    color: #ffffff;
}


.dpm-send-btn:hover {

    background: #0369a1;
}


.dpm-preview-btn {

    min-width: 105px;

    border: 1px solid #0284c7;

    background: #ffffff;

    color: #0369a1;
}


.dpm-preview-btn:hover {

    background: #f0f9ff;
}


/* =========================================================
   PREVIEW OVERLAY
========================================================= */

.dancepair-preview-overlay {

    display: none !important;

    position: fixed !important;

    inset: 0 !important;

    z-index: 999999 !important;

    align-items: center !important;
    justify-content: center !important;

    padding: 20px !important;

    background: rgba(15, 23, 42, .55) !important;

    backdrop-filter: blur(4px);
}


.dancepair-preview-overlay.is-open {

    display: flex !important;
}


/* =========================================================
   CLIENT MESSAGE BOX
========================================================= */

.dancepair-preview-box {

    width: 100%;
    max-width: 520px;

    overflow: hidden;

    border: 1px solid #dbe5ec;
    border-top: 5px solid #0284c7;
    border-radius: 18px;

    background: #ffffff;

    box-shadow:
        0 28px 80px rgba(15, 23, 42, .30);
}


.dancepair-preview-box.normal {

    border-top-color: #0284c7;
}


.dancepair-preview-box.important {

    border-top-color: #f97316;
}


.dancepair-preview-box.critical {

    border-top-color: #dc2626;
}


/* =========================================================
   PREVIEW HEADER
========================================================= */

.dancepair-preview-header {

min-height: 82px;

padding: 14px 22px;

display: flex;
align-items: center;
justify-content: space-between;

border-bottom: 1px solid #edf2f7;

background: #f8fbfd;
}

.dancepair-preview-brand {

display: flex;
align-items: center;

gap: 14px;
}

.dancepair-preview-logo {

width: 64px;
height: 64px;

flex: 0 0 64px;

display: flex;
align-items: center;
justify-content: center;

overflow: hidden;

border-radius: 16px;

background: #ffffff;
}

.dancepair-preview-logo img {

width: 100%;
height: 100%;

display: block;

object-fit: contain;
}

.dancepair-preview-brand strong {

color: #0f172a;

font-size: 18px;
font-weight: 850;
}

.dancepair-preview-x {

    width: 32px;
    height: 32px;

    border: 0;
    border-radius: 8px;

    background: transparent;

    color: #64748b;

    font-size: 21px;

    cursor: pointer;
}


.dancepair-preview-x:hover {

    background: #eef2f6;

    color: #0f172a;
}


/* =========================================================
   PREVIEW CONTENT
========================================================= */

.dancepair-preview-content {

    padding: 22px 20px 18px;
}


.dancepair-preview-content h3 {

    margin: 0 0 10px;

    color: #0f172a;

    font-size: 19px;
    font-weight: 850;

    line-height: 1.35;
}


#dancePairPreviewText {

    color: #475569;

    font-size: 12px;
    line-height: 1.7;

    white-space: pre-line;

    overflow-wrap: anywhere;
}


/* =========================================================
   PREVIEW IMPORTANCE BADGE
========================================================= */

.dancepair-preview-importance {

    width: fit-content;

    margin-bottom: 11px;

    padding: 5px 9px;

    border-radius: 999px;

    font-size: 8px;
    font-weight: 850;

    text-transform: uppercase;
}


.dancepair-preview-importance.normal {

    background: #e0f2fe;

    color: #0369a1;
}


.dancepair-preview-importance.important {

    background: #ffedd5;

    color: #c2410c;
}


.dancepair-preview-importance.critical {

    background: #fee2e2;

    color: #b91c1c;
}


/* =========================================================
   PREVIEW FOOTER
========================================================= */

.dancepair-preview-footer {

    padding: 0 20px 18px;

    display: flex;
    justify-content: flex-end;
}


.dancepair-preview-close {

    min-width: 90px;
    height: 36px;

    padding: 0 15px;

    border: 1px solid #cbd5e1;
    border-radius: 8px;

    background: #ffffff;

    color: #475569;

    font-size: 9px;
    font-weight: 700;

    cursor: pointer;
}


.dancepair-preview-close:hover {

    background: #f8fafc;
}


/* =========================================================
   SENT MESSAGE LIST
========================================================= */

.dpm-list {

    padding: 10px 14px 14px;
}


.dpm-list-head,
.dpm-message-row {

    display: grid;

    grid-template-columns:
        minmax(180px, 1.5fr)
        90px
        minmax(120px, 1fr)
        70px
        70px
        140px;

    align-items: center;

    gap: 10px;
}


.dpm-list-head {

    padding: 6px 10px;

    color: #94a3b8;

    font-size: 8px;
    font-weight: 800;

    text-transform: uppercase;
}


.dpm-message-row {

    min-height: 60px;

    margin-top: 5px;

    padding: 9px 10px;

    border: 1px solid #e5edf2;
    border-radius: 10px;

    background: #ffffff;
}


.dpm-message-row:hover {

    background: #fafcfd;
}


.dpm-message-main {

    min-width: 0;
}


.dpm-message-main strong {

    display: block;

    overflow: hidden;

    color: #0f172a;

    font-size: 11px;
    font-weight: 800;

    text-overflow: ellipsis;
    white-space: nowrap;
}


.dpm-message-main span {

    display: block;

    margin-top: 2px;

    color: #94a3b8;

    font-size: 8px;
}


/* =========================================================
   MESSAGE BADGE
========================================================= */

.dpm-badge {

    display: inline-flex;
    align-items: center;
    justify-content: center;

    width: fit-content;

    padding: 4px 7px;

    border-radius: 999px;

    font-size: 8px;
    font-weight: 800;
}


.dpm-badge.normal {

    background: #e0f2fe;

    color: #0369a1;
}


.dpm-badge.important {

    background: #ffedd5;

    color: #c2410c;
}


.dpm-badge.critical {

    background: #fee2e2;

    color: #b91c1c;
}

/* =========================================================
   SENT MESSAGE RECIPIENTS
========================================================= */

.dpm-audience-info {
    min-width: 0;
}


.dpm-audience-label {
    color: #64748b;

    font-size: 8.5px;
    font-weight: 700;
}


.dpm-recipient-list {
    margin-top: 5px;

    display: flex;
    flex-direction: column;

    gap: 5px;
}


.dpm-recipient-item {
    min-width: 0;
}


.dpm-recipient-top {
    display: flex;
    align-items: center;

    gap: 5px;
}


.dpm-recipient-top strong {
    max-width: 110px;

    overflow: hidden;

    color: #0f172a;

    font-size: 8.5px;
    font-weight: 800;

    text-overflow: ellipsis;
    white-space: nowrap;
}


.dpm-recipient-role {
    padding: 2px 5px;

    border-radius: 999px;

    background: #f1f5f9;

    color: #64748b;

    font-size: 6.5px;
    font-weight: 800;

    text-transform: uppercase;
}


.dpm-recipient-role.teacher {
    background: #f3e8ff;

    color: #7e22ce;
}


.dpm-recipient-role.student {
    background: #dbeafe;

    color: #1d4ed8;
}


.dpm-recipient-email {
    margin-top: 1px;

    max-width: 160px;

    overflow: hidden;

    color: #94a3b8;

    font-size: 7px;

    text-overflow: ellipsis;
    white-space: nowrap;
}
/* =========================================================
   LIST INFO
========================================================= */

.dpm-info {

    color: #64748b;

    font-size: 9px;
}


.dpm-info strong {

    color: #0f172a;

    font-weight: 800;
}


.dpm-unread {

    color: #dc2626;
}


.dpm-read {

    color: #15803d;
}


/* =========================================================
   ACTIONS
========================================================= */

.dpm-actions {

    display: flex;
    justify-content: flex-end;

    gap: 5px;
}


.dpm-action-btn {

    min-height: 29px;

    padding: 0 8px;

    border-radius: 7px;

    font-family: inherit;

    font-size: 8px;
    font-weight: 700;

    cursor: pointer;
}


.dpm-action-btn.toggle {

    border: 1px solid #cbd5e1;

    background: #ffffff;

    color: #475569;
}


.dpm-action-btn.delete {

    border: 1px solid #fecaca;

    background: #fff1f2;

    color: #b91c1c;
}


/* =========================================================
   EMPTY
========================================================= */

.dpm-empty {

    margin: 10px 14px 14px;

    padding: 28px 18px;

    border: 1px dashed #cbd5e1;
    border-radius: 10px;

    background: #fafcfd;

    text-align: center;

    color: #94a3b8;

    font-size: 10px;
}


/* =========================================================
   PAGINATION
========================================================= */

.dpm-pagination {

    padding: 5px 14px 15px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1000px) {

    .dpm-list-head {

        display: none;
    }


    .dpm-message-row {

        grid-template-columns:
            1fr
            1fr
            1fr;
    }
}


@media(max-width: 700px) {

    .dpm-grid {

        grid-template-columns: 1fr;
    }


    .dpm-importance-options {

        gap: 14px;
    }


    .dpm-message-row {

        grid-template-columns: 1fr;
    }


    .dpm-actions {

        justify-content: flex-start;
    }


    .dpm-submit-row {

        justify-content: stretch;

        flex-direction: column;
    }


    .dpm-send-btn,
    .dpm-preview-btn {

        width: 100%;
    }
}

</style>



<div class="dpm-page">


    {{-- =====================================================
       SUCCESS
    ====================================================== --}}

    @if(session('success'))

        <div class="dpm-alert success">

            {{ session('success') }}

        </div>

    @endif



    {{-- =====================================================
       ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div class="dpm-alert error">

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif



    {{-- =====================================================
       NEW MESSAGE
    ====================================================== --}}

    <div class="dpm-card form-card">


        <div class="dpm-card-header">

            <h3>

                {{ $fr
                    ? 'Nouveau message'
                    : 'New Message'
                }}

            </h3>

        </div>



        <form
            method="POST"
            action="{{ route('admin.platform-messages.store') }}"
            id="dpmMessageForm"
            class="dpm-form"
        >

            @csrf


            <div class="dpm-grid">


                {{-- =================================================
                   TITLE
                ================================================== --}}

                <div class="dpm-field full">

                    <label
                        for="dpmTitle"
                        class="dpm-label"
                    >

                        {{ $fr
                            ? 'Titre'
                            : 'Title'
                        }}

                    </label>


                    <input
                        id="dpmTitle"
                        type="text"
                        name="title_en"
                        value="{{ old('title_en') }}"
                        maxlength="255"
                        class="dpm-input"
                        required
                    >


                    <input
                        id="dpmTitleFr"
                        type="hidden"
                        name="title_fr"
                        value="{{ old(
                            'title_fr',
                            old('title_en')
                        ) }}"
                    >

                </div>



                {{-- =================================================
                   MESSAGE
                ================================================== --}}

                <div class="dpm-field full">

                    <label
                        for="dpmMessage"
                        class="dpm-label"
                    >
                        Message
                    </label>


                    <textarea
                        id="dpmMessage"
                        name="message_en"
                        maxlength="10000"
                        class="dpm-textarea"
                        required
                    >{{ old('message_en') }}</textarea>


                    <textarea
                        id="dpmMessageFr"
                        name="message_fr"
                        style="display:none;"
                    >{{ old(
                        'message_fr',
                        old('message_en')
                    ) }}</textarea>

                </div>



                {{-- =================================================
                   IMPORTANCE
                ================================================== --}}

                <div class="dpm-importance">


                    <label class="dpm-label">
                        Importance
                    </label>


                    <div class="dpm-importance-options">


                        <label class="dpm-radio normal">

                            <input
                                type="radio"
                                name="importance"
                                value="normal"

                                {{ old(
                                    'importance',
                                    'normal'
                                ) === 'normal'
                                    ? 'checked'
                                    : ''
                                }}
                            >

                            Normal

                        </label>



                        <label class="dpm-radio important">

                            <input
                                type="radio"
                                name="importance"
                                value="important"

                                {{ old('importance')
                                    === 'important'
                                    ? 'checked'
                                    : ''
                                }}
                            >

                            Important

                        </label>



                        <label class="dpm-radio critical">

                            <input
                                type="radio"
                                name="importance"
                                value="critical"

                                {{ old('importance')
                                    === 'critical'
                                    ? 'checked'
                                    : ''
                                }}
                            >

                            {{ $fr
                                ? 'Critique'
                                : 'Critical'
                            }}

                        </label>


                    </div>


                    <div class="dpm-importance-help">

                        {{ $fr
                            ? 'Normal : une fois · Important : chaque connexion · Critique : chaque page'
                            : 'Normal: once · Important: every login · Critical: every page'
                        }}

                    </div>

                </div>



                {{-- =================================================
                   SEND TO
                ================================================== --}}

                <div class="dpm-field full">

                    <label
                        for="dpmAudience"
                        class="dpm-label"
                    >

                        {{ $fr
                            ? 'Envoyer à'
                            : 'Send To'
                        }}

                    </label>


                    <select
                        id="dpmAudience"
                        name="audience_type"
                        class="dpm-select"
                        required
                    >

                        <option
                            value="all_users"

                            {{ old(
                                'audience_type',
                                'all_users'
                            ) === 'all_users'
                                ? 'selected'
                                : ''
                            }}
                        >

                            {{ $fr
                                ? 'Tous les professeurs et étudiants'
                                : 'All Teachers & Students'
                            }}

                            ({{ $teachersCount + $studentsCount }})

                        </option>


                        <option
                            value="all_teachers"

                            {{ old('audience_type')
                                === 'all_teachers'
                                ? 'selected'
                                : ''
                            }}
                        >

                            {{ $fr
                                ? 'Tous les professeurs'
                                : 'All Teachers'
                            }}

                            ({{ $teachersCount }})

                        </option>


                        <option
                            value="all_students"

                            {{ old('audience_type')
                                === 'all_students'
                                ? 'selected'
                                : ''
                            }}
                        >

                            {{ $fr
                                ? 'Tous les étudiants'
                                : 'All Students'
                            }}

                            ({{ $studentsCount }})

                        </option>


                        <option
                            value="single_user"

                            {{ old('audience_type')
                                === 'single_user'
                                ? 'selected'
                                : ''
                            }}
                        >

                            {{ $fr
                                ? 'Un utilisateur'
                                : 'One Specific User'
                            }}

                        </option>


                        <option
                            value="selected_users"

                            {{ old('audience_type')
                                === 'selected_users'
                                ? 'selected'
                                : ''
                            }}
                        >

                            {{ $fr
                                ? 'Plusieurs utilisateurs'
                                : 'Selected Users'
                            }}

                        </option>

                    </select>

                </div>



                {{-- =================================================
                   SINGLE USER
                ================================================== --}}

                <div
                    id="dpmSingleUser"
                    class="dpm-audience-extra"
                >

                    <div class="dpm-field">

                        <label class="dpm-label">

                            {{ $fr
                                ? 'Utilisateur'
                                : 'User'
                            }}

                        </label>


                        <select
                            name="single_user_id"
                            class="dpm-select"
                        >

                            <option value="">

                                {{ $fr
                                    ? 'Sélectionner'
                                    : 'Select User'
                                }}

                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"

                                    {{ (string)
                                        old('single_user_id')
                                        ===
                                        (string) $user->id
                                            ? 'selected'
                                            : ''
                                    }}
                                >

                                    {{ $user->name }}

                                    —

                                    {{ ucfirst($user->role) }}

                                    —

                                    {{ $user->email }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>



                {{-- =================================================
                   SELECTED USERS
                ================================================== --}}

                <div
                    id="dpmSelectedUsers"
                    class="dpm-audience-extra"
                >

                    <div class="dpm-field">

                        <label class="dpm-label">

                            {{ $fr
                                ? 'Utilisateurs'
                                : 'Users'
                            }}

                        </label>


                        <div
                            class="dpm-user-picker"
                            id="dpmUserPicker"
                        >


                            {{-- PICKER BUTTON --}}

                            <button
                                type="button"
                                id="dpmUserPickerButton"
                                class="dpm-user-picker-button"
                            >

                                <span class="dpm-picker-placeholder">

                                    {{ $fr
                                        ? 'Choisir les utilisateurs'
                                        : 'Choose users'
                                    }}

                                </span>


                                <span
                                    id="dpmPickerCount"
                                    class="dpm-picker-count"
                                >
                                    0
                                </span>


                                <span class="dpm-picker-arrow">
                                    ▾
                                </span>

                            </button>



                            {{-- DROPDOWN --}}

                            <div
                                id="dpmUserPickerDropdown"
                                class="dpm-user-picker-dropdown"
                            >


                                {{-- SEARCH --}}

                                <div class="dpm-picker-search-area">

                                    <input
                                        type="text"
                                        id="dpmPickerSearch"
                                        class="dpm-picker-search"
                                        autocomplete="off"
                                        placeholder="{{ $fr
                                            ? 'Rechercher par nom ou courriel...'
                                            : 'Search by name or email...'
                                        }}"
                                    >

                                </div>



                                {{-- USERS --}}

                                <div class="dpm-picker-users">


                                    @foreach($users as $user)

                                        @php

                                            $initial =
                                                strtoupper(
                                                    mb_substr(
                                                        $user->name ?? '?',
                                                        0,
                                                        1
                                                    )
                                                );

                                        @endphp


                                        <label
                                            class="dpm-picker-user"
                                            data-user-search="{{ strtolower(
                                                ($user->name ?? '')
                                                . ' '
                                                . ($user->email ?? '')
                                                . ' '
                                                . ($user->role ?? '')
                                            ) }}"
                                        >


                                            <input
                                                type="checkbox"
                                                name="selected_user_ids[]"
                                                value="{{ $user->id }}"
                                                class="dpm-picker-checkbox"

                                                {{ in_array(
                                                    $user->id,
                                                    old(
                                                        'selected_user_ids',
                                                        []
                                                    )
                                                )
                                                    ? 'checked'
                                                    : ''
                                                }}
                                            >


                                            <span class="dpm-picker-check"></span>


                                            <span class="dpm-picker-avatar">

                                                {{ $initial }}

                                            </span>


                                            <span class="dpm-picker-user-info">

                                                <span class="dpm-picker-user-name">

                                                    {{ $user->name }}

                                                </span>


                                                <span class="dpm-picker-user-email">

                                                    {{ $user->email }}

                                                </span>

                                            </span>


                                            <span
                                                class="
                                                    dpm-picker-role
                                                    {{ $user->role }}
                                                "
                                            >

                                                {{ $user->role }}

                                            </span>


                                        </label>

                                    @endforeach



                                    <div
                                        id="dpmNoUser"
                                        class="dpm-no-user"
                                        style="display:none;"
                                    >

                                        {{ $fr
                                            ? 'Aucun utilisateur trouvé.'
                                            : 'No users found.'
                                        }}

                                    </div>


                                </div>



                                {{-- FOOTER --}}

                                <div class="dpm-picker-footer">


                                    <div class="dpm-picker-footer-count">

                                        <strong id="dpmFooterCount">
                                            0
                                        </strong>

                                        {{ $fr
                                            ? 'sélectionné(s)'
                                            : 'selected'
                                        }}

                                    </div>


                                    <button
                                        type="button"
                                        id="dpmPickerDone"
                                        class="dpm-picker-done"
                                    >

                                        {{ $fr
                                            ? 'Terminé'
                                            : 'Done'
                                        }}

                                    </button>


                                </div>


                            </div>


                        </div>

                    </div>

                </div>



                {{-- =================================================
                   START
                ================================================== --}}

                <div class="dpm-field">

                    <label class="dpm-label">

                        {{ $fr
                            ? 'Début'
                            : 'Start'
                        }}

                    </label>


                    <input
                        type="datetime-local"
                        name="starts_at"
                        value="{{ old('starts_at') }}"
                        class="dpm-input"
                    >

                </div>



                {{-- =================================================
                   END
                ================================================== --}}

                <div class="dpm-field">

                    <label class="dpm-label">

                        {{ $fr
                            ? 'Fin'
                            : 'End'
                        }}

                    </label>


                    <input
                        type="datetime-local"
                        name="ends_at"
                        value="{{ old('ends_at') }}"
                        class="dpm-input"
                    >

                </div>



                {{-- =================================================
                   ACTIVE
                ================================================== --}}

                <div class="dpm-active-row">

                    <input
                        type="checkbox"
                        id="dpmActive"
                        name="is_active"
                        value="1"

                        {{ old(
                            'is_active',
                            '1'
                        )
                            ? 'checked'
                            : ''
                        }}
                    >


                    <label for="dpmActive">

                        {{ $fr
                            ? 'Message actif'
                            : 'Message Active'
                        }}

                    </label>

                </div>



                {{-- =================================================
                   PREVIEW + SEND
                ================================================== --}}

                <div class="dpm-submit-row">

                    <button
                        type="button"
                        id="dancePairPreviewOpen"
                        class="dpm-preview-btn"
                    >
                        Preview
                    </button>


                    <button
                        type="submit"
                        class="dpm-send-btn"
                    >

                        {{ $fr
                            ? 'Envoyer'
                            : 'Send Message'
                        }}

                    </button>

                </div>


            </div>

        </form>

    </div>



    {{-- =====================================================
       CLIENT MESSAGE PREVIEW
    ====================================================== --}}

    <div
        id="dancePairPreviewOverlay"
        class="dancepair-preview-overlay"
        aria-hidden="true"
    >


        <div
            id="dancePairPreviewBox"
            class="dancepair-preview-box normal"
        >


            {{-- HEADER --}}

            <div class="dancepair-preview-header">


            <div class="dancepair-preview-brand">

<div class="dancepair-preview-logo">

    <img
        src="{{ asset('logo/logo.png') }}"
        alt="DancePair"
    >

</div>

<strong>
    DancePair
</strong>

</div>



                <button
                    type="button"
                    id="dancePairPreviewCloseX"
                    class="dancepair-preview-x"
                    aria-label="Close"
                >
                    ×
                </button>


            </div>



            {{-- CONTENT --}}

            <div class="dancepair-preview-content">


                <div
                    id="dancePairPreviewImportance"
                    class="dancepair-preview-importance normal"
                >
                    Normal
                </div>


                <h3 id="dancePairPreviewTitle">

                    {{ $fr
                        ? 'Titre du message'
                        : 'Message title'
                    }}

                </h3>


                <div id="dancePairPreviewText">

                    {{ $fr
                        ? 'Votre message DancePair apparaîtra ici.'
                        : 'Your DancePair message will appear here.'
                    }}

                </div>


            </div>



            {{-- FOOTER --}}

            <div class="dancepair-preview-footer">


                <button
                    type="button"
                    id="dancePairPreviewClose"
                    class="dancepair-preview-close"
                >

                    {{ $fr
                        ? 'Fermer'
                        : 'Close'
                    }}

                </button>


            </div>


        </div>


    </div>



    {{-- =====================================================
       SENT MESSAGES
    ====================================================== --}}

    <div class="dpm-card list-card">


        <div class="dpm-card-header">

            <h3>

                {{ $fr
                    ? 'Messages envoyés'
                    : 'Sent Messages'
                }}

            </h3>

        </div>



        @if($messages->count())


            <div class="dpm-list">


                <div class="dpm-list-head">

                    <div>
                        Message
                    </div>

                    <div>
                        Importance
                    </div>

                    <div>
                        {{ $fr ? 'Destinataires' : 'Audience' }}
                    </div>

                    <div>
                        {{ $fr ? 'Envoyés' : 'Sent' }}
                    </div>

                    <div>
                        {{ $fr ? 'Non lus' : 'Unread' }}
                    </div>

                    <div></div>

                </div>



                @foreach($messages as $message)

                    @php

                        $unreadCount =
                            max(
                                0,
                                (int) $message->recipients_count
                                -
                                (int) $message->read_count
                            );


                        $audienceLabel =
                            $audienceLabels[
                                $message->audience_type
                            ]
                            ??
                            ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $message->audience_type
                                )
                            );


                        $messageTitle =
                            $message->title_en
                            ?: $message->title_fr;

                            $recipientUsers =
                             $message
                            ->recipients
                            ->pluck('user')
                             ->filter()
                            ->values();

                    @endphp



                    <div class="dpm-message-row">


                        {{-- TITLE --}}

                        <div class="dpm-message-main">

                            <strong>
                                {{ $messageTitle }}
                            </strong>


                            <span>

                                {{ optional(
                                    $message->created_at
                                )->format(
                                    $fr
                                        ? 'd M Y · H:i'
                                        : 'M d, Y · g:i A'
                                ) }}

                            </span>

                        </div>



                        {{-- IMPORTANCE --}}

                        <div>

                            <span
                                class="
                                    dpm-badge
                                    {{ $message->importance }}
                                "
                            >

                                @if($message->importance === 'critical')

                                    {{ $fr
                                        ? 'Critique'
                                        : 'Critical'
                                    }}

                                @elseif($message->importance === 'important')

                                    Important

                                @else

                                    Normal

                                @endif

                            </span>

                        </div>



                        {{-- AUDIENCE / RECIPIENTS --}}

<div class="dpm-info dpm-audience-info">


    <div class="dpm-audience-label">

        {{ $audienceLabel }}

    </div>


    @if(
        in_array(
            $message->audience_type,
            [
                'single_user',
                'selected_users',
            ],
            true
        )
    )

        <div class="dpm-recipient-list">


            @foreach($recipientUsers as $recipientUser)

                <div class="dpm-recipient-item">


                    <div class="dpm-recipient-top">

                        <strong>

                            {{ $recipientUser->name }}

                        </strong>


                        <span
                            class="
                                dpm-recipient-role
                                {{ $recipientUser->role }}
                            "
                        >

                            {{ ucfirst(
                                $recipientUser->role
                            ) }}

                        </span>

                    </div>


                    <div class="dpm-recipient-email">

                        {{ $recipientUser->email }}

                    </div>


                </div>

            @endforeach


        </div>

    @endif


</div>



                        {{-- SENT --}}

                        <div class="dpm-info">

                            <strong>

                                {{ $message->recipients_count }}

                            </strong>

                        </div>



                        {{-- UNREAD --}}

                        <div
                            class="
                                dpm-info
                                {{ $unreadCount > 0
                                    ? 'dpm-unread'
                                    : 'dpm-read'
                                }}
                            "
                        >

                            <strong>

                                {{ $unreadCount }}

                            </strong>

                        </div>



                        {{-- ACTIONS --}}

                        <div class="dpm-actions">


                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.platform-messages.toggle',
                                    $message
                                ) }}"
                            >

                                @csrf
                                @method('PATCH')


                                <button
                                    type="submit"
                                    class="
                                        dpm-action-btn
                                        toggle
                                    "
                                >

                                    {{ $message->is_active

                                        ? (
                                            $fr
                                                ? 'Désactiver'
                                                : 'Disable'
                                        )

                                        : (
                                            $fr
                                                ? 'Activer'
                                                : 'Enable'
                                        )
                                    }}

                                </button>

                            </form>



                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.platform-messages.destroy',
                                    $message
                                ) }}"
                                onsubmit="
                                    return confirm(
                                        '{{ $fr
                                            ? 'Supprimer ce message ?'
                                            : 'Delete this message?'
                                        }}'
                                    );
                                "
                            >

                                @csrf
                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="
                                        dpm-action-btn
                                        delete
                                    "
                                >

                                    {{ $fr
                                        ? 'Supprimer'
                                        : 'Delete'
                                    }}

                                </button>

                            </form>


                        </div>


                    </div>


                @endforeach


            </div>



            @if($messages->hasPages())

                <div class="dpm-pagination">

                    {{ $messages->links() }}

                </div>

            @endif


        @else


            <div class="dpm-empty">

                {{ $fr
                    ? 'Aucun message.'
                    : 'No messages yet.'
                }}

            </div>


        @endif


    </div>


</div>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /* =====================================================
           AUDIENCE
        ====================================================== */

        const audience =
            document.getElementById(
                'dpmAudience'
            );


        const singleUser =
            document.getElementById(
                'dpmSingleUser'
            );


        const selectedUsers =
            document.getElementById(
                'dpmSelectedUsers'
            );


        function updateAudience()
        {
            const value =
                audience?.value;


            singleUser
                ?.classList
                .toggle(
                    'active',
                    value === 'single_user'
                );


            selectedUsers
                ?.classList
                .toggle(
                    'active',
                    value === 'selected_users'
                );
        }


        audience?.addEventListener(
            'change',
            updateAudience
        );


        updateAudience();



        /* =====================================================
           COPY MESSAGE TO EN / FR DB FIELDS
        ====================================================== */

        const title =
            document.getElementById(
                'dpmTitle'
            );


        const titleFr =
            document.getElementById(
                'dpmTitleFr'
            );


        const messageInput =
            document.getElementById(
                'dpmMessage'
            );


        const messageFr =
            document.getElementById(
                'dpmMessageFr'
            );


        function syncContent()
        {
            if (
                title
                &&
                titleFr
            ) {

                titleFr.value =
                    title.value;
            }


            if (
                messageInput
                &&
                messageFr
            ) {

                messageFr.value =
                    messageInput.value;
            }
        }


        title?.addEventListener(
            'input',
            syncContent
        );


        messageInput?.addEventListener(
            'input',
            syncContent
        );


        document
            .getElementById(
                'dpmMessageForm'
            )
            ?.addEventListener(
                'submit',
                syncContent
            );


        syncContent();



        /* =====================================================
           USER PICKER
        ====================================================== */

        const picker =
            document.getElementById(
                'dpmUserPicker'
            );


        const pickerButton =
            document.getElementById(
                'dpmUserPickerButton'
            );


        const pickerDropdown =
            document.getElementById(
                'dpmUserPickerDropdown'
            );


        const pickerSearch =
            document.getElementById(
                'dpmPickerSearch'
            );


        const pickerDone =
            document.getElementById(
                'dpmPickerDone'
            );


        const pickerCount =
            document.getElementById(
                'dpmPickerCount'
            );


        const footerCount =
            document.getElementById(
                'dpmFooterCount'
            );


        const noUser =
            document.getElementById(
                'dpmNoUser'
            );


        function updatePickerCount()
        {
            const count =
                document.querySelectorAll(
                    '.dpm-picker-checkbox:checked'
                ).length;


            if (pickerCount) {

                pickerCount.textContent =
                    count;
            }


            if (footerCount) {

                footerCount.textContent =
                    count;
            }
        }


        function openPicker()
        {
            pickerDropdown
                ?.classList
                .add('open');


            pickerButton
                ?.classList
                .add('open');


            setTimeout(
                function () {

                    pickerSearch?.focus();

                },
                30
            );
        }


        function closePicker()
        {
            pickerDropdown
                ?.classList
                .remove('open');


            pickerButton
                ?.classList
                .remove('open');
        }


        pickerButton
            ?.addEventListener(
                'click',
                function () {

                    const isOpen =
                        pickerDropdown
                            ?.classList
                            .contains('open');


                    if (isOpen) {

                        closePicker();

                    } else {

                        openPicker();
                    }
                }
            );


        pickerDone
            ?.addEventListener(
                'click',
                closePicker
            );



        /* =====================================================
           USER CHECKBOX COUNT
        ====================================================== */

        document
            .querySelectorAll(
                '.dpm-picker-checkbox'
            )
            .forEach(
                function (checkbox) {

                    checkbox.addEventListener(
                        'change',
                        updatePickerCount
                    );
                }
            );


        updatePickerCount();



        /* =====================================================
           SEARCH USERS
        ====================================================== */

        pickerSearch
            ?.addEventListener(
                'input',
                function () {

                    const search =
                        this.value
                            .trim()
                            .toLowerCase();


                    let visible =
                        0;


                    document
                        .querySelectorAll(
                            '.dpm-picker-user'
                        )
                        .forEach(
                            function (userRow) {

                                const searchable =
                                    userRow
                                        .dataset
                                        .userSearch
                                    || '';


                                const match =
                                    searchable
                                        .includes(
                                            search
                                        );


                                userRow.style.display =
                                    match
                                        ? 'flex'
                                        : 'none';


                                if (match) {

                                    visible++;
                                }
                            }
                        );


                    if (noUser) {

                        noUser.style.display =
                            visible === 0
                                ? 'block'
                                : 'none';
                    }

                }
            );



        /* =====================================================
           CLOSE PICKER OUTSIDE
        ====================================================== */

        document.addEventListener(
            'click',
            function (event) {

                if (
                    picker
                    &&
                    !picker.contains(
                        event.target
                    )
                ) {

                    closePicker();
                }
            }
        );



        /* =====================================================
           MESSAGE PREVIEW
        ====================================================== */

        const previewOpen =
            document.getElementById(
                'dancePairPreviewOpen'
            );


        const previewOverlay =
            document.getElementById(
                'dancePairPreviewOverlay'
            );


        const previewBox =
            document.getElementById(
                'dancePairPreviewBox'
            );


        const previewTitle =
            document.getElementById(
                'dancePairPreviewTitle'
            );


        const previewText =
            document.getElementById(
                'dancePairPreviewText'
            );


        const previewImportance =
            document.getElementById(
                'dancePairPreviewImportance'
            );


        const previewCloseX =
            document.getElementById(
                'dancePairPreviewCloseX'
            );


        const previewClose =
            document.getElementById(
                'dancePairPreviewClose'
            );



        function updatePreview()
        {
            const currentTitle =
                title?.value
                    ?.trim()
                || '';


            const currentMessage =
                messageInput?.value
                    ?.trim()
                || '';


            const checkedImportance =
                document.querySelector(
                    'input[name="importance"]:checked'
                );


            const importance =
                checkedImportance?.value
                ||
                'normal';



            /* TITLE */

            if (previewTitle) {

                previewTitle.textContent =
                    currentTitle !== ''
                        ? currentTitle
                        : @json(
                            $fr
                                ? 'Titre du message'
                                : 'Message title'
                        );
            }



            /* MESSAGE */

            if (previewText) {

                previewText.textContent =
                    currentMessage !== ''
                        ? currentMessage
                        : @json(
                            $fr
                                ? 'Votre message DancePair apparaîtra ici.'
                                : 'Your DancePair message will appear here.'
                        );
            }



            /* BOX COLOR */

            previewBox?.classList.remove(
                'normal',
                'important',
                'critical'
            );


            previewBox?.classList.add(
                importance
            );



            /* BADGE COLOR */

            previewImportance?.classList.remove(
                'normal',
                'important',
                'critical'
            );


            previewImportance?.classList.add(
                importance
            );



            /* BADGE TEXT */

            if (previewImportance) {

                if (
                    importance
                    ===
                    'critical'
                ) {

                    previewImportance.textContent =
                        @json(
                            $fr
                                ? 'Critique'
                                : 'Critical'
                        );

                } else if (
                    importance
                    ===
                    'important'
                ) {

                    previewImportance.textContent =
                        'Important';

                } else {

                    previewImportance.textContent =
                        'Normal';
                }
            }
        }



        function openPreview()
        {
            updatePreview();


            previewOverlay
                ?.classList
                .add(
                    'is-open'
                );


            previewOverlay
                ?.setAttribute(
                    'aria-hidden',
                    'false'
                );


            document.body.style.overflow =
                'hidden';
        }



        function closePreview()
        {
            previewOverlay
                ?.classList
                .remove(
                    'is-open'
                );


            previewOverlay
                ?.setAttribute(
                    'aria-hidden',
                    'true'
                );


            document.body.style.overflow =
                '';
        }



        previewOpen
            ?.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();

                    openPreview();
                }
            );


        previewCloseX
            ?.addEventListener(
                'click',
                closePreview
            );


        previewClose
            ?.addEventListener(
                'click',
                closePreview
            );


        previewOverlay
            ?.addEventListener(
                'click',
                function (event) {

                    if (
                        event.target
                        ===
                        previewOverlay
                    ) {

                        closePreview();
                    }
                }
            );


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key
                    ===
                    'Escape'
                ) {

                    closePreview();
                }
            }
        );



        /* =====================================================
           LIVE PREVIEW CONTENT
        ====================================================== */

        title
            ?.addEventListener(
                'input',
                updatePreview
            );


        messageInput
            ?.addEventListener(
                'input',
                updatePreview
            );


        document
            .querySelectorAll(
                'input[name="importance"]'
            )
            .forEach(
                function (radio) {

                    radio.addEventListener(
                        'change',
                        updatePreview
                    );
                }
            );


        updatePreview();

    }
);

</script>

@endsection