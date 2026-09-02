@extends('admin.layout')

@section('title', 'Reviews')
@section('page-title', 'Reviews')

@section('content')

<style>

/* =========================================================
   PAGE
========================================================= */

.reviews-page {
    display: flex;
    flex-direction: column;
    gap: 22px;

    padding-bottom: 40px;
}


/* =========================================================
   OVERVIEW
========================================================= */

.review-overview {
    padding: 22px;

    background: #FFFFFF;

    border: 1px solid #E2E8F0;
    border-top: 4px solid #F59E0B;
    border-radius: 20px;

    box-shadow:
        0 8px 24px rgba(15, 23, 42, .035);
}

.review-overview-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 16px;

    margin-bottom: 18px;
}

.review-overview-header h3 {
    margin: 0;

    font-size: 22px;
    font-weight: 850;

    color: #0F172A;
}

.review-overview-header p {
    margin: 5px 0 0;

    font-size: 10px;

    color: #64748B;
}

.review-total-pill {
    display: inline-flex;
    align-items: center;

    padding: 7px 12px;

    border: 1px solid #FDE68A;
    border-radius: 999px;

    background: #FFFBEB;

    color: #92400E;

    font-size: 9px;
    font-weight: 800;

    white-space: nowrap;
}


/* =========================================================
   KPI
========================================================= */

.review-stats-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 12px;
}

.review-stat-card {
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

.review-stat-label {
    font-size: 8.5px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .45px;

    color: #64748B;
}

.review-stat-value {
    margin-top: 10px;

    font-size: clamp(20px, 1.5vw, 26px);
    font-weight: 850;

    line-height: 1;

    color: #0F172A;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.review-stat-value.rating {
    color: #D97706;
}

.review-stat-description {
    margin-top: 8px;

    font-size: 8.5px;
    font-weight: 650;

    color: #94A3B8;
}


/* =========================================================
   STATUS STRIP
========================================================= */

.review-status-strip {
    margin-top: 14px;

    display: flex;
    flex-wrap: wrap;

    gap: 8px;
}

.review-status-chip {
    display: inline-flex;
    align-items: center;

    padding: 6px 10px;

    border-radius: 999px;

    font-size: 8px;
    font-weight: 800;
}

.review-status-chip.approved {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    color: #047857;
}

.review-status-chip.pending {
    background: #FFF7ED;
    border: 1px solid #FED7AA;
    color: #C2410C;
}


/* =========================================================
   SEARCH CARD
========================================================= */

.review-search-card {
    padding: 20px;

    background: #FFFFFF;

    border: 1px solid #E2E8F0;
    border-radius: 18px;

    box-shadow:
        0 6px 18px rgba(15, 23, 42, .03);
}

.review-search-header {
    margin-bottom: 16px;
}

.review-search-header h4 {
    margin: 0;

    font-size: 19px;
    font-weight: 850;

    color: #0F172A;
}

.review-search-header p {
    margin: 4px 0 0;

    font-size: 10px;

    color: #64748B;
}

.review-filter-grid {
    display: grid;

    grid-template-columns:
        2fr
        1fr
        1fr
        1fr
        auto;

    gap: 12px;

    align-items: end;
}

.review-filter-group {
    min-width: 0;
}

.review-filter-group label {
    display: block;

    margin-bottom: 5px;

    font-size: 9px;
    font-weight: 750;

    color: #475569;
}

.review-filter-control {
    width: 100%;
    min-height: 42px;

    padding: 0 12px;

    border: 1px solid #CBD5E1;
    border-radius: 10px;

    background: #FBFDFC;

    color: #0F172A;

    font-size: 10.5px;

    outline: none;

    transition:
        border-color .15s ease,
        box-shadow .15s ease,
        background .15s ease;
}

.review-filter-control:focus {
    border-color: #F59E0B;

    background: #FFFFFF;

    box-shadow:
        0 0 0 3px rgba(245, 158, 11, .09);
}

.review-filter-actions {
    display: flex;
    align-items: center;

    gap: 8px;
}

.review-filter-btn {
    min-height: 42px;

    padding: 0 16px;

    border: 0;
    border-radius: 10px;

    background: #D97706;

    color: #FFFFFF;

    font-size: 9.5px;
    font-weight: 800;

    cursor: pointer;

    transition: background .15s ease;
}

.review-filter-btn:hover {
    background: #B45309;
}

.review-reset-btn {
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

.review-reset-btn:hover {
    background: #F8FAFC;

    color: #0F172A;
}


/* =========================================================
   RESULTS
========================================================= */

.review-results-card {
    padding: 20px;

    background: #FFFFFF;

    border: 1px solid #E2E8F0;
    border-radius: 18px;

    box-shadow:
        0 6px 18px rgba(15, 23, 42, .03);
}

.review-results-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 16px;

    margin-bottom: 15px;
}

.review-results-header h4 {
    margin: 0;

    font-size: 19px;
    font-weight: 850;

    color: #0F172A;
}

.review-results-header p {
    margin: 4px 0 0;

    font-size: 10px;

    color: #64748B;
}

.review-result-count {
    padding: 6px 10px;

    border: 1px solid #E2E8F0;
    border-radius: 999px;

    background: #F8FAFC;

    color: #475569;

    font-size: 8.5px;
    font-weight: 800;

    white-space: nowrap;
}


/* =========================================================
   REVIEW LIST
========================================================= */

.review-list {
    display: flex;
    flex-direction: column;

    gap: 10px;
}

.review-item {
    position: relative;

    display: grid;

    grid-template-columns:
        minmax(150px, 1fr)
        minmax(150px, 1fr)
        105px
        minmax(220px, 1.7fr)
        115px
        40px;

    align-items: center;

    gap: 16px;

    padding: 15px 16px;

    border: 1px solid #E5EAF0;
    border-radius: 14px;

    background: #FCFDFE;

    cursor: pointer;

    transition:
        background .15s ease,
        border-color .15s ease,
        box-shadow .15s ease,
        transform .15s ease;
}

.review-item:hover {
    transform: translateY(-1px);

    border-color: #FCD34D;

    background: #FFFEFA;

    box-shadow:
        0 8px 18px rgba(15, 23, 42, .05);
}

.review-item:hover::before {
    content: "";

    position: absolute;

    left: 0;
    top: 10px;
    bottom: 10px;

    width: 3px;

    border-radius: 999px;

    background: #F59E0B;
}


/* =========================================================
   REVIEW CELL
========================================================= */

.review-mini-label {
    display: block;

    margin-bottom: 4px;

    font-size: 7px;
    font-weight: 850;

    text-transform: uppercase;
    letter-spacing: .5px;

    color: #94A3B8;
}

.review-person {
    font-size: 10.5px;
    font-weight: 800;

    color: #0F172A;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.review-email {
    margin-top: 2px;

    font-size: 8px;

    color: #94A3B8;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}


/* =========================================================
   STARS
========================================================= */

.review-stars {
    display: flex;

    gap: 1px;

    color: #F59E0B;

    font-size: 13px;

    white-space: nowrap;
}

.review-rating-number {
    margin-top: 3px;

    font-size: 8px;
    font-weight: 750;

    color: #64748B;
}


/* =========================================================
   COMMENT
========================================================= */

.review-comment {
    color: #475569;

    font-size: 9.5px;
    line-height: 1.5;

    display: -webkit-box;

    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;

    overflow: hidden;
}


/* =========================================================
   APPROVAL
========================================================= */

.review-approval {
    display: inline-flex;
    align-items: center;

    gap: 5px;

    padding: 5px 8px;

    border-radius: 999px;

    font-size: 7.5px;
    font-weight: 800;

    white-space: nowrap;
}

.review-approval.approved {
    background: #DCFCE7;
    color: #047857;
}

.review-approval.pending {
    background: #FFF7ED;
    color: #C2410C;
}


/* =========================================================
   ARROW
========================================================= */

.review-arrow {
    width: 30px;
    height: 30px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-left: auto;

    border-radius: 9px;

    background: #FFFBEB;

    border: 1px solid #FDE68A;

    color: #D97706;

    font-size: 13px;
    font-weight: 850;

    transition:
        background .15s ease,
        color .15s ease,
        transform .15s ease;
}

.review-item:hover .review-arrow {
    background: #D97706;

    color: #FFFFFF;

    transform: translateX(2px);
}


/* =========================================================
   EMPTY
========================================================= */

.review-empty {
    padding: 42px 20px;

    text-align: center;

    color: #94A3B8;

    font-size: 10.5px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 1200px) {

    .review-stats-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .review-filter-grid {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }

    .review-filter-actions {
        grid-column: 1 / -1;
    }

    .review-item {
        grid-template-columns:
            repeat(2, minmax(0,1fr));
    }

    .review-arrow {
        display: none;
    }
}


@media(max-width: 750px) {

    .review-overview-header,
    .review-results-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .review-stats-grid,
    .review-filter-grid,
    .review-item {
        grid-template-columns: 1fr;
    }

    .review-filter-actions {
        grid-column: auto;
    }
}

</style>


<div class="reviews-page">


{{-- =========================================================
   OVERVIEW
========================================================= --}}

<div class="review-overview">

    <div class="review-overview-header">

        <div>

            <h3>
                Review Overview
            </h3>

            <p>
                Ratings and feedback across DancePair.
            </p>

        </div>


        <div class="review-total-pill">
            {{ number_format($totalReviews) }}
            reviews
        </div>

    </div>


    <div class="review-stats-grid">


        {{-- TOTAL --}}
        <div class="review-stat-card">

            <div class="review-stat-label">
                Total Reviews
            </div>

            <div
                class="review-stat-value"
                title="{{ number_format($totalReviews) }}"
            >
                {{ number_format($totalReviews) }}
            </div>

            <div class="review-stat-description">
                All submitted reviews
            </div>

        </div>


        {{-- AVERAGE --}}
        <div class="review-stat-card">

            <div class="review-stat-label">
                Average Rating
            </div>

            <div class="review-stat-value rating">
                {{ number_format($averageRating, 1) }}
                ★
            </div>

            <div class="review-stat-description">
                Overall platform rating
            </div>

        </div>


        {{-- STUDENT REVIEWS --}}
        <div class="review-stat-card">

            <div class="review-stat-label">
                Written By Students
            </div>

            <div
                class="review-stat-value"
                title="{{ number_format($studentReviews) }}"
            >
                {{ number_format($studentReviews) }}
            </div>

            <div class="review-stat-description">
                Student feedback
            </div>

        </div>


        {{-- TEACHER REVIEWS --}}
        <div class="review-stat-card">

            <div class="review-stat-label">
                Written By Teachers
            </div>

            <div
                class="review-stat-value"
                title="{{ number_format($teacherReviews) }}"
            >
                {{ number_format($teacherReviews) }}
            </div>

            <div class="review-stat-description">
                Teacher feedback
            </div>

        </div>

    </div>


    <div class="review-status-strip">

        <span class="review-status-chip approved">
            Approved
            {{ number_format($approvedReviews) }}
        </span>

        <span class="review-status-chip pending">
            Pending Approval
            {{ number_format($pendingReviews) }}
        </span>

    </div>

</div>



{{-- =========================================================
   SEARCH / FILTERS
========================================================= --}}

<div class="review-search-card">

    <div class="review-search-header">

        <h4>
            Find Reviews
        </h4>

        <p>
            Search the review history without loading every review.
        </p>

    </div>


    <form
        method="GET"
        action="{{ route('admin.reviews') }}"
    >

        <div class="review-filter-grid">


            {{-- SEARCH --}}
            <div class="review-filter-group">

                <label>
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    class="review-filter-control"
                    value="{{ request('search') }}"
                    placeholder="Name, email or comment..."
                >

            </div>



            {{-- RATING --}}
            <div class="review-filter-group">

                <label>
                    Rating
                </label>

                <select
                    name="rating"
                    class="review-filter-control"
                >

                    <option value="">
                        All Ratings
                    </option>


                    @for($rating = 5; $rating >= 1; $rating--)

                        <option
                            value="{{ $rating }}"
                            @selected(
                                (string) request('rating')
                                ===
                                (string) $rating
                            )
                        >
                            {{ $rating }}
                            Star{{ $rating > 1 ? 's' : '' }}
                        </option>

                    @endfor

                </select>

            </div>



            {{-- WRITTEN BY --}}
            <div class="review-filter-group">

                <label>
                    Written By
                </label>

                <select
                    name="reviewer_type"
                    class="review-filter-control"
                >

                    <option value="">
                        Everyone
                    </option>


                    <option
                        value="student"
                        @selected(
                            request('reviewer_type')
                            ===
                            'student'
                        )
                    >
                        Student
                    </option>


                    <option
                        value="teacher"
                        @selected(
                            request('reviewer_type')
                            ===
                            'teacher'
                        )
                    >
                        Teacher
                    </option>

                </select>

            </div>



            {{-- APPROVAL --}}
            <div class="review-filter-group">

                <label>
                    Approval
                </label>

                <select
                    name="approved"
                    class="review-filter-control"
                >

                    <option value="">
                        All
                    </option>


                    <option
                        value="yes"
                        @selected(
                            request('approved')
                            ===
                            'yes'
                        )
                    >
                        Approved
                    </option>


                    <option
                        value="no"
                        @selected(
                            request('approved')
                            ===
                            'no'
                        )
                    >
                        Pending Approval
                    </option>

                </select>

            </div>



            {{-- ACTIONS --}}
            <div class="review-filter-actions">

                <button
                    type="submit"
                    class="review-filter-btn"
                >
                    Apply Filters
                </button>


                <a
                    href="{{ route('admin.reviews') }}"
                    class="review-reset-btn"
                >
                    Reset
                </a>

            </div>

        </div>

    </form>

</div>



{{-- =========================================================
   RESULTS
   ONLY AFTER SEARCH / FILTER
========================================================= --}}

@if($hasFilters)

    <div class="review-results-card">

        <div class="review-results-header">

            <div>

                <h4>
                    Search Results
                </h4>

                <p>
                    Reviews matching your selected filters.
                </p>

            </div>


            <div class="review-result-count">

                {{ number_format(
                    $reviews->total()
                ) }}

                results

            </div>

        </div>


        @if($reviews->count())


            <div class="review-list">


                @foreach($reviews as $review)

                    @php

                        $studentName =
                            $review
                                ->student
                                ?->user
                                ?->name
                            ?? 'Unknown Student';


                        $teacherName =
                            $review
                                ->teacher
                                ?->user
                                ?->name
                            ?? 'Unknown Teacher';


                        if (
                            $review->reviewer_type
                            ===
                            'student'
                        ) {

                            $reviewerName =
                                $studentName;

                            $reviewerEmail =
                                $review
                                    ->student
                                    ?->user
                                    ?->email
                                ?? '';

                            $recipientName =
                                $teacherName;

                            $recipientEmail =
                                $review
                                    ->teacher
                                    ?->user
                                    ?->email
                                ?? '';

                        } else {

                            $reviewerName =
                                $teacherName;

                            $reviewerEmail =
                                $review
                                    ->teacher
                                    ?->user
                                    ?->email
                                ?? '';

                            $recipientName =
                                $studentName;

                            $recipientEmail =
                                $review
                                    ->student
                                    ?->user
                                    ?->email
                                ?? '';
                        }

                    @endphp


                    <div
                        class="review-item"
                        onclick="window.location.href='{{ route('admin.reviews.show', $review) }}'"
                        title="View review details"
                    >


                        {{-- WRITTEN BY --}}
                        <div>

                            <span class="review-mini-label">
                                Written By
                            </span>

                            <div class="review-person">
                                {{ $reviewerName }}
                            </div>

                            <div class="review-email">
                                {{ $reviewerEmail }}
                            </div>

                        </div>



                        {{-- REVIEW FOR --}}
                        <div>

                            <span class="review-mini-label">
                                Review For
                            </span>

                            <div class="review-person">
                                {{ $recipientName }}
                            </div>

                            <div class="review-email">
                                {{ $recipientEmail }}
                            </div>

                        </div>



                        {{-- RATING --}}
                        <div>

                            <span class="review-mini-label">
                                Rating
                            </span>


                            <div class="review-stars">

                                @for($i = 1; $i <= 5; $i++)

                                    <span>
                                        {{ $i <= $review->rating ? '★' : '☆' }}
                                    </span>

                                @endfor

                            </div>


                            <div class="review-rating-number">
                                {{ $review->rating }} / 5
                            </div>

                        </div>



                        {{-- COMMENT --}}
                        <div>

                            <span class="review-mini-label">
                                Comment
                            </span>

                            <div class="review-comment">

                                {{ $review->comment
                                    ?: 'No comment provided.'
                                }}

                            </div>

                        </div>



                        {{-- APPROVAL --}}
                        <div>

                            <span class="review-mini-label">
                                Status
                            </span>


                            @if($review->approved)

                                <span class="review-approval approved">
                                    ● Approved
                                </span>

                            @else

                                <span class="review-approval pending">
                                    ● Pending
                                </span>

                            @endif

                        </div>



                        {{-- OPEN --}}
                        <div>

                            <span class="review-arrow">
                                →
                            </span>

                        </div>


                    </div>

                @endforeach


            </div>



            @if($reviews->hasPages())

                <div class="mt-4">

                    {{ $reviews->links() }}

                </div>

            @endif


        @else


            <div class="review-empty">
                No reviews match your search or filters.
            </div>


        @endif

    </div>

@endif


</div>

@endsection