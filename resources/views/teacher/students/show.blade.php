@extends('teacher.layout')

@section('title', __('teacher.student_profile'))
@section('page-title', __('teacher.student_profile'))

@section('content')

<div class="card profile-card p-4">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div class="d-flex align-items-center gap-4 mb-4">

            @if($student->profile_photo)

                <img
                    src="{{ asset('storage/' . $student->profile_photo) }}"
                    alt="{{ $student->user->name ?? __('teacher.student') }}"
                    style="
                        width:110px;
                        height:110px;
                        object-fit:cover;
                        border-radius:50%;
                        border:4px solid white;
                        box-shadow:0 6px 18px rgba(0,0,0,.08);
                    "
                >

            @else

                <div
                    style="
                        width:110px;
                        height:110px;
                        border-radius:50%;
                        background:#EEE5FF;
                        color:#6D28D9;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:34px;
                        font-weight:700;
                    "
                >
                    {{ strtoupper(
                        substr(
                            $student->user->name ?? 'S',
                            0,
                            1
                        )
                    ) }}
                </div>

            @endif


            <div>

                <h2 class="mb-1">
                    {{ $student->user->name ?? __('teacher.student') }}
                </h2>

                <p class="text-muted mb-0">

                    {{ $student->city ?? __('teacher.location_not_set') }}

                    @if($student->province)
                        , {{ $student->province }}
                    @endif

                    @if($student->country)
                        , {{ $student->country }}
                    @endif

                </p>

            </div>

        </div>


        <div>

            <p class="text-muted mb-0">
                {{ $student->user->email ?? '' }}
            </p>

        </div>


        <a
            href="{{ route('teacher.bookings') }}"
            class="btn btn-outline-secondary"
        >
            {{ __('teacher.back') }}
        </a>

    </div>


    <hr>


    <div class="row g-4">

        <div class="col-md-6">

            <label class="text-muted">
                {{ __('teacher.location') }}
            </label>

            <div class="fw-semibold mt-1">

                {{ $student->city ?? __('teacher.not_provided') }}

                @if($student->province)
                    , {{ $student->province }}
                @endif

                @if($student->country)
                    , {{ $student->country }}
                @endif

            </div>

        </div>


        <div class="col-md-6">

            <label class="text-muted">
                {{ __('teacher.experience_level') }}
            </label>

            <div class="fw-semibold mt-1">

                @if($student->experience_level)

                    @if($student->experience_level === 'beginner')
                        {{ __('teacher.beginner') }}

                    @elseif($student->experience_level === 'intermediate')
                        {{ __('teacher.intermediate') }}

                    @elseif($student->experience_level === 'advanced')
                        {{ __('teacher.advanced') }}

                    @else
                        {{ ucfirst($student->experience_level) }}
                    @endif

                @else

                    {{ __('teacher.not_provided') }}

                @endif

            </div>

        </div>


        <div class="col-md-6">

            <label class="text-muted">
                {{ __('teacher.previous_dance_classes') }}
            </label>

            <div class="fw-semibold mt-1">

                @if($student->has_dance_experience)
                    {{ __('teacher.yes') }}
                @else
                    {{ __('teacher.no') }}
                @endif

            </div>

        </div>


        <div class="col-md-6">

            <label class="text-muted">
                {{ __('teacher.requested_dance_style') }}
            </label>

            <div class="fw-semibold mt-1">
                {{ $booking->danceStyle->name ?? __('teacher.dance') }}
            </div>

        </div>


        <div class="col-12">

            <label class="text-muted">
                {{ __('teacher.about_student') }}
            </label>

            <div class="mt-2">
                {{ $student->bio ?: __('teacher.no_bio_provided') }}
            </div>

        </div>

    </div>


    @if($booking->status === 'pending')

        <hr class="my-4">


        <div class="d-flex justify-content-end gap-2">

            <form
                method="POST"
                action="{{ route(
                    'teacher.bookings.reject',
                    $booking
                ) }}"
            >
                @csrf

                <button
                    class="btn btn-outline-danger"
                    type="submit"
                >
                    {{ __('teacher.refuse_request') }}
                </button>

            </form>


            <form
                method="POST"
                action="{{ route(
                    'teacher.bookings.accept',
                    $booking
                ) }}"
            >
                @csrf

                <button
                    class="btn btn-success"
                    type="submit"
                >
                    {{ __('teacher.accept_request') }}
                </button>

            </form>

        </div>

    @endif

</div>

@endsection