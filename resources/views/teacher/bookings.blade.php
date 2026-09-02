@extends('teacher.layout')

@section('title', __('teacher.bookings'))
@section('page-title', __('teacher.bookings'))

@section('content')

@if(session('success'))
    <div class="alert alert-success mb-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mb-3">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger mb-3">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif


<style>

/* =========================================================
   CLEAN ACTION AREA
========================================================= */

.teacher-request-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;

    flex-wrap: nowrap;
}

.teacher-request-actions .btn {
    white-space: nowrap;
}

.teacher-request-actions .btn-outline-primary {
    min-width: 90px;
}

.teacher-message-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;

    min-width: 105px;
}

.teacher-message-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 18px;
    height: 18px;

    padding: 0 5px;

    border-radius: 20px;

    background: #0284C7;
    color: #FFFFFF;

    font-size: 9px;
    font-weight: 700;
}


/* =========================================================
   MORE MENU
========================================================= */

.teacher-more-btn {
    width: 38px;
    min-width: 38px !important;
    height: 34px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0;

    border-radius: 8px;

    font-size: 20px;
    line-height: 1;
    font-weight: 700;
}

.teacher-actions-dropdown {
    min-width: 175px;

    padding: 6px;

    border: 1px solid #D9E2EC;
    border-radius: 10px;

    box-shadow: 0 12px 30px rgba(15,23,42,.12);
}

.teacher-actions-dropdown .dropdown-item {
    width: 100%;

    display: flex;
    align-items: center;
    gap: 8px;

    padding: 9px 11px;

    border: 0;
    border-radius: 7px;

    background: transparent;

    font-size: 13px;

    text-align: left;
}

.teacher-actions-dropdown .dropdown-item:hover {
    background: #F4F7FB;
}

.teacher-actions-dropdown form {
    margin: 0;
}

.teacher-actions-dropdown .action-review {
    color: #8A6500;
}

.teacher-actions-dropdown .action-accept {
    color: #15803D;
    font-weight: 600;
}

.teacher-actions-dropdown .action-refuse {
    color: #DC2626;
}


/* =========================================================
   MESSAGE AREA
========================================================= */

.teacher-message-area {
    padding: 18px;

    margin-top: 10px;
    margin-bottom: 14px;

    border: 1px solid #CDE7F7;
    border-radius: 14px;

    background: rgba(255,255,255,.65);
}

.teacher-conversation-top {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 14px;
}

.teacher-conversation-title {
    font-size: 15px;
    font-weight: 700;
}

.teacher-conversation-student {
    color: #64748B;
    font-size: 11px;
}


/* =========================================================
   CHAT HISTORY
========================================================= */

.teacher-conversation-history {
    max-height: 380px;

    overflow-y: auto;

    padding: 12px;

    margin-bottom: 15px;

    border: 1px solid #D7E9F5;
    border-radius: 12px;

    background: #FFFFFF;
}

.teacher-chat-message-row {
    display: flex;

    margin-bottom: 11px;
}

.teacher-chat-message-row:last-child {
    margin-bottom: 0;
}

.teacher-chat-message-row.mine {
    justify-content: flex-end;
}

.teacher-chat-message-row.theirs {
    justify-content: flex-start;
}

.teacher-chat-bubble {
    width: fit-content;
    max-width: 72%;

    padding: 9px 12px;

    border-radius: 13px;

    font-size: 13px;
    line-height: 1.5;

    word-break: break-word;
}

.teacher-chat-message-row.mine
.teacher-chat-bubble {
    background: #0284C7;
    color: #FFFFFF;

    border-bottom-right-radius: 4px;
}

.teacher-chat-message-row.theirs
.teacher-chat-bubble {
    background: #EDF6FC;
    color: #1F2937;

    border-bottom-left-radius: 4px;
}

.teacher-chat-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    margin-bottom: 5px;

    font-size: 10px;
    font-weight: 700;

    opacity: .85;
}

.teacher-chat-date {
    white-space: nowrap;

    font-size: 9px;
    font-weight: 400;

    opacity: .8;
}


/* =========================================================
   MESSAGE FORM
========================================================= */

.teacher-message-form-label {
    display: block;

    margin-bottom: 6px;

    font-size: 12px;
    font-weight: 700;
}

.teacher-message-textarea {
    width: 100%;
    min-height: 85px;

    resize: vertical;

    padding: 10px 12px;

    border: 1px solid #C9DDEA;
    border-radius: 10px;

    background: #FFFFFF;

    font-size: 13px;
    line-height: 1.5;

    outline: none;
}

.teacher-message-textarea:focus {
    border-color: #0284C7;

    box-shadow:
        0 0 0 3px rgba(2,132,199,.10);
}

.teacher-message-warning {
    display: block;

    margin-top: 5px;
    margin-bottom: 11px;

    color: #8495A5;

    font-size: 10px;
    line-height: 1.4;
}

.teacher-message-action-row {
    display: flex;
    justify-content: flex-end;
}

.teacher-message-action-row .btn {
    min-width: 170px;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 850px) {

    .teacher-request-actions {
        justify-content: flex-start;
        flex-wrap: wrap;
    }

}


@media (max-width: 650px) {

    .teacher-chat-bubble {
        max-width: 88%;
    }

    .teacher-chat-meta {
        align-items: flex-start;
        flex-direction: column;
        gap: 2px;
    }

    .teacher-message-action-row .btn {
        width: 100%;
    }

}

</style>


<div class="card profile-card p-4">

    <div class="mb-3">

        <h3 class="mb-1">
            {{ __('teacher.lesson_requests') }}
        </h3>

        <p class="text-muted mb-0">
            {{ __('teacher.lesson_requests_subtitle') }}
        </p>

    </div>


    <div class="teacher-request-list">

        @forelse($bookings as $booking)

            @php
                $start = \Carbon\Carbon::parse(
                    $booking->lesson_time
                );

                $end = $start->copy()->addMinutes(
                    $booking->duration ?? 60
                );

                $teacherReview = $booking->teacherReview;
            @endphp


            {{-- ================================================= --}}
            {{-- REQUEST ROW --}}
            {{-- ================================================= --}}

            <div
                class="teacher-request-row
                {{ is_null($booking->teacher_viewed_at)
                    ? 'request-unread'
                    : 'request-read' }}"
            >


                {{-- STUDENT --}}
                <div class="request-student">

                    <div class="request-student-name">
                        {{ $booking->student->user->name ?? __('teacher.student') }}
                    </div>

                    <div class="request-location">

                        {{ $booking->student->city ?? __('teacher.location_not_set') }}

                        @if($booking->student->province)
                            , {{ $booking->student->province }}
                        @endif

                    </div>

                </div>


                {{-- DANCE --}}
                <div class="request-column">

                    <span class="request-label">
                        {{ __('teacher.dance') }}
                    </span>

                    <strong>
                        {{ $booking->danceStyle->name ?? __('teacher.dance') }}
                    </strong>

                </div>


                {{-- DATE --}}
                <div class="request-column">

                    <span class="request-label">
                        {{ __('teacher.date') }}
                    </span>

                    <strong>
                        {{ \Carbon\Carbon::parse(
                            $booking->lesson_date
                        )
                        ->locale(app()->getLocale())
                        ->translatedFormat(
                            app()->getLocale() === 'fr'
                                ? 'd M Y'
                                : 'M d, Y'
                        ) }}
                    </strong>

                </div>


                {{-- TIME --}}
                <div class="request-column">

                    <span class="request-label">
                        {{ __('teacher.time') }}
                    </span>

                    <strong>

                        @if(app()->getLocale() === 'fr')

                            {{ $start->format('H:i') }}
                            -
                            {{ $end->format('H:i') }}

                        @else

                            {{ $start->format('g:i A') }}
                            -
                            {{ $end->format('g:i A') }}

                        @endif

                    </strong>

                </div>


                {{-- STATUS --}}
                <div class="request-status-area">

                    @if($booking->status === 'pending')

                        <span class="teacher-request-status pending">
                            {{ __('teacher.pending') }}
                        </span>

                    @elseif($booking->status === 'confirmed')

                        <span class="teacher-request-status accepted">
                            {{ __('teacher.accepted') }}
                        </span>

                    @elseif($booking->status === 'cancelled')

                        <span class="teacher-request-status refused">
                            {{ __('teacher.refused') }}
                        </span>

                    @elseif($booking->status === 'completed')

                        <span class="teacher-request-status completed">
                            {{ __('teacher.completed') }}
                        </span>

                    @else

                        <span class="teacher-request-status">
                            {{ ucfirst($booking->status) }}
                        </span>

                    @endif

                </div>


                {{-- ================================================= --}}
                {{-- ACTIONS --}}
                {{-- ================================================= --}}

                <div class="teacher-request-actions">


                    {{-- PROFILE --}}
                    <a
                        href="{{ route(
                            'teacher.bookings.student',
                            $booking
                        ) }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        {{ __('teacher.profile') }}
                    </a>


                    {{-- MESSAGES --}}
                    {{-- MESSAGES --}}

                    @php
    $receivedMessagesCount = $booking->messages
        ->where('sender_id', '!=', auth()->id())
        ->whereNull('read_at')
        ->count();
@endphp

<button
    type="button"
    class="btn btn-sm btn-outline-primary teacher-message-btn"
    data-bs-toggle="collapse"
    data-bs-target="#teacherMessagesBooking{{ $booking->id }}"
    aria-controls="teacherMessagesBooking{{ $booking->id }}"
    aria-expanded="false"
>
    {{ __('teacher.messages') }}

    @if($receivedMessagesCount > 0)

        <span class="teacher-message-count">
            {{ $receivedMessagesCount }}
        </span>

    @endif
</button>


                    {{-- MORE ACTIONS --}}
                    <div class="dropdown">

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-secondary teacher-more-btn"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            aria-label="More actions"
                        >
                            ⋯
                        </button>


                        <div class="dropdown-menu dropdown-menu-end teacher-actions-dropdown">


                            {{-- REVIEW --}}
                            <button
                                type="button"
                                class="dropdown-item action-review"
                                data-bs-toggle="collapse"
                                data-bs-target="#teacherReviewBooking{{ $booking->id }}"
                                aria-controls="teacherReviewBooking{{ $booking->id }}"
                                aria-expanded="false"
                            >
                                <span>★</span>

                                @if($teacherReview)

                                    {{ __('teacher.edit_review') }}

                                @else

                                    {{ __('teacher.review') }}

                                @endif
                            </button>


                            {{-- PENDING ACTIONS --}}
                            @if($booking->status === 'pending')

                                <div class="dropdown-divider"></div>


                                {{-- ACCEPT --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                                        'teacher.bookings.accept',
                                        $booking
                                    ) }}"
                                    onsubmit="return confirm(
                                        '{{ __('teacher.accept_confirmation') }}'
                                    )"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="dropdown-item action-accept"
                                    >
                                        <span>✓</span>
                                        {{ __('teacher.accept') }}
                                    </button>

                                </form>


                                {{-- REFUSE --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                                        'teacher.bookings.reject',
                                        $booking
                                    ) }}"
                                    onsubmit="return confirm(
                                        '{{ __('teacher.refuse_confirmation') }}'
                                    )"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="dropdown-item action-refuse"
                                    >
                                        <span>✕</span>
                                        {{ __('teacher.refuse') }}
                                    </button>

                                </form>

                            @endif

                        </div>

                    </div>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- MESSAGES --}}
            {{-- ================================================= --}}

            <div
                class="collapse"
                id="teacherMessagesBooking{{ $booking->id }}"
            >

                <div class="teacher-message-area">


                    {{-- =================================================
                       SHOW CONVERSATION ONLY IF MESSAGES EXIST
                    ================================================== --}}

                    @if($booking->messages->isNotEmpty())

                        <div class="teacher-conversation-top">

                            <div>

                                <div class="teacher-conversation-title">
                                    {{ __('teacher.conversation') }}
                                </div>

                                <div class="teacher-conversation-student">

                                    {{ $booking->student->user->name
                                        ?? __('teacher.student')
                                    }}

                                </div>

                            </div>


                            <span
                                class="teacher-request-status
                                {{ $booking->status === 'pending'
                                    ? 'pending'
                                    : (
                                        $booking->status === 'confirmed'
                                            ? 'accepted'
                                            : (
                                                $booking->status === 'cancelled'
                                                    ? 'refused'
                                                    : (
                                                        $booking->status === 'completed'
                                                            ? 'completed'
                                                            : ''
                                                    )
                                            )
                                    )
                                }}"
                            >

                                @if($booking->status === 'pending')

                                    {{ __('teacher.pending') }}

                                @elseif($booking->status === 'confirmed')

                                    {{ __('teacher.accepted') }}

                                @elseif($booking->status === 'cancelled')

                                    {{ __('teacher.refused') }}

                                @elseif($booking->status === 'completed')

                                    {{ __('teacher.completed') }}

                                @else

                                    {{ ucfirst($booking->status) }}

                                @endif

                            </span>

                        </div>


                        {{-- CHAT HISTORY --}}
                        <div class="teacher-conversation-history">

                            @foreach($booking->messages as $message)

                                @php
                                    $isMine =
                                        (int) $message->sender_id ===
                                        (int) auth()->id();
                                @endphp


                                <div
                                    class="teacher-chat-message-row
                                    {{ $isMine ? 'mine' : 'theirs' }}"
                                >

                                    <div class="teacher-chat-bubble">


                                        <div class="teacher-chat-meta">

                                            <span>

                                                @if($isMine)

                                                    {{ __('teacher.you') }}

                                                @else

                                                    {{ $message->sender->name
                                                        ?? __('teacher.student')
                                                    }}

                                                @endif

                                            </span>


                                            <span class="teacher-chat-date">

                                                @if(app()->getLocale() === 'fr')

                                                    {{ $message->created_at
                                                        ->locale('fr')
                                                        ->translatedFormat('d M Y')
                                                    }}

                                                    ·

                                                    {{ $message->created_at
                                                        ->format('H:i')
                                                    }}

                                                @else

                                                    {{ $message->created_at
                                                        ->locale('en')
                                                        ->translatedFormat('M d, Y')
                                                    }}

                                                    ·

                                                    {{ $message->created_at
                                                        ->format('g:i A')
                                                    }}

                                                @endif

                                            </span>

                                        </div>


                                        <div>
                                            {{ $message->message }}
                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @endif


                    {{-- =================================================
                       TEACHER SEND / REPLY
                    ================================================== --}}

                    <form
                        method="POST"
                        action="{{ route(
                            'bookings.messages.store',
                            $booking
                        ) }}"
                    >

                        @csrf


                        <input
                            type="hidden"
                            name="booking_id"
                            value="{{ $booking->id }}"
                        >


                        <label
                            for="teacher_message_{{ $booking->id }}"
                            class="teacher-message-form-label"
                        >

                            @if($booking->messages->isNotEmpty())

                                {{ __('teacher.reply_to_student') }}

                            @else

                                {{ __('teacher.message_to_student') }}

                            @endif

                        </label>


                        <textarea
                            id="teacher_message_{{ $booking->id }}"
                            name="message"
                            class="teacher-message-textarea"
                            maxlength="3000"
                            required
                            placeholder="{{ __('teacher.message_placeholder') }}"
                        >{{ old('booking_id') == $booking->id ? old('message') : '' }}</textarea>


                        <small class="teacher-message-warning">
                            {{ __('teacher.contact_info_warning') }}
                        </small>


                        <div class="teacher-message-action-row">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                {{ __('teacher.send_message') }}
                            </button>

                        </div>

                    </form>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- REVIEW STUDENT --}}
            {{-- ================================================= --}}

            <div
                class="collapse"
                id="teacherReviewBooking{{ $booking->id }}"
            >
                <div class="student-review-area">

                    @php
                        $teacherReview = $booking->teacherReview;
                    @endphp

                    <form
                        method="POST"
                        action="{{ route(
                            'teacher.reviews.store',
                            $booking
                        ) }}"
                    >
                        @csrf

                        <div class="row g-3 align-items-end">

                            {{-- STARS --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    {{ __('teacher.rate_this_student') }}
                                </label>

                                <div class="student-review-stars">

                                    @for($star = 5; $star >= 1; $star--)

                                        <input
                                            type="radio"
                                            name="rating"
                                            value="{{ $star }}"
                                            id="teacher_rating_{{ $booking->id }}_{{ $star }}"
                                            {{
                                                (int) old(
                                                    'rating',
                                                    $teacherReview?->rating
                                                ) === $star
                                                    ? 'checked'
                                                    : ''
                                            }}
                                            required
                                        >

                                        <label
                                            for="teacher_rating_{{ $booking->id }}_{{ $star }}"
                                            title="{{ $star }} {{ __('teacher.stars') }}"
                                        >
                                            ★
                                        </label>

                                    @endfor

                                </div>

                            </div>


                            {{-- COMMENT --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    {{ __('teacher.review') }}
                                </label>

                                <input
                                    type="text"
                                    name="comment"
                                    class="form-control form-control-sm"
                                    maxlength="1500"
                                    value="{{ old(
                                        'comment',
                                        $teacherReview?->comment
                                    ) }}"
                                    placeholder="{{ __('teacher.review_placeholder') }}"
                                >

                            </div>


                            {{-- SAVE --}}
                            <div class="col-md-2">

                                <button
                                    type="submit"
                                    class="btn btn-warning btn-sm w-100"
                                >
                                    @if($teacherReview)
                                        {{ __('teacher.update') }}
                                    @else
                                        {{ __('teacher.save') }}
                                    @endif
                                </button>

                            </div>

                        </div>


                        @if($teacherReview)

                            <div class="student-review-existing">

                                {{ __('teacher.current_rating') }}

                                {{ str_repeat(
                                    '★',
                                    (int) $teacherReview->rating
                                ) }}

                                {{ str_repeat(
                                    '☆',
                                    5 - (int) $teacherReview->rating
                                ) }}

                            </div>

                        @endif

                    </form>

                </div>
            </div>


        @empty

            <div class="text-muted py-4 text-center">
                {{ __('teacher.no_lesson_requests') }}
            </div>

        @endforelse

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | SCROLL CONVERSATION TO LAST MESSAGE
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '[id^="teacherMessagesBooking"]'
            )
            .forEach(
                function (messagePanel) {

                    messagePanel.addEventListener(
                        'shown.bs.collapse',
                        function () {

                            const history =
                                messagePanel.querySelector(
                                    '.teacher-conversation-history'
                                );

                            if (history) {

                                history.scrollTop =
                                    history.scrollHeight;

                            }

                        }
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | CLOSE DROPDOWN AFTER REVIEW CLICK
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.action-review')
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const dropdownElement =
                                button.closest('.dropdown');

                            if (!dropdownElement) {
                                return;
                            }

                            const toggle =
                                dropdownElement.querySelector(
                                    '[data-bs-toggle="dropdown"]'
                                );

                            if (
                                toggle &&
                                typeof bootstrap !== 'undefined'
                            ) {

                                const dropdown =
                                    bootstrap.Dropdown.getOrCreateInstance(
                                        toggle
                                    );

                                dropdown.hide();

                            }

                        }
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | AUTO OPEN MESSAGE AREA AFTER VALIDATION ERROR
        |--------------------------------------------------------------------------
        */

        @if($errors->any() && old('booking_id'))

            const messagePanel =
                document.getElementById(
                    'teacherMessagesBooking{{ old('booking_id') }}'
                );

            if (
                messagePanel &&
                typeof bootstrap !== 'undefined'
            ) {

                const collapse =
                    bootstrap.Collapse.getOrCreateInstance(
                        messagePanel,
                        {
                            toggle: false
                        }
                    );

                collapse.show();

            }

        @endif

    }
);

</script>

@endsection