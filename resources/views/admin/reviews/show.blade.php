@extends('admin.layout')

@section('title', 'Review #' . $review->id)
@section('page-title', 'Review Details')

@section('content')

@php

    $student = $review->student;
    $teacher = $review->teacher;
    $booking = $review->booking;

    if ($review->reviewer_type === 'student') {

        $reviewer = $student;
        $recipient = $teacher;

        $reviewerRole = 'Student';
        $recipientRole = 'Teacher';

    } else {

        $reviewer = $teacher;
        $recipient = $student;

        $reviewerRole = 'Teacher';
        $recipientRole = 'Student';
    }

@endphp


<style>

/* =========================================================
   PAGE
========================================================= */

.review-detail-page {
    max-width: 1250px;
    margin: 0 auto;

    display: flex;
    flex-direction: column;

    gap: 18px;

    padding-bottom: 40px;
}


/* =========================================================
   HEADER
========================================================= */

.review-detail-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 18px;
}

.review-detail-header h2 {
    margin: 0;

    font-size: 23px;
    font-weight: 850;

    color: #0F172A;
}

.review-detail-header p {
    margin: 4px 0 0;

    font-size: 10px;

    color: #64748B;
}

.review-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 40px;

    padding: 0 14px;

    border: 1px solid #CBD5E1;
    border-radius: 10px;

    background: #FFFFFF;

    color: #334155;

    text-decoration: none;

    font-size: 10px;
    font-weight: 750;

    transition:
        background .15s ease,
        border-color .15s ease,
        color .15s ease;
}

.review-back-btn:hover {
    background: #F8FAFC;

    border-color: #94A3B8;

    color: #0F172A;
}


/* =========================================================
   SUMMARY
========================================================= */

.review-summary {
    display: grid;

    grid-template-columns:
        minmax(260px, 1.7fr)
        repeat(3, minmax(130px, .7fr));

    overflow: hidden;

    border: 1px solid #FDE68A;
    border-radius: 18px;

    background: #FFFFFF;

    box-shadow:
        0 8px 22px rgba(15, 23, 42, .035);
}

.review-summary-main {
    padding: 22px;

    background:
        linear-gradient(
            135deg,
            #FFFBEB,
            #FFFDF6
        );
}

.review-type-pill {
    display: inline-flex;
    align-items: center;

    padding: 5px 9px;

    border-radius: 999px;

    background: #FEF3C7;

    color: #92400E;

    font-size: 7.5px;
    font-weight: 850;

    text-transform: uppercase;
    letter-spacing: .5px;
}

.review-summary-main h3 {
    margin: 10px 0 5px;

    font-size: 21px;
    font-weight: 850;

    color: #0F172A;
}

.review-summary-main p {
    margin: 0;

    font-size: 9px;

    color: #64748B;
}

.review-summary-item {
    padding: 18px 16px;

    display: flex;
    flex-direction: column;
    justify-content: center;

    border-left: 1px solid #F1E8C8;
}

.review-summary-item span {
    margin-bottom: 5px;

    font-size: 7px;
    font-weight: 850;

    text-transform: uppercase;
    letter-spacing: .45px;

    color: #94A3B8;
}

.review-summary-item strong {
    font-size: 11px;
    font-weight: 800;

    color: #0F172A;

    overflow-wrap: anywhere;
}

.review-summary-rating {
    color: #D97706 !important;

    font-size: 17px !important;
}

.review-summary-approved {
    color: #047857 !important;
}

.review-summary-pending {
    color: #C2410C !important;
}


/* =========================================================
   STARS
========================================================= */

.review-stars {
    display: flex;

    gap: 2px;

    margin-top: 10px;

    font-size: 22px;

    color: #F59E0B;
}


/* =========================================================
   GRID
========================================================= */

.review-detail-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0,1fr));

    gap: 16px;
}


/* =========================================================
   CARD
========================================================= */

.review-card {
    padding: 20px;

    border: 1px solid #E2E8F0;
    border-radius: 15px;

    background: #FFFFFF;

    box-shadow:
        0 5px 16px rgba(15,23,42,.03);
}

.review-card.full {
    grid-column: 1 / -1;
}

.review-card.reviewer {
    border-top: 3px solid #38BDF8;
}

.review-card.recipient {
    border-top: 3px solid #A78BFA;
}

.review-card.comment {
    border-top: 3px solid #F59E0B;
}

.review-card.booking {
    border-top: 3px solid #6366F1;
}

.review-card-header {
    margin-bottom: 16px;
    padding-bottom: 11px;

    border-bottom: 1px solid #EEF2F7;
}

.review-card-header h4 {
    margin: 0;

    font-size: 14px;
    font-weight: 850;

    color: #0F172A;
}

.review-card-header p {
    margin: 3px 0 0;

    font-size: 8px;

    color: #94A3B8;
}


/* =========================================================
   PERSON
========================================================= */

.review-person {
    display: flex;
    align-items: center;

    gap: 11px;

    margin-bottom: 16px;
}

.review-avatar {
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

.reviewer .review-avatar {
    background: #E0F2FE;
    color: #0369A1;
}

.recipient .review-avatar {
    background: #F3E8FF;
    color: #7E22CE;
}

.review-person strong {
    display: block;

    font-size: 11px;
    font-weight: 850;

    color: #0F172A;
}

.review-person small {
    display: block;

    margin-top: 2px;

    font-size: 8.5px;

    color: #64748B;
}


/* =========================================================
   INFO GRID
========================================================= */

.review-info-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0,1fr));

    gap: 15px 20px;
}

.review-info-item span {
    display: block;

    margin-bottom: 4px;

    font-size: 7px;
    font-weight: 850;

    text-transform: uppercase;
    letter-spacing: .45px;

    color: #94A3B8;
}

.review-info-item strong {
    display: block;

    font-size: 10.5px;
    font-weight: 750;

    color: #1E293B;

    overflow-wrap: anywhere;
}


/* =========================================================
   COMMENT
========================================================= */

.review-comment-box {
    padding: 17px;

    border-left: 4px solid #F59E0B;
    border-radius: 0 12px 12px 0;

    background: #FFFCF5;

    color: #334155;

    font-size: 11px;
    line-height: 1.75;

    white-space: pre-wrap;
}


/* =========================================================
   BOOKING
========================================================= */

.review-booking-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0,1fr));

    gap: 10px;
}

.review-booking-item {
    padding: 13px;

    border: 1px solid #E2E8F0;
    border-radius: 10px;

    background: #F8FAFC;
}

.review-booking-item span {
    display: block;

    margin-bottom: 5px;

    font-size: 7px;
    font-weight: 850;

    text-transform: uppercase;
    letter-spacing: .45px;

    color: #94A3B8;
}

.review-booking-item strong {
    display: block;

    font-size: 10px;
    font-weight: 750;

    color: #1E293B;

    overflow-wrap: anywhere;
}

.review-booking-link {
    margin-top: 15px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 38px;

    padding: 0 14px;

    border-radius: 10px;

    background: #4F46E5;

    color: #FFFFFF;

    text-decoration: none;

    font-size: 9.5px;
    font-weight: 800;

    transition: background .15s ease;
}

.review-booking-link:hover {
    background: #4338CA;

    color: #FFFFFF;
}


/* =========================================================
   APPROVAL
========================================================= */

.review-approval-badge {
    display: inline-flex;
    align-items: center;

    padding: 5px 9px;

    border-radius: 999px;

    font-size: 8px;
    font-weight: 850;
}

.review-approval-badge.approved {
    background: #DCFCE7;
    color: #047857;
}

.review-approval-badge.pending {
    background: #FFF7ED;
    color: #C2410C;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1000px) {

    .review-summary {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }

    .review-summary-main {
        grid-column: 1 / -1;
    }

    .review-summary-item {
        border-top: 1px solid #F1E8C8;
    }

    .review-booking-grid {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }
}

@media(max-width: 750px) {

    .review-detail-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .review-detail-grid {
        grid-template-columns: 1fr;
    }

    .review-card.full {
        grid-column: auto;
    }

    .review-info-grid,
    .review-booking-grid {
        grid-template-columns: 1fr;
    }

    .review-summary {
        grid-template-columns: 1fr;
    }

    .review-summary-main {
        grid-column: auto;
    }

    .review-summary-item {
        border-left: 0;
    }
}

</style>


<div class="review-detail-page">


{{-- =========================================================
   HEADER
========================================================= --}}

<div class="review-detail-header">

    <div>

        <h2>
            Review #{{ $review->id }}
        </h2>

        <p>
            Feedback, people and related lesson information.
        </p>

    </div>


    <a
        href="{{ route('admin.reviews') }}"
        class="review-back-btn"
    >
        ← Back to Reviews
    </a>

</div>



{{-- =========================================================
   SUMMARY
========================================================= --}}

<div class="review-summary">

    <div class="review-summary-main">

        <span class="review-type-pill">
            {{ $reviewerRole }} Review
        </span>


        <h3>
            {{ $reviewer?->user?->name ?? 'Unknown' }}

            →

            {{ $recipient?->user?->name ?? 'Unknown' }}
        </h3>


        <p>
            Submitted
            {{ optional($review->created_at)->format('M d, Y · g:i A') }}
        </p>


        <div class="review-stars">

            @for($i = 1; $i <= 5; $i++)

                <span>
                    {{ $i <= $review->rating ? '★' : '☆' }}
                </span>

            @endfor

        </div>

    </div>


    <div class="review-summary-item">

        <span>
            Rating
        </span>

        <strong class="review-summary-rating">
            {{ $review->rating }} / 5
        </strong>

    </div>


    <div class="review-summary-item">

        <span>
            Written By
        </span>

        <strong>
            {{ $reviewerRole }}
        </strong>

    </div>


    <div class="review-summary-item">

        <span>
            Approval
        </span>

        <strong
            class="{{ $review->approved
                ? 'review-summary-approved'
                : 'review-summary-pending'
            }}"
        >
            {{ $review->approved
                ? 'Approved'
                : 'Pending'
            }}
        </strong>

    </div>

</div>



<div class="review-detail-grid">


{{-- =========================================================
   REVIEWER
========================================================= --}}

<div class="review-card reviewer">

    <div class="review-card-header">

        <h4>
            Written By
        </h4>

        <p>
            {{ $reviewerRole }} who submitted this review
        </p>

    </div>


    <div class="review-person">

        <div class="review-avatar">

            {{ strtoupper(
                substr(
                    $reviewer?->user?->name ?? '?',
                    0,
                    1
                )
            ) }}

        </div>


        <div>

            <strong>
                {{ $reviewer?->user?->name ?? 'Unknown' }}
            </strong>

            <small>
                {{ $reviewer?->user?->email ?? '—' }}
            </small>

        </div>

    </div>


    <div class="review-info-grid">

        <div class="review-info-item">

            <span>
                Role
            </span>

            <strong>
                {{ $reviewerRole }}
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                ID
            </span>

            <strong>
                #{{ $reviewer?->id ?? '—' }}
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                City
            </span>

            <strong>
                {{ $reviewer?->city ?? '—' }}
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                Province
            </span>

            <strong>
                {{ $reviewer?->province ?? '—' }}
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                Country
            </span>

            <strong>
                {{ $reviewer?->country ?? '—' }}
            </strong>

        </div>

    </div>

</div>



{{-- =========================================================
   RECIPIENT
========================================================= --}}

<div class="review-card recipient">

    <div class="review-card-header">

        <h4>
            Review For
        </h4>

        <p>
            {{ $recipientRole }} receiving this review
        </p>

    </div>


    <div class="review-person">

        <div class="review-avatar">

            {{ strtoupper(
                substr(
                    $recipient?->user?->name ?? '?',
                    0,
                    1
                )
            ) }}

        </div>


        <div>

            <strong>
                {{ $recipient?->user?->name ?? 'Unknown' }}
            </strong>

            <small>
                {{ $recipient?->user?->email ?? '—' }}
            </small>

        </div>

    </div>


    <div class="review-info-grid">

        <div class="review-info-item">

            <span>
                Role
            </span>

            <strong>
                {{ $recipientRole }}
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                ID
            </span>

            <strong>
                #{{ $recipient?->id ?? '—' }}
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                City
            </span>

            <strong>
                {{ $recipient?->city ?? '—' }}
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                Province
            </span>

            <strong>
                {{ $recipient?->province ?? '—' }}
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                Country
            </span>

            <strong>
                {{ $recipient?->country ?? '—' }}
            </strong>

        </div>

    </div>

</div>



{{-- =========================================================
   COMMENT
========================================================= --}}

<div class="review-card comment full">

    <div class="review-card-header">

        <h4>
            Review Comment
        </h4>

        <p>
            Written feedback submitted with the rating
        </p>

    </div>


    <div class="review-comment-box">

        {{ $review->comment
            ?: 'No written comment was provided for this review.'
        }}

    </div>

</div>



{{-- =========================================================
   REVIEW INFORMATION
========================================================= --}}

<div class="review-card">

    <div class="review-card-header">

        <h4>
            Review Information
        </h4>

        <p>
            Review record and moderation status
        </p>

    </div>


    <div class="review-info-grid">

        <div class="review-info-item">

            <span>
                Review ID
            </span>

            <strong>
                #{{ $review->id }}
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                Reviewer Type
            </span>

            <strong>
                {{ ucfirst($review->reviewer_type) }}
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                Rating
            </span>

            <strong>
                {{ $review->rating }} / 5
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                Approval
            </span>

            @if($review->approved)

                <span class="review-approval-badge approved">
                    ● Approved
                </span>

            @else

                <span class="review-approval-badge pending">
                    ● Pending Approval
                </span>

            @endif

        </div>


        <div class="review-info-item">

            <span>
                Submitted
            </span>

            <strong>
                {{ optional(
                    $review->created_at
                )->format(
                    'M d, Y · g:i A'
                ) }}
            </strong>

        </div>


        <div class="review-info-item">

            <span>
                Last Updated
            </span>

            <strong>
                {{ optional(
                    $review->updated_at
                )->format(
                    'M d, Y · g:i A'
                ) }}
            </strong>

        </div>

    </div>

</div>



{{-- =========================================================
   RELATED BOOKING
========================================================= --}}

<div class="review-card booking">

    <div class="review-card-header">

        <h4>
            Related Lesson
        </h4>

        <p>
            Booking connected to this review
        </p>

    </div>


    @if($booking)


        <div class="review-booking-grid">


            <div class="review-booking-item">

                <span>
                    Booking
                </span>

                <strong>
                    #{{ $booking->id }}
                </strong>

            </div>


            <div class="review-booking-item">

                <span>
                    Dance
                </span>

                <strong>
                    {{ $booking->danceStyle?->name ?? '—' }}
                </strong>

            </div>


            <div class="review-booking-item">

                <span>
                    Lesson Date
                </span>

                <strong>

                    {{ $booking->lesson_date
                        ? \Carbon\Carbon::parse(
                            $booking->lesson_date
                        )->format('M d, Y')
                        : '—'
                    }}

                </strong>

            </div>


            <div class="review-booking-item">

                <span>
                    Price
                </span>

                <strong>

                    ${{ number_format(
                        (float) ($booking->price ?? 0),
                        2
                    ) }}

                </strong>

            </div>


            <div class="review-booking-item">

                <span>
                    Booking Status
                </span>

                <strong>
                    {{ ucfirst($booking->status ?? '—') }}
                </strong>

            </div>


            <div class="review-booking-item">

                <span>
                    Payment
                </span>

                <strong
                    class="{{ $booking->paid
                        ? 'review-summary-approved'
                        : 'review-summary-pending'
                    }}"
                >
                    {{ $booking->paid
                        ? 'Paid'
                        : 'Unpaid'
                    }}
                </strong>

            </div>


        </div>


        <a
            href="{{ route(
                'admin.bookings.show',
                $booking
            ) }}"
            class="review-booking-link"
        >
            View Full Booking →
        </a>


    @else


        <div class="review-comment-box">
            No booking is connected to this review.
        </div>


    @endif

</div>


</div>

</div>

@endsection