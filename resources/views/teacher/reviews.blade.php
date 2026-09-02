@extends('teacher.layout')

@section('title', __('teacher.reviews'))
@section('page-title', __('teacher.reviews'))

@section('content')


<style>

/* =========================================================
   REVIEWS PAGE
========================================================= */

.teacher-reviews-card {
    background: #EEE5FF;

    border: 1px solid #DDCCFF;

    border-radius: 22px;

    padding: 28px;

    box-shadow:
        0 10px 30px rgba(124, 58, 237, 0.08);
}


/* =========================================================
   HEADER
========================================================= */

.teacher-reviews-header {
    display: flex;

    align-items: center;
    justify-content: space-between;

    gap: 20px;

    margin-bottom: 28px;
}


.teacher-reviews-title h3 {
    margin: 0 0 5px 0;

    font-size: 26px;

    font-weight: 700;
}


.teacher-reviews-title p {
    margin: 0;

    color: #6B7280;

    font-size: 13px;
}


/* =========================================================
   SUMMARY
========================================================= */

.teacher-rating-summary {
    display: flex;

    align-items: center;

    gap: 14px;

    background: #FFFFFF;

    border: 1px solid #D8C8F5;

    border-radius: 14px;

    padding: 12px 16px;
}


.teacher-rating-number {
    font-size: 30px;

    line-height: 1;

    font-weight: 700;

    color: #1F2937;
}


.teacher-rating-summary-right {
    display: flex;

    flex-direction: column;

    gap: 3px;
}


.teacher-summary-stars {
    display: flex;

    gap: 2px;
}


.teacher-summary-star {
    font-size: 18px;

    color: #D1D5DB;

    line-height: 1;
}


.teacher-summary-star.active {
    color: #F5B301;
}


.teacher-review-count {
    color: #6B7280;

    font-size: 11px;
}


/* =========================================================
   REVIEWS LIST
========================================================= */

.teacher-reviews-list {
    display: flex;

    flex-direction: column;

    gap: 12px;
}


/* =========================================================
   REVIEW ITEM
========================================================= */

.teacher-review-item {
    display: grid;

    grid-template-columns:
        52px
        190px
        120px
        110px
        1fr;

    gap: 16px;

    align-items: center;

    padding: 16px;

    background: rgba(255, 255, 255, .65);

    border: 1px solid #DDD1F2;

    border-radius: 14px;
}


/* =========================================================
   STUDENT AVATAR
========================================================= */

.review-student-avatar {
    width: 44px;
    height: 44px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    overflow: hidden;

    background: #FFFFFF;

    border: 2px solid #DDD1F2;

    color: #6D28D9;

    font-size: 16px;

    font-weight: 700;
}


.review-student-avatar img {
    width: 100%;
    height: 100%;

    object-fit: cover;
}


/* =========================================================
   STUDENT INFO
========================================================= */

.review-student-name {
    font-size: 14px;

    font-weight: 700;

    color: #1F2937;

    margin-bottom: 2px;
}


.review-date {
    font-size: 10px;

    color: #8B7AA8;
}


/* =========================================================
   DANCE
========================================================= */

.review-label {
    display: block;

    margin-bottom: 3px;

    font-size: 9px;

    font-weight: 600;

    color: #8B7AA8;

    text-transform: uppercase;

    letter-spacing: .3px;
}


.review-dance {
    font-size: 13px;

    font-weight: 600;

    color: #1F2937;
}


/* =========================================================
   STARS
========================================================= */

.review-stars {
    display: flex;

    align-items: center;

    gap: 1px;
}


.review-star {
    color: #D1D5DB;

    font-size: 17px;

    line-height: 1;
}


.review-star.active {
    color: #F5B301;
}


.review-rating-number {
    margin-top: 4px;

    font-size: 10px;

    color: #6B7280;
}


/* =========================================================
   COMMENT
========================================================= */

.review-comment {
    color: #374151;

    font-size: 13px;

    line-height: 1.5;

    overflow-wrap: anywhere;
}


.review-no-comment {
    color: #9CA3AF;

    font-style: italic;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.teacher-reviews-empty {
    padding: 55px 20px;

    text-align: center;

    background: rgba(255, 255, 255, .45);

    border-radius: 14px;

    border: 1px dashed #D8C8F5;
}


.teacher-reviews-empty-stars {
    margin-bottom: 12px;

    color: #D1D5DB;

    font-size: 28px;

    letter-spacing: 3px;
}


.teacher-reviews-empty h5 {
    margin-bottom: 6px;

    font-weight: 700;
}


.teacher-reviews-empty p {
    margin: 0;

    color: #6B7280;

    font-size: 13px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1050px) {

    .teacher-review-item {
        grid-template-columns:
            45px
            1fr
            120px;
    }

    .review-comment {
        grid-column: 2 / -1;
    }
}


@media (max-width: 700px) {

    .teacher-reviews-header {
        flex-direction: column;

        align-items: flex-start;
    }


    .teacher-review-item {
        grid-template-columns:
            45px
            1fr;
    }


    .review-dance-column,
    .review-rating-column,
    .review-comment {
        grid-column: 2;
    }

}

</style>



<div class="teacher-reviews-card">


    {{-- =====================================================
       HEADER + SUMMARY
    ====================================================== --}}

    <div class="teacher-reviews-header">


        <div class="teacher-reviews-title">

            <h3>
                {{ __('teacher.my_reviews') }}
            </h3>

            <p>
                {{ __('teacher.reviews_subtitle') }}
            </p>

        </div>


        @if($reviewCount > 0)

            @php
                $roundedAverage = round($averageRating);
            @endphp


            <div class="teacher-rating-summary">


                <div class="teacher-rating-number">

                    {{ number_format($averageRating, 1) }}

                </div>


                <div class="teacher-rating-summary-right">


                    <div class="teacher-summary-stars">

                        @for($i = 1; $i <= 5; $i++)

                            <span
                                class="teacher-summary-star
                                {{ $i <= $roundedAverage ? 'active' : '' }}"
                            >
                                ★
                            </span>

                        @endfor

                    </div>


                    <div class="teacher-review-count">

                        {{ $reviewCount }}

                        @if($reviewCount === 1)
                            {{ __('teacher.review') }}
                        @else
                            {{ __('teacher.reviews_count') }}
                        @endif

                    </div>

                </div>

            </div>

        @endif

    </div>



    {{-- =====================================================
       REVIEWS
    ====================================================== --}}

    @if($reviews->count())


        <div class="teacher-reviews-list">


            @foreach($reviews as $review)


                <div class="teacher-review-item">


                    {{-- =========================================
                       STUDENT PHOTO
                    ========================================== --}}

                    <div class="review-student-avatar">


                        @if($review->student?->profile_photo)

                            <img
                                src="{{ asset(
                                    'storage/' .
                                    $review->student->profile_photo
                                ) }}"
                                alt="{{ $review->student->user->name ?? __('teacher.student') }}"
                            >

                        @else

                            {{ strtoupper(
                                substr(
                                    $review->student->user->name
                                        ?? 'S',
                                    0,
                                    1
                                )
                            ) }}

                        @endif

                    </div>



                    {{-- =========================================
                       STUDENT
                    ========================================== --}}

                    <div>

                        <div class="review-student-name">

                            {{ $review->student->user->name ?? __('teacher.student') }}

                        </div>


                        <div class="review-date">

                            @if($review->created_at)

                                {{ $review->created_at
                                    ->copy()
                                    ->locale(app()->getLocale())
                                    ->translatedFormat(
                                        app()->getLocale() === 'fr'
                                            ? 'd M Y'
                                            : 'M d, Y'
                                    )
                                }}

                                •

                                {{ $review->created_at->format('H:i') }}

                            @endif

                        </div>

                    </div>



                    {{-- =========================================
                       DANCE
                    ========================================== --}}

                    <div class="review-dance-column">

                        <span class="review-label">
                            {{ __('teacher.dance') }}
                        </span>

                        <div class="review-dance">

                            {{ $review->booking?->danceStyle?->name
                                ?? __('teacher.dance') }}

                        </div>

                    </div>



                    {{-- =========================================
                       RATING
                    ========================================== --}}

                    <div class="review-rating-column">

                        <span class="review-label">
                            {{ __('teacher.rating') }}
                        </span>


                        <div class="review-stars">

                            @for($i = 1; $i <= 5; $i++)

                                <span
                                    class="review-star
                                    {{ $i <= $review->rating
                                        ? 'active'
                                        : '' }}"
                                >
                                    ★
                                </span>

                            @endfor

                        </div>


                        <div class="review-rating-number">

                            {{ $review->rating }} / 5

                        </div>

                    </div>



                    {{-- =========================================
                       COMMENT
                    ========================================== --}}

                    <div>

                        <span class="review-label">
                            {{ __('teacher.comment') }}
                        </span>


                        @if($review->comment)

                            <div class="review-comment">

                                {{ $review->comment }}

                            </div>

                        @else

                            <div class="review-comment review-no-comment">

                                {{ __('teacher.no_written_comment') }}

                            </div>

                        @endif

                    </div>


                </div>


            @endforeach


        </div>


    @else


        <div class="teacher-reviews-empty">

            <div class="teacher-reviews-empty-stars">
                ★★★★★
            </div>

            <h5>
                {{ __('teacher.no_reviews_yet') }}
            </h5>

            <p>
                {{ __('teacher.reviews_empty_text') }}
            </p>

        </div>


    @endif


</div>

@endsection