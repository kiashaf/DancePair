@extends('student.layout')

@section('title', __('student.reviews'))
@section('page-title', __('student.reviews'))

@section('content')

<style>

.student-reviews-card {
    background: #EAF6FF;
    border: 1px solid #CDE9F8;
    border-radius: 22px;
    padding: 28px;
}

.student-reviews-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 28px;
}

.student-reviews-title h3 {
    margin: 0 0 5px;
    font-size: 26px;
    font-weight: 700;
}

.student-reviews-title p {
    margin: 0;
    color: #6B7280;
    font-size: 13px;
}

.student-rating-summary {
    display: flex;
    align-items: center;
    gap: 14px;

    background: #FFFFFF;
    border: 1px solid #CDE9F8;
    border-radius: 14px;

    padding: 12px 16px;
}

.student-rating-number {
    font-size: 30px;
    line-height: 1;
    font-weight: 700;
}

.student-summary-stars {
    display: flex;
    gap: 2px;
}

.student-summary-star {
    font-size: 18px;
    color: #D1D5DB;
}

.student-summary-star.active {
    color: #F5B301;
}

.student-review-count {
    margin-top: 3px;
    font-size: 11px;
    color: #6B7280;
}

.student-reviews-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.student-review-item {
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

    background: rgba(255,255,255,.75);
    border: 1px solid #CDE9F8;
    border-radius: 14px;
}

.review-teacher-avatar {
    width: 44px;
    height: 44px;

    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    overflow: hidden;

    background: #DFF2FF;
    border: 2px solid #CDE9F8;

    color: #0369A1;

    font-weight: 700;
}

.review-teacher-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.review-teacher-name {
    font-size: 14px;
    font-weight: 700;
}

.review-date {
    font-size: 10px;
    color: #7C96A8;
    margin-top: 2px;
}

.review-date {
    font-size: 10px;
    color: #7C96A8;
    margin-top: 2px;
}

.review-edited {
    display: block;
    margin-top: 2px;
    color: #9CA3AF;
    font-size: 9px;
    font-style: italic;
}

.review-label {
    display: block;
    margin-bottom: 3px;

    font-size: 9px;
    font-weight: 600;

    color: #7C96A8;

    text-transform: uppercase;
}

.review-dance {
    font-size: 13px;
    font-weight: 600;
}

.review-stars {
    display: flex;
    gap: 1px;
}

.review-star {
    color: #D1D5DB;
    font-size: 17px;
}

.review-star.active {
    color: #F5B301;
}

.review-rating-number {
    font-size: 10px;
    color: #6B7280;
    margin-top: 3px;
}

.review-comment {
    font-size: 13px;
    line-height: 1.5;
    color: #374151;
    overflow-wrap: anywhere;
}

.review-no-comment {
    color: #9CA3AF;
    font-style: italic;
}

.student-reviews-empty {
    padding: 50px 20px;

    text-align: center;

    background: rgba(255,255,255,.55);

    border: 1px dashed #CDE9F8;
    border-radius: 14px;
}

.student-reviews-empty-stars {
    font-size: 28px;
    color: #D1D5DB;
    letter-spacing: 3px;
    margin-bottom: 10px;
}

@media (max-width: 1000px) {

    .student-review-item {
        grid-template-columns:
            45px
            1fr
            120px;
    }

    .review-comment-column {
        grid-column: 2 / -1;
    }
}

@media (max-width: 700px) {

    .student-reviews-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .student-review-item {
        grid-template-columns:
            45px
            1fr;
    }

    .review-dance-column,
    .review-rating-column,
    .review-comment-column {
        grid-column: 2;
    }
}

</style>


<div class="student-reviews-card">

    <div class="student-reviews-header">

        <div class="student-reviews-title">

            <h3>
                {{ __('student.my_reviews') }}
            </h3>

            <p>
                {{ __('student.reviews_subtitle') }}
            </p>

        </div>


        @if($reviewCount > 0)

            @php
                $roundedAverage = round($averageRating);
            @endphp

            <div class="student-rating-summary">

                <div class="student-rating-number">
                    {{ number_format($averageRating, 1) }}
                </div>

                <div>

                    <div class="student-summary-stars">

                        @for($i = 1; $i <= 5; $i++)

                            <span
                                class="student-summary-star
                                {{ $i <= $roundedAverage ? 'active' : '' }}"
                            >
                                ★
                            </span>

                        @endfor

                    </div>

                    <div class="student-review-count">

                        {{ $reviewCount }}

                        {{ $reviewCount === 1
                            ? __('student.review')
                            : __('student.reviews_plural')
                        }}

                    </div>

                </div>

            </div>

        @endif

    </div>


    @if($reviews->count())

        <div class="student-reviews-list">

            @foreach($reviews as $review)

                <div class="student-review-item">


                    {{-- TEACHER PHOTO --}}
                    <div class="review-teacher-avatar">

                        @if($review->teacher?->profile_photo)

                            <img
                                src="{{ asset(
                                    'storage/' .
                                    $review->teacher->profile_photo
                                ) }}"
                                alt="{{ $review->teacher->user->name ?? __('student.teacher') }}"
                            >

                        @else

                            {{ strtoupper(
                                substr(
                                    $review->teacher->user->name
                                        ?? 'T',
                                    0,
                                    1
                                )
                            ) }}

                        @endif

                    </div>


                    {{-- TEACHER --}}
                    <div>

                        <div class="review-teacher-name">
                            {{ $review->teacher->user->name ?? __('student.teacher') }}
                        </div>


                        <div class="review-date">

                            @if($review->created_at)

                                {{ $review->created_at
                                    ->copy()
                                    ->locale(app()->getLocale())
                                    ->translatedFormat(
                                        app()->getLocale() === 'fr'
                                            ? 'd M Y • H:i'
                                            : 'M d, Y • H:i'
                                    )
                                }}

                            @endif


                            @if(
                                $review->updated_at &&
                                $review->created_at &&
                                !$review->updated_at->equalTo($review->created_at)
                            )

                                <span class="review-edited">

                                    {{ __('student.edited') }}:

                                    {{ $review->updated_at
                                        ->copy()
                                        ->locale(app()->getLocale())
                                        ->translatedFormat(
                                            app()->getLocale() === 'fr'
                                                ? 'd M Y • H:i'
                                                : 'M d, Y • H:i'
                                        )
                                    }}

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- DANCE --}}
                    <div class="review-dance-column">

                        <span class="review-label">
                            {{ __('student.dance') }}
                        </span>

                        <div class="review-dance">

                            {{ $review->booking?->danceStyle?->name
                                ?? __('student.dance')
                            }}

                        </div>

                    </div>


                    {{-- RATING --}}
                    <div class="review-rating-column">

                        <span class="review-label">
                            {{ __('student.rating') }}
                        </span>

                        <div class="review-stars">

                            @for($i = 1; $i <= 5; $i++)

                                <span
                                    class="review-star
                                    {{ $i <= $review->rating
                                        ? 'active'
                                        : ''
                                    }}"
                                >
                                    ★
                                </span>

                            @endfor

                        </div>

                        <div class="review-rating-number">
                            {{ $review->rating }} / 5
                        </div>

                    </div>


                    {{-- COMMENT --}}
                    <div class="review-comment-column">

                        <span class="review-label">
                            {{ __('student.comment') }}
                        </span>

                        @if($review->comment)

                            <div class="review-comment">
                                {{ $review->comment }}
                            </div>

                        @else

                            <div class="review-comment review-no-comment">
                                {{ __('student.no_written_comment') }}
                            </div>

                        @endif

                    </div>


                </div>

            @endforeach

        </div>

    @else

        <div class="student-reviews-empty">

            <div class="student-reviews-empty-stars">
                ★★★★★
            </div>

            <h5>
                {{ __('student.no_reviews') }}
            </h5>

            <p class="text-muted mb-0">
                {{ __('student.reviews_empty_text') }}
            </p>

        </div>

    @endif

</div>

@endsection