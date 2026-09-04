@extends('admin.layout')

@section('title', 'Conversation #' . $booking->id)

@section(
    'page-title',
    app()->getLocale() === 'fr'
        ? 'Conversation client'
        : 'Client Conversation'
)

@section('content')

@php

    $fr = app()->getLocale() === 'fr';

    $teacherName =
        $booking->teacher->user->name
        ?? ($fr ? 'Professeur' : 'Teacher');

    $teacherEmail =
        $booking->teacher->user->email
        ?? null;

    $studentName =
        $booking->student->user->name
        ?? ($fr ? 'Étudiant' : 'Student');

    $studentEmail =
        $booking->student->user->email
        ?? null;

    $messageCount =
        $booking->messages->count();

    $lessonDate = null;
    $lessonTime = null;

    if ($booking->lesson_date) {

        $lessonDate =
            \Carbon\Carbon::parse(
                $booking->lesson_date
            )
            ->locale(app()->getLocale())
            ->translatedFormat(
                $fr
                    ? 'd M Y'
                    : 'M d, Y'
            );
    }

    if ($booking->lesson_time) {

        $lessonTime =
            \Carbon\Carbon::parse(
                $booking->lesson_time
            )->format(
                $fr
                    ? 'H:i'
                    : 'g:i A'
            );
    }

    $statusLabel = match ($booking->status) {

        'pending' =>
            $fr
                ? 'En attente'
                : 'Pending',

        'confirmed' =>
            $fr
                ? 'Confirmée'
                : 'Confirmed',

        'completed' =>
            $fr
                ? 'Terminée'
                : 'Completed',

        'cancelled' =>
            $fr
                ? 'Annulée'
                : 'Cancelled',

        default =>
            ucfirst(
                (string) $booking->status
            ),
    };

@endphp


<style>

/* =========================================================
   PAGE
========================================================= */

.admin-conversation-page {
    width: 100%;
    max-width: 1080px;
    margin: 0 auto;
}


.admin-conversation-page * {
    box-sizing: border-box;
}


/* =========================================================
   BACK
========================================================= */

.ac-back-row {
    margin-bottom: 14px;
}


.ac-back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 7px 10px;

    border: 1px solid #e2e8f0;
    border-radius: 9px;

    background: #ffffff;

    color: #475569;

    font-size: 10px;
    font-weight: 700;

    text-decoration: none;

    transition: all .15s ease;
}


.ac-back-link:hover {
    border-color: #cbd5e1;

    background: #f8fafc;

    color: #0f172a;
}


/* =========================================================
   TOP CARD
========================================================= */

.ac-summary {
    margin-bottom: 14px;

    overflow: hidden;

    border: 1px solid #dfe7ee;
    border-radius: 15px;

    background: #ffffff;

    box-shadow:
        0 4px 18px rgba(
            15,
            23,
            42,
            .035
        );
}


.ac-summary-top {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    padding: 15px 17px;

    border-bottom: 1px solid #edf2f7;

    background:
        linear-gradient(
            180deg,
            #ffffff 0%,
            #fbfdff 100%
        );
}


.ac-title-area {
    min-width: 0;
}


.ac-title {
    display: flex;
    align-items: center;

    gap: 8px;

    margin: 0;

    color: #0f172a;

    font-size: 15px;
    font-weight: 800;

    line-height: 1.2;
}


.ac-title-booking {
    color: #64748b;

    font-size: 10px;
    font-weight: 700;
}


.ac-subtitle {
    margin-top: 5px;

    color: #64748b;

    font-size: 9.5px;
}


.ac-summary-actions {
    display: flex;
    align-items: center;

    gap: 7px;

    flex-shrink: 0;
}


.ac-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 25px;

    padding: 0 9px;

    border-radius: 999px;

    font-size: 8.5px;
    font-weight: 800;

    white-space: nowrap;
}


.ac-status.pending {
    background: #fff7ed;
    color: #c2410c;
}


.ac-status.confirmed {
    background: #ecfdf5;
    color: #047857;
}


.ac-status.completed {
    background: #eff6ff;
    color: #1d4ed8;
}


.ac-status.cancelled {
    background: #fef2f2;
    color: #b91c1c;
}


.ac-message-total {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 25px;

    padding: 0 9px;

    border-radius: 999px;

    background: #f1f5f9;

    color: #475569;

    font-size: 8.5px;
    font-weight: 800;

    white-space: nowrap;
}


/* =========================================================
   PARTICIPANTS
========================================================= */

.ac-participants {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        38px
        minmax(0, 1fr);

    align-items: center;

    gap: 12px;

    padding: 14px 17px;
}


.ac-person {
    display: flex;
    align-items: center;

    min-width: 0;

    gap: 10px;
}


.ac-person.student {
    justify-content: flex-end;

    text-align: right;
}


.ac-avatar {
    width: 35px;
    height: 35px;

    flex: 0 0 35px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    font-size: 11px;
    font-weight: 800;
}


.ac-person.teacher .ac-avatar {
    background: #e0f2fe;
    color: #0369a1;
}


.ac-person.student .ac-avatar {
    background: #ede9fe;
    color: #6d28d9;
}


.ac-person-info {
    min-width: 0;
}


.ac-person-role {
    display: block;

    margin-bottom: 2px;

    color: #94a3b8;

    font-size: 7.5px;
    font-weight: 800;

    letter-spacing: .04em;

    text-transform: uppercase;
}


.ac-person-name {
    display: block;

    overflow: hidden;

    color: #0f172a;

    font-size: 10.5px;
    font-weight: 800;

    text-overflow: ellipsis;
    white-space: nowrap;
}


.ac-person-email {
    display: block;

    overflow: hidden;

    margin-top: 2px;

    color: #94a3b8;

    font-size: 8px;

    text-overflow: ellipsis;
    white-space: nowrap;
}


.ac-between {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 38px;
    height: 38px;

    border: 1px solid #e2e8f0;
    border-radius: 50%;

    background: #f8fafc;

    color: #64748b;

    font-size: 14px;
    font-weight: 700;
}


/* =========================================================
   BOOKING META
========================================================= */

.ac-booking-meta {
    display: grid;

    grid-template-columns:
        repeat(5, minmax(0, 1fr));

    border-top: 1px solid #edf2f7;

    background: #fbfdff;
}


.ac-meta-item {
    min-width: 0;

    padding: 10px 13px;

    border-right: 1px solid #edf2f7;
}


.ac-meta-item:last-child {
    border-right: 0;
}


.ac-meta-label {
    display: block;

    margin-bottom: 3px;

    color: #94a3b8;

    font-size: 7.5px;
    font-weight: 800;

    text-transform: uppercase;
}


.ac-meta-value {
    display: block;

    overflow: hidden;

    color: #334155;

    font-size: 9px;
    font-weight: 700;

    text-overflow: ellipsis;
    white-space: nowrap;
}


/* =========================================================
   ALERTS
========================================================= */

.ac-alert {
    margin-bottom: 13px;

    padding: 10px 12px;

    border-radius: 10px;

    font-size: 9.5px;
    font-weight: 600;
}


.ac-alert.success {
    border: 1px solid #bbf7d0;

    background: #f0fdf4;

    color: #166534;
}


.ac-alert.error {
    border: 1px solid #fecaca;

    background: #fef2f2;

    color: #b91c1c;
}


.ac-alert ul {
    margin: 0;
    padding-left: 16px;
}


/* =========================================================
   CHAT SHELL
========================================================= */

.ac-chat {
    overflow: hidden;

    border: 1px solid #dfe7ee;
    border-radius: 15px;

    background: #ffffff;

    box-shadow:
        0 4px 18px rgba(
            15,
            23,
            42,
            .035
        );
}


.ac-chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    min-height: 52px;

    padding: 10px 15px;

    border-bottom: 1px solid #e8eef3;

    background: #ffffff;
}


.ac-chat-header-left {
    min-width: 0;
}


.ac-chat-header-title {
    display: flex;
    align-items: center;

    gap: 7px;

    color: #0f172a;

    font-size: 11px;
    font-weight: 800;
}


.ac-online-dot {
    width: 7px;
    height: 7px;

    flex: 0 0 7px;

    border-radius: 50%;

    background: #22c55e;
}


.ac-chat-header-subtitle {
    margin-top: 3px;

    color: #94a3b8;

    font-size: 8px;
}


.ac-monitor-badge {
    display: inline-flex;
    align-items: center;

    padding: 5px 8px;

    border-radius: 999px;

    background: #f1f5f9;

    color: #64748b;

    font-size: 7.5px;
    font-weight: 800;

    white-space: nowrap;
}


/* =========================================================
   CHAT HISTORY
========================================================= */

.ac-chat-history {
    display: flex;
    flex-direction: column;

    gap: 8px;

    height: 430px;

    overflow-y: auto;

    padding: 15px;

    background:
        linear-gradient(
            180deg,
            #fbfdff 0%,
            #f8fbfd 100%
        );

    scroll-behavior: smooth;
}


.ac-chat-history::-webkit-scrollbar {
    width: 7px;
}


.ac-chat-history::-webkit-scrollbar-track {
    background: transparent;
}


.ac-chat-history::-webkit-scrollbar-thumb {
    border-radius: 999px;

    background: #cbd5e1;
}


.ac-chat-history::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}


/* =========================================================
   MESSAGE ROW
========================================================= */

.ac-message-row {
    display: flex;

    width: 100%;

    margin: 0;
    padding: 0;

    align-items: flex-start;
}


.ac-message-row.teacher {
    justify-content: flex-start;
}


.ac-message-row.student {
    justify-content: flex-end;
}


.ac-message-row.support {
    justify-content: center;

    margin: 2px 0;
}


/* =========================================================
   BUBBLE
========================================================= */

.ac-message-bubble {
    display: inline-flex;
    flex-direction: column;

    width: fit-content;
    min-width: 0;
    max-width: 64%;

    margin: 0;

    padding: 8px 10px;

    border-radius: 11px;

    font-size: 10.5px;
    line-height: 1.45;

    box-shadow:
        0 1px 2px rgba(
            15,
            23,
            42,
            .035
        );
}


/* TEACHER */

.ac-message-row.teacher
.ac-message-bubble {

    border: 1px solid #bae6fd;

    border-bottom-left-radius: 4px;

    background: #eef8ff;

    color: #1e293b;
}


/* STUDENT */

.ac-message-row.student
.ac-message-bubble {

    border: 1px solid #ddd6fe;

    border-bottom-right-radius: 4px;

    background: #f5f3ff;

    color: #1e293b;
}


/* SUPPORT */

.ac-message-row.support
.ac-message-bubble {

    max-width: 70%;

    border: 1px solid #bbf7d0;

    background: #f0fdf4;

    color: #14532d;
}


/* =========================================================
   META INSIDE MESSAGE
========================================================= */

.ac-message-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;

    width: 100%;

    gap: 14px;

    margin: 0 0 4px 0;

    line-height: 1;
}


.ac-message-name {
    display: inline-flex;
    align-items: center;

    gap: 4px;

    min-width: 0;

    color: #334155;

    font-size: 8px;
    font-weight: 800;

    white-space: nowrap;
}


.ac-message-row.support
.ac-message-name {

    color: #15803d;
}


.ac-support-dot {
    display: inline-block;

    width: 5px;
    height: 5px;

    border-radius: 50%;

    background: #22c55e;
}


.ac-message-date {
    flex-shrink: 0;

    color: #94a3b8;

    font-size: 7.5px;
    font-weight: 500;

    white-space: nowrap;
}


.ac-message-text {
    width: 100%;

    margin: 0;
    padding: 0;

    color: inherit;

    font-size: 10.5px;
    line-height: 1.45;

    word-break: break-word;
    overflow-wrap: anywhere;

    white-space: pre-wrap;
}


/* =========================================================
   EMPTY CHAT
========================================================= */

.ac-chat-empty {
    margin: auto;

    padding: 25px;

    text-align: center;

    color: #94a3b8;

    font-size: 9.5px;
}


/* =========================================================
   REPLY
========================================================= */

.ac-reply {
    padding: 12px 14px 13px;

    border-top: 1px solid #e2e8f0;

    background: #ffffff;
}


.ac-reply-top {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 7px;
}


.ac-reply-label {
    display: flex;
    align-items: center;

    gap: 6px;

    margin: 0;

    color: #334155;

    font-size: 9.5px;
    font-weight: 800;
}


.ac-support-badge {
    display: inline-flex;
    align-items: center;

    padding: 3px 6px;

    border-radius: 999px;

    background: #dcfce7;

    color: #15803d;

    font-size: 7px;
    font-weight: 800;
}


.ac-reply-note {
    color: #94a3b8;

    font-size: 7.5px;
}


.ac-textarea {
    display: block;

    width: 100%;
    min-height: 74px;
    max-height: 180px;

    padding: 10px 11px;

    resize: vertical;

    outline: none;

    border: 1px solid #cbd5e1;
    border-radius: 10px;

    background: #ffffff;

    color: #1e293b;

    font-family: inherit;

    font-size: 10px;
    line-height: 1.45;

    transition:
        border-color .15s ease,
        box-shadow .15s ease;
}


.ac-textarea::placeholder {
    color: #94a3b8;
}


.ac-textarea:focus {
    border-color: #38bdf8;

    box-shadow:
        0 0 0 3px rgba(
            14,
            165,
            233,
            .09
        );
}


.ac-reply-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 14px;

    margin-top: 8px;
}


.ac-reply-help {
    max-width: 650px;

    color: #94a3b8;

    font-size: 7.5px;
    line-height: 1.4;
}


.ac-send-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 108px;
    height: 34px;

    flex-shrink: 0;

    padding: 0 15px;

    border: 0;
    border-radius: 9px;

    background: #15803d;

    color: #ffffff;

    font-size: 8.5px;
    font-weight: 800;

    cursor: pointer;

    transition:
        background .15s ease,
        transform .15s ease,
        box-shadow .15s ease;
}


.ac-send-button:hover {
    background: #166534;

    box-shadow:
        0 4px 10px rgba(
            21,
            128,
            61,
            .14
        );
}


.ac-send-button:active {
    transform: translateY(1px);
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 900px) {

    .ac-booking-meta {
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
    }

    .ac-meta-item:nth-child(3) {
        border-right: 0;
    }

    .ac-message-bubble {
        max-width: 75%;
    }
}


@media(max-width: 700px) {

    .ac-summary-top {
        align-items: flex-start;
        flex-direction: column;
    }

    .ac-summary-actions {
        width: 100%;
    }

    .ac-participants {
        grid-template-columns: 1fr;

        gap: 9px;
    }

    .ac-between {
        width: 30px;
        height: 30px;

        margin: 0 auto;

        transform: rotate(90deg);
    }

    .ac-person.student {
        justify-content: flex-start;

        text-align: left;
    }

    .ac-person.student .ac-avatar {
        order: 0;
    }

    .ac-booking-meta {
        grid-template-columns:
            1fr
            1fr;
    }

    .ac-meta-item,
    .ac-meta-item:nth-child(3) {
        border-right: 1px solid #edf2f7;
    }

    .ac-meta-item:nth-child(even) {
        border-right: 0;
    }

    .ac-chat-history {
        height: 390px;

        padding: 11px;
    }

    .ac-message-bubble {
        max-width: 86%;
    }

    .ac-message-row.support
    .ac-message-bubble {

        max-width: 92%;
    }

    .ac-reply-top {
        align-items: flex-start;
        flex-direction: column;

        gap: 4px;
    }

    .ac-reply-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .ac-send-button {
        width: 100%;
    }
}

</style>


<div class="admin-conversation-page">


    {{-- =====================================================
       BACK
    ====================================================== --}}

    <div class="ac-back-row">

        <a
            href="{{ route('admin.conversations') }}"
            class="ac-back-link"
        >
            <span>←</span>

            <span>
                {{ $fr
                    ? 'Toutes les conversations'
                    : 'All Conversations'
                }}
            </span>
        </a>

    </div>



    {{-- =====================================================
       SUCCESS
    ====================================================== --}}

    @if(session('success'))

        <div class="ac-alert success">

            {{ session('success') }}

        </div>

    @endif



    {{-- =====================================================
       ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div class="ac-alert error">

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
       CONVERSATION SUMMARY
    ====================================================== --}}

    <div class="ac-summary">


        {{-- TOP --}}

        <div class="ac-summary-top">


            <div class="ac-title-area">

                <h2 class="ac-title">

                    {{ $fr
                        ? 'Conversation'
                        : 'Conversation'
                    }}

                    <span class="ac-title-booking">
                        #{{ $booking->id }}
                    </span>

                </h2>


                <div class="ac-subtitle">

                    {{ $fr
                        ? 'Conversation liée à cette réservation'
                        : 'Conversation related to this booking'
                    }}

                </div>

            </div>



            <div class="ac-summary-actions">


                <span
                    class="
                        ac-status
                        {{ $booking->status }}
                    "
                >
                    {{ $statusLabel }}
                </span>


                <span class="ac-message-total">

                    {{ $messageCount }}

                    {{ $fr
                        ? ($messageCount > 1
                            ? 'messages'
                            : 'message')
                        : ($messageCount === 1
                            ? 'message'
                            : 'messages')
                    }}

                </span>

            </div>


        </div>



        {{-- PARTICIPANTS --}}

        <div class="ac-participants">


            {{-- TEACHER --}}

            <div class="ac-person teacher">


                <div class="ac-avatar">

                    {{ strtoupper(
                        substr(
                            $teacherName,
                            0,
                            1
                        )
                    ) }}

                </div>


                <div class="ac-person-info">

                    <span class="ac-person-role">

                        {{ $fr
                            ? 'Professeur'
                            : 'Teacher'
                        }}

                    </span>


                    <span class="ac-person-name">

                        {{ $teacherName }}

                    </span>


                    @if($teacherEmail)

                        <span class="ac-person-email">

                            {{ $teacherEmail }}

                        </span>

                    @endif

                </div>


            </div>



            {{-- BETWEEN --}}

            <div class="ac-between">
                ↔
            </div>



            {{-- STUDENT --}}

            <div class="ac-person student">


                <div class="ac-person-info">

                    <span class="ac-person-role">

                        {{ $fr
                            ? 'Étudiant'
                            : 'Student'
                        }}

                    </span>


                    <span class="ac-person-name">

                        {{ $studentName }}

                    </span>


                    @if($studentEmail)

                        <span class="ac-person-email">

                            {{ $studentEmail }}

                        </span>

                    @endif

                </div>


                <div class="ac-avatar">

                    {{ strtoupper(
                        substr(
                            $studentName,
                            0,
                            1
                        )
                    ) }}

                </div>


            </div>


        </div>



        {{-- BOOKING INFO --}}

        <div class="ac-booking-meta">


            <div class="ac-meta-item">

                <span class="ac-meta-label">

                    {{ $fr
                        ? 'Danse'
                        : 'Dance'
                    }}

                </span>

                <span class="ac-meta-value">

                    {{ $booking->danceStyle->name
                        ?? '—'
                    }}

                </span>

            </div>



            <div class="ac-meta-item">

                <span class="ac-meta-label">

                    {{ $fr
                        ? 'Date'
                        : 'Date'
                    }}

                </span>

                <span class="ac-meta-value">

                    {{ $lessonDate ?? '—' }}

                </span>

            </div>



            <div class="ac-meta-item">

                <span class="ac-meta-label">

                    {{ $fr
                        ? 'Heure'
                        : 'Time'
                    }}

                </span>

                <span class="ac-meta-value">

                    {{ $lessonTime ?? '—' }}

                </span>

            </div>



            <div class="ac-meta-item">

                <span class="ac-meta-label">

                    {{ $fr
                        ? 'Durée'
                        : 'Duration'
                    }}

                </span>

                <span class="ac-meta-value">

                    {{ $booking->duration ?? 60 }}
                    min

                </span>

            </div>



            <div class="ac-meta-item">

                <span class="ac-meta-label">

                    {{ $fr
                        ? 'Prix'
                        : 'Price'
                    }}

                </span>

                <span class="ac-meta-value">

                    ${{ number_format(
                        (float) ($booking->price ?? 0),
                        2
                    ) }}

                </span>

            </div>


        </div>


    </div>



    {{-- =====================================================
       CHAT
    ====================================================== --}}

    <div class="ac-chat">


        {{-- CHAT HEADER --}}

        <div class="ac-chat-header">


            <div class="ac-chat-header-left">


                <div class="ac-chat-header-title">

                    <span class="ac-online-dot"></span>

                    <span>

                        {{ $teacherName }}
                        ↔
                        {{ $studentName }}

                    </span>

                </div>


                <div class="ac-chat-header-subtitle">

                    {{ $fr
                        ? 'Historique complet de la conversation'
                        : 'Complete conversation history'
                    }}

                </div>


            </div>



            <span class="ac-monitor-badge">

                {{ $fr
                    ? 'Vue administrateur'
                    : 'Admin View'
                }}

            </span>


        </div>



        {{-- =================================================
           MESSAGE HISTORY
        ================================================== --}}

        <div
            class="ac-chat-history"
            id="adminConversationHistory"
        >


            @forelse($booking->messages->sortBy('created_at') as $message)


                @php

                    $senderRole =
                        $message->sender->role
                        ?? 'unknown';


                    if ($senderRole === 'admin') {

                        $rowClass =
                            'support';

                        $senderName =
                            'DancePair Support';

                    } elseif ($senderRole === 'teacher') {

                        $rowClass =
                            'teacher';

                        $senderName =
                            $message->sender->name
                            ?? $teacherName;

                    } else {

                        $rowClass =
                            'student';

                        $senderName =
                            $message->sender->name
                            ?? $studentName;
                    }

                @endphp



                <div
                    class="
                        ac-message-row
                        {{ $rowClass }}
                    "
                >


                    <div class="ac-message-bubble">


                        {{-- META --}}

                        <div class="ac-message-meta">


                            <span class="ac-message-name">

                                @if($senderRole === 'admin')

                                    <span class="ac-support-dot"></span>

                                @endif

                                {{ $senderName }}

                            </span>



                            <span class="ac-message-date">

                                {{ $message->created_at
                                    ->locale(
                                        app()->getLocale()
                                    )
                                    ->translatedFormat(
                                        $fr
                                            ? 'd M · H:i'
                                            : 'M d · g:i A'
                                    )
                                }}

                            </span>


                        </div>



                        {{-- MESSAGE --}}

                        <div class="ac-message-text">{{ $message->message }}</div>


                    </div>


                </div>


            @empty


                <div class="ac-chat-empty">

                    {{ $fr
                        ? 'Aucun message dans cette conversation.'
                        : 'No messages in this conversation.'
                    }}

                </div>


            @endforelse


        </div>



        {{-- =================================================
           ADMIN REPLY
        ================================================== --}}

        <div class="ac-reply">


            <form
                method="POST"
                action="{{ route(
                    'admin.conversations.reply',
                    $booking
                ) }}"
            >

                @csrf



                <div class="ac-reply-top">


                    <label
                        for="adminConversationReply"
                        class="ac-reply-label"
                    >

                        {{ $fr
                            ? 'Intervenir dans la conversation'
                            : 'Join this conversation'
                        }}

                        <span class="ac-support-badge">

                            DancePair Support

                        </span>

                    </label>



                    <span class="ac-reply-note">

                        {{ $fr
                            ? 'Visible par les deux utilisateurs'
                            : 'Visible to both users'
                        }}

                    </span>


                </div>



                <textarea
                    id="adminConversationReply"
                    name="message"
                    maxlength="3000"
                    required
                    class="ac-textarea"
                    placeholder="{{ $fr
                        ? 'Écrivez votre message en tant que DancePair Support...'
                        : 'Write your message as DancePair Support...'
                    }}"
                >{{ old('message') }}</textarea>



                <div class="ac-reply-footer">


                    <div class="ac-reply-help">

                        {{ $fr
                            ? 'Le professeur et l’étudiant verront ce message directement dans leur conversation sous le nom DancePair Support.'
                            : 'Teacher and Student will see this message directly in their conversation under the name DancePair Support.'
                        }}

                    </div>



                    <button
                        type="submit"
                        class="ac-send-button"
                    >

                        {{ $fr
                            ? 'Envoyer'
                            : 'Send Reply'
                        }}

                    </button>


                </div>


            </form>


        </div>


    </div>


</div>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const history =
            document.getElementById(
                'adminConversationHistory'
            );

        if (history) {

            history.scrollTop =
                history.scrollHeight;
        }
    }
);

</script>

@endsection