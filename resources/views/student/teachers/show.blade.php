@extends('student.layout')

@section('title', __('student.teacher_profile'))
@section('page-title', __('student.teacher_profile'))

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
   TEACHER PROFILE
========================================================= */

.student-teacher-profile {
    display: grid;
    grid-template-columns: 130px 1fr 320px;
    gap: 22px;
    align-items: start;
}

.student-teacher-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ffffff;
    box-shadow: 0 8px 20px rgba(2,132,199,.12);
}

.student-teacher-photo-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #DFF2FF;
    color: #0369A1;

    font-size: 38px;
    font-weight: 700;
}

.student-teacher-name {
    font-size: 25px;
    font-weight: 700;
    margin-bottom: 3px;
}

.student-teacher-location {
    color: #6B7280;
    font-size: 14px;
    margin-bottom: 10px;
}

.student-teacher-bio {
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 12px;
}

.teacher-style-prices {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.teacher-style-price {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    padding: 6px 10px;

    border-radius: 10px;

    background: #FFFFFF;
    border: 1px solid #CCE6F7;

    font-size: 12px;
}

.teacher-style-price strong {
    font-weight: 600;
}

.teacher-style-price span {
    color: #0284C7;
    font-weight: 700;
}


/* =========================================================
   VIDEO
========================================================= */

.student-teacher-video-title {
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 7px;
}

.student-teacher-video video {
    width: 100%;
    max-height: 180px;
    border-radius: 12px;
    background: #000;
}

.student-teacher-no-video {
    min-height: 120px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1px dashed #BDDDF0;
    border-radius: 12px;

    color: #94A3B8;
    font-size: 13px;

    background: #FFFFFF;
}


/* =========================================================
   RATING
========================================================= */

.teacher-rating {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.teacher-stars {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2px;
}

.teacher-star {
    font-size: 21px;
    line-height: 1;
    color: #D1D5DB;
}

.teacher-star.active {
    color: #F5B301;
}

.teacher-rating-info {
    margin-top: 2px;
    font-size: 12px;
    color: #6B7280;
    text-align: center;
}

.teacher-rating-info strong {
    color: #111827;
    font-weight: 700;
}


/* =========================================================
   AVAILABILITIES
========================================================= */

.availability-header {
    margin-bottom: 16px;
}

.availability-header h3 {
    margin: 0;
    font-size: 20px;
}

.availability-record {
    border-bottom: 1px solid #D7E9F5;
}

.availability-record:last-child {
    border-bottom: none;
}


/* COLLAPSED ROW */

.availability-row {
    display: grid;

    grid-template-columns:
        180px
        190px
        minmax(220px, 320px)
        minmax(320px, 1fr)
        44px;

    align-items: center;

    gap: 14px;

    min-height: 70px;

    padding: 10px 6px;
}

.availability-label {
    display: block;

    font-size: 9px;
    font-weight: 600;

    color: #8CA0AF;

    text-transform: uppercase;

    margin-bottom: 2px;
}

.availability-value {
    font-size: 13px;
    font-weight: 600;
}

.availability-price {
    color: #0284C7;
    font-weight: 700;
}


/* ARROW */

.availability-toggle {
    width: 36px;
    height: 36px;

    padding: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #BFDFF1;
    border-radius: 9px;

    background: #FFFFFF;
    color: #0284C7;

    cursor: pointer;

    transition: .2s ease;
}

.availability-toggle:hover {
    background: #EAF7FF;
}

.availability-toggle-arrow {
    display: block;

    font-size: 13px;

    transition: transform .2s ease;
}

.availability-toggle.open
.availability-toggle-arrow {
    transform: rotate(180deg);
}


/* =========================================================
   EXPANDED PANEL
========================================================= */

.availability-details {
    display: none;

    padding:
        4px
        54px
        22px
        6px;
}

.availability-details.open {
    display: block;
}

.lesson-expanded-box {
    padding: 18px;

    border: 1px solid #CDE7F7;
    border-radius: 14px;

    background: rgba(255,255,255,.58);
}


/* =========================================================
   BOOKING STATUS
========================================================= */

.conversation-top {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 14px;
}

.conversation-title {
    font-size: 15px;
    font-weight: 700;
}

.booking-status-badge {
    padding: 5px 10px;

    border-radius: 20px;

    background: #E0F2FE;
    color: #0369A1;

    font-size: 11px;
    font-weight: 700;
}


/* =========================================================
   CHAT HISTORY
========================================================= */

.conversation-history {
    max-height: 380px;

    overflow-y: auto;

    padding: 12px;

    margin-bottom: 15px;

    border: 1px solid #D7E9F5;
    border-radius: 12px;

    background: #FFFFFF;
}

.conversation-empty {
    padding: 20px;

    text-align: center;

    color: #94A3B8;

    font-size: 12px;
}

.chat-message-row {
    display: flex;
    margin-bottom: 11px;
}

.chat-message-row:last-child {
    margin-bottom: 0;
}

.chat-message-row.mine {
    justify-content: flex-end;
}

.chat-message-row.theirs {
    justify-content: flex-start;
}

.chat-bubble {
    width: fit-content;
    max-width: 72%;

    padding: 9px 12px;

    border-radius: 13px;

    font-size: 13px;
    line-height: 1.5;

    word-break: break-word;
}

.chat-message-row.mine
.chat-bubble {
    background: #0284C7;
    color: #FFFFFF;

    border-bottom-right-radius: 4px;
}

.chat-message-row.theirs
.chat-bubble {
    background: #EDF6FC;
    color: #1F2937;

    border-bottom-left-radius: 4px;
}

.chat-sender {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;

    margin-bottom: 5px;

    font-size: 10px;
    font-weight: 700;

    opacity: .85;
}

.chat-message-date {
    font-size: 9px;
    font-weight: 400;
    white-space: nowrap;
    opacity: .8;
}

.chat-time {
    margin-top: 5px;

    font-size: 9px;

    opacity: .7;
}


/* =========================================================
   MESSAGE FORM
========================================================= */

.message-form-label {
    display: block;

    margin-bottom: 6px;

    font-size: 12px;
    font-weight: 700;
}

.message-textarea {
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

.message-textarea:focus {
    border-color: #0284C7;

    box-shadow:
        0 0 0 3px rgba(2,132,199,.10);
}

.message-warning {
    display: block;

    margin-top: 5px;
    margin-bottom: 11px;

    color: #8495A5;

    font-size: 10px;
    line-height: 1.4;
}

.message-action-row {
    display: flex;
    justify-content: flex-end;
}

.message-action-row .btn {
    min-width: 170px;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 1000px) {

    .student-teacher-profile {
        grid-template-columns: 110px 1fr;
    }

    .student-teacher-video {
        grid-column: 1 / -1;
    }

    .availability-row {
        grid-template-columns:
            1fr
            1fr
            1fr
            100px
            40px;
    }
}


@media (max-width: 750px) {

    .availability-row {
        grid-template-columns:
            1fr
            1fr
            40px;
    }

    .availability-row > div:nth-child(3),
    .availability-row > div:nth-child(4) {
        grid-column: span 1;
    }

    .availability-details {
        padding:
            6px
            0
            18px;
    }
}


@media (max-width: 650px) {

    .student-teacher-profile {
        grid-template-columns: 1fr;
    }

    .student-teacher-photo,
    .student-teacher-photo-placeholder {
        width: 100px;
        height: 100px;
    }

    .availability-row {
        grid-template-columns:
            1fr
            40px;
    }

    .availability-row > div {
        grid-column: 1;
    }

    .availability-row
    .availability-toggle-wrapper {
        grid-column: 2;
        grid-row: 1 / span 4;
        align-self: center;
    }

    .chat-bubble {
        max-width: 88%;
    }

    .message-action-row .btn {
        width: 100%;
    }
}

</style>



{{-- =========================================================
   PROFILE
========================================================= --}}

<div class="card profile-card p-4 mb-4">

    <div class="student-teacher-profile">


        {{-- PHOTO --}}
        <div>

            @if($teacher->profile_photo)

                <img
                    src="{{ asset('storage/' . $teacher->profile_photo) }}"
                    alt="{{ $teacher->user->name }}"
                    class="student-teacher-photo"
                >

            @else

                <div class="student-teacher-photo-placeholder">

                    {{ strtoupper(
                        substr(
                            $teacher->user->name ?? 'T',
                            0,
                            1
                        )
                    ) }}

                </div>

            @endif


            @php
                $averageRating = $averageRating ?? 0;
                $reviewCount = $reviewCount ?? 0;
                $roundedRating = round($averageRating);
            @endphp


            <div class="teacher-rating mt-3">

                <div class="teacher-stars">

                    @for($i = 1; $i <= 5; $i++)

                        <span
                            class="teacher-star
                            {{ $i <= $roundedRating ? 'active' : '' }}"
                        >
                            ★
                        </span>

                    @endfor

                </div>


                <div class="teacher-rating-info">

                    @if($reviewCount > 0)

                        <strong>
                            {{ number_format($averageRating, 1) }}
                        </strong>

                        <span>

                            ({{ $reviewCount }}

                            {{ $reviewCount === 1
                                ? __('student.review')
                                : __('student.reviews_plural')
                            }})

                        </span>

                    @else

                        <span>
                            {{ __('student.no_reviews') }}
                        </span>

                    @endif

                </div>

            </div>

        </div>



        {{-- INFO --}}
        <div>

            <div class="student-teacher-name">
                {{ $teacher->user->name ?? __('student.teacher') }}
            </div>


            <div class="student-teacher-location">

                {{ $teacher->city ?? __('student.location_not_set') }}

                @if($teacher->province)
                    , {{ $teacher->province }}
                @endif

                @if($teacher->country)
                    , {{ $teacher->country }}
                @endif

            </div>


            <div class="mb-2">

                <span class="badge bg-light text-dark border">

                    {{ $teacher->experience_years ?? 0 }}

                    @if(($teacher->experience_years ?? 0) == 1)

                        {{ __('student.year_experience') }}

                    @else

                        {{ __('student.years_experience') }}

                    @endif

                </span>

            </div>


            @if($teacher->bio)

                <div class="student-teacher-bio">
                    {{ $teacher->bio }}
                </div>

            @endif


            <div class="teacher-style-prices">

                @foreach($teacher->danceStyles as $style)

                    <div class="teacher-style-price">

                        <strong>
                            {{ $style->name }}
                        </strong>

                        <span>

                            @if($style->pivot->hourly_rate !== null)

                                ${{ number_format(
                                    $style->pivot->hourly_rate,
                                    2
                                ) }}

                            @else

                                {{ __('student.no_rate') }}

                            @endif

                        </span>

                    </div>

                @endforeach

            </div>

        </div>



        {{-- VIDEO --}}
        <div class="student-teacher-video">

            <div class="student-teacher-video-title">
                {{ __('student.introduction_video') }}
            </div>


            @if($teacher->intro_video)

                <video controls preload="metadata">

                    <source
                        src="{{ asset(
                            'storage/' . $teacher->intro_video
                        ) }}"
                    >

                    {{ __('student.browser_no_video') }}

                </video>

            @else

                <div class="student-teacher-no-video">
                    {{ __('student.no_introduction_video') }}
                </div>

            @endif

        </div>

    </div>

</div>



{{-- =========================================================
   AVAILABLE CLASSES
========================================================= --}}

<div class="card profile-card p-4">

    <div class="availability-header">

        <h3>
            {{ __('student.available_classes') }}
        </h3>

        <small class="text-muted">
            {{ __('student.choose_class_request') }}
        </small>

    </div>


    <div class="availability-list">

        @forelse($teacher->availabilities as $availability)

            @php

                $style = $teacher->danceStyles
                    ->firstWhere(
                        'id',
                        $availability->dance_style_id
                    );

                $styleRate =
                    $style?->pivot?->hourly_rate;


                $sessionStart =
                    \Carbon\Carbon::parse(
                        $availability->start_time
                    );

                $sessionEnd =
                    \Carbon\Carbon::parse(
                        $availability->end_time
                    );

                $sessionDurationMinutes =
                    $sessionStart->diffInMinutes(
                        $sessionEnd
                    );

                $sessionPrice =
                    $styleRate !== null
                        ? round(
                            (float) $styleRate
                            *
                            ($sessionDurationMinutes / 60),
                            2
                        )
                        : null;


                $availabilityDate =
                    \Carbon\Carbon::parse(
                        $availability->available_date
                    )->format('Y-m-d');

                $availabilityTime =
                    \Carbon\Carbon::parse(
                        $availability->start_time
                    )->format('H:i:s');

                $slotKey =
                    $availabilityDate .
                    '|' .
                    $availabilityTime;

                $booking =
                    $bookingsBySlot->get(
                        $slotKey
                    );

            @endphp


            <div
                class="availability-record"
                id="availability-record-{{ $availability->id }}"
            >


                {{-- =====================================================
                   COLLAPSED RECORD
                ====================================================== --}}

                <div class="availability-row">


                    {{-- DATE --}}
                    <div>

                        <span class="availability-label">
                            {{ __('student.date') }}
                        </span>

                        <div class="availability-value">

                            {{ \Carbon\Carbon::parse(
                                $availability->available_date
                            )
                            ->locale(app()->getLocale())
                            ->translatedFormat(
                                app()->getLocale() === 'fr'
                                    ? 'D d M Y'
                                    : 'D, M d, Y'
                            ) }}

                        </div>

                    </div>



                    {{-- TIME --}}
                    <div>

                        <span class="availability-label">
                            {{ __('student.time') }}
                        </span>

                        <div class="availability-value">

                            @if(app()->getLocale() === 'fr')

                                {{ \Carbon\Carbon::parse(
                                    $availability->start_time
                                )->format('H:i') }}

                                -

                                {{ \Carbon\Carbon::parse(
                                    $availability->end_time
                                )->format('H:i') }}

                            @else

                                {{ \Carbon\Carbon::parse(
                                    $availability->start_time
                                )->format('g:i A') }}

                                -

                                {{ \Carbon\Carbon::parse(
                                    $availability->end_time
                                )->format('g:i A') }}

                            @endif

                        </div>

                    </div>



                    {{-- DANCE --}}
                    <div>

                        <span class="availability-label">
                            {{ __('student.dance') }}
                        </span>

                        <div class="availability-value">

                            {{ $availability->danceStyle->name
                                ?? __('student.dance')
                            }}

                        </div>

                    </div>



                    {{-- RATE --}}
                    {{-- RATE --}}
<div>

    <span class="availability-label">
        {{ __('student.rate') }}
    </span>

    <div
        class="availability-price"
        style="
            display:flex;
            align-items:center;
            gap:8px;
            flex-wrap:nowrap;
            white-space:nowrap;
        "
    >

        @if($styleRate !== null)

            <span>
                ${{ number_format(
                    $styleRate,
                    2
                ) }}

                / {{ __('student.hr') }}
            </span>

            <span
                style="
                    color:#94A3B8;
                    font-weight:400;
                "
            >
                •
            </span>

            <span
                style="
                    color:#475569;
                    font-size:11px;
                    font-weight:600;
                "
            >
                {{ __('student.total') }}

                <strong
                    style="
                        color:#0284C7;
                        font-weight:700;
                    "
                >
                    ${{ number_format(
                        $sessionPrice,
                        2
                    ) }}
                </strong>
            </span>

        @else

            {{ __('student.not_set') }}

        @endif

    </div>

</div>



                    {{-- TOGGLE --}}
                    <div class="availability-toggle-wrapper">

                        <button
                            type="button"
                            class="availability-toggle"
                            data-target="availability-details-{{ $availability->id }}"
                            aria-label="{{ __('student.open_lesson_details') }}"
                        >
                            <span class="availability-toggle-arrow">
                                ▼
                            </span>
                        </button>

                    </div>

                </div>



                {{-- =====================================================
                   EXPANDED DETAILS
                ====================================================== --}}

                <div
                    class="availability-details"
                    id="availability-details-{{ $availability->id }}"
                >

                    <div class="lesson-expanded-box">


                        {{-- =================================================
                           BOOKING ALREADY EXISTS
                        ================================================== --}}

                        @if($booking)


                            {{-- =================================================
                               SHOW CONVERSATION ONLY AFTER FIRST MESSAGE EXISTS
                            ================================================== --}}

                            @if($booking->messages->isNotEmpty())

                                <div class="conversation-top">

                                    <div class="conversation-title">
                                        {{ __('student.conversation') }}
                                    </div>


                                    <span class="booking-status-badge">

                                        {{ __(
                                            'student.' . $booking->status
                                        ) }}

                                    </span>

                                </div>



                                {{-- CHAT HISTORY --}}
                                <div class="conversation-history">

                                    @foreach($booking->messages as $message)

                                        @php

                                            $isMine =
                                                (int) $message->sender_id ===
                                                (int) auth()->id();

                                        @endphp


                                        <div
                                            class="chat-message-row
                                            {{ $isMine ? 'mine' : 'theirs' }}"
                                        >

                                            <div class="chat-bubble">


                                                <div class="chat-sender">

                                                    <span>

                                                        @if($isMine)

                                                            {{ __('student.you') }}

                                                        @else

                                                            {{ $message->sender->name
                                                                ?? __('student.teacher')
                                                            }}

                                                        @endif

                                                    </span>


                                                    <span class="chat-message-date">

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
                               REPLY AFTER BOOKING EXISTS
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
                                    name="availability_id"
                                    value="{{ $availability->id }}"
                                >


                                <label
                                    for="reply_message_{{ $availability->id }}"
                                    class="message-form-label"
                                >
                                    {{ __('student.reply_to_teacher') }}
                                </label>


                                <textarea
                                    id="reply_message_{{ $availability->id }}"
                                    name="message"
                                    class="message-textarea"
                                    maxlength="3000"
                                    required
                                    placeholder="{{ __('student.reply_placeholder') }}"
                                ></textarea>


                                <small class="message-warning">
                                    {{ __('student.contact_info_warning') }}
                                </small>


                                <div class="message-action-row">

                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        {{ __('student.send_message') }}
                                    </button>

                                </div>

                            </form>



                        {{-- =================================================
                           NO BOOKING YET
                           FIRST MESSAGE + REQUEST
                        ================================================== --}}

                        @else


                            @if($styleRate !== null)

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'student.booking.request',
                                        $availability
                                    ) }}"
                                >

                                    @csrf


                                    <input
                                        type="hidden"
                                        name="availability_id"
                                        value="{{ $availability->id }}"
                                    >


                                    <label
                                        for="request_message_{{ $availability->id }}"
                                        class="message-form-label"
                                    >
                                        {{ __('student.message_to_teacher_optional') }}
                                    </label>


                                    <textarea
                                        id="request_message_{{ $availability->id }}"
                                        name="message"
                                        class="message-textarea"
                                        maxlength="3000"
                                        placeholder="{{ __('student.message_to_teacher_placeholder') }}"
                                    ></textarea>


                                    <small class="message-warning">
                                        {{ __('student.contact_info_warning') }}
                                    </small>


                                    <div class="message-action-row">

                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                        >
                                            {{ __('student.request') }}
                                        </button>

                                    </div>

                                </form>


                            @else

                                <div class="alert alert-secondary mb-0">
                                    {{ __('student.unavailable') }}
                                </div>

                            @endif

                        @endif

                    </div>

                </div>

            </div>


        @empty

            <div class="text-muted py-3">
                {{ __('student.no_available_classes') }}
            </div>

        @endforelse

    </div>

</div>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const toggleButtons =
            document.querySelectorAll(
                '.availability-toggle'
            );


        toggleButtons.forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        const targetId =
                            button.dataset.target;

                        const panel =
                            document.getElementById(
                                targetId
                            );

                        if (!panel) {
                            return;
                        }


                        const isOpen =
                            panel.classList.contains(
                                'open'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | CLOSE ALL OTHER RECORDS
                        |--------------------------------------------------------------------------
                        */

                        document
                            .querySelectorAll(
                                '.availability-details.open'
                            )
                            .forEach(
                                function (openPanel) {

                                    openPanel.classList.remove(
                                        'open'
                                    );

                                }
                            );


                        document
                            .querySelectorAll(
                                '.availability-toggle.open'
                            )
                            .forEach(
                                function (openButton) {

                                    openButton.classList.remove(
                                        'open'
                                    );

                                }
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | OPEN CURRENT
                        |--------------------------------------------------------------------------
                        */

                        if (!isOpen) {

                            panel.classList.add(
                                'open'
                            );

                            button.classList.add(
                                'open'
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | SCROLL CHAT TO LAST MESSAGE
                            |--------------------------------------------------------------------------
                            */

                            const history =
                                panel.querySelector(
                                    '.conversation-history'
                                );

                            if (history) {

                                history.scrollTop =
                                    history.scrollHeight;
                            }
                        }

                    }
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | AUTO OPEN RECORD AFTER VALIDATION ERROR
        |--------------------------------------------------------------------------
        */

        @if($errors->any() && old('availability_id'))

            const errorButton =
                document.querySelector(
                    '[data-target="availability-details-{{ old('availability_id') }}"]'
                );

            if (errorButton) {
                errorButton.click();
            }

        @endif

    }
);

</script>

@endsection