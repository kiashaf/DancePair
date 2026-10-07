@extends('teacher.layout')

@section('title', __('teacher.availability'))
@section('page-title', __('teacher.availability'))

@section('content')


{{-- =========================================================
    ADD AVAILABILITY
========================================================= --}}

<div class="card profile-card p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                {{ __('teacher.add_availability') }}
            </h3>

            <p class="text-muted mb-0">
                {{ __('teacher.availability_subtitle') }}
            </p>

        </div>

    </div>


    <form
        method="POST"
        action="{{ route('teacher.availability.store') }}"
    >

        @csrf


        <div class="row">


            {{-- DATE --}}

            <div class="col-md-3 mb-3">

                <label class="form-label">
                    {{ __('teacher.date') }}
                </label>

                <input
                    type="date"
                    name="available_date"
                    class="form-control"
                    value="{{ old('available_date') }}"
                    required
                >

            </div>



            {{-- DANCE STYLE --}}

            <div class="col-md-3 mb-3">

                <label class="form-label">
                    {{ __('teacher.dance_style') }}
                </label>

                <select
                    name="dance_style_id"
                    class="form-select"
                    required
                >

                    <option value="">
                        {{ __('teacher.select') }}
                    </option>

                    @foreach($teacher->danceStyles as $style)

                        <option
                            value="{{ $style->id }}"
                            {{ (int) old('dance_style_id') === (int) $style->id ? 'selected' : '' }}
                        >
                            {{ $style->name }}
                        </option>

                    @endforeach

                </select>

            </div>



            {{-- FROM --}}

            <div class="col-md-2 mb-3">

                <label class="form-label">
                    {{ __('teacher.from') }}
                </label>

                <select
    name="start_time"
    class="form-select"
    data-availability-start-time
    required
>

                    <option value="">
                        --:--
                    </option>

                    @for($hour = 0; $hour < 24; $hour++)

                        @foreach([0, 15, 30, 45] as $minute)

                            @php

                                $timeValue = sprintf(
                                    '%02d:%02d',
                                    $hour,
                                    $minute
                                );

                                $timeLabel =
                                    \Carbon\Carbon::createFromTime(
                                        $hour,
                                        $minute
                                    )->format(
                                        app()->getLocale() === 'fr'
                                            ? 'H:i'
                                            : 'g:i A'
                                    );

                            @endphp

                            <option
                                value="{{ $timeValue }}"
                                {{ old('start_time') === $timeValue ? 'selected' : '' }}
                            >
                                {{ $timeLabel }}
                            </option>

                        @endforeach

                    @endfor

                </select>

            </div>



            {{-- TO --}}

            <div class="col-md-2 mb-3">

                <label class="form-label">
                    {{ __('teacher.to') }}
                </label>

                <select
    name="end_time"
    class="form-select"
    data-availability-end-time
    required
>

                    <option value="">
                        --:--
                    </option>

                    @for($hour = 0; $hour < 24; $hour++)

                        @foreach([0, 15, 30, 45] as $minute)

                            @php

                                $timeValue = sprintf(
                                    '%02d:%02d',
                                    $hour,
                                    $minute
                                );

                                $timeLabel =
                                    \Carbon\Carbon::createFromTime(
                                        $hour,
                                        $minute
                                    )->format(
                                        app()->getLocale() === 'fr'
                                            ? 'H:i'
                                            : 'g:i A'
                                    );

                            @endphp

                            <option
                                value="{{ $timeValue }}"
                                {{ old('end_time') === $timeValue ? 'selected' : '' }}
                            >
                                {{ $timeLabel }}
                            </option>

                        @endforeach

                    @endfor

                </select>

            </div>



            {{-- ADD BUTTON --}}

            <div class="col-md-2 mb-3 d-flex align-items-end">

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >
                    {{ __('teacher.add') }}
                </button>

            </div>

        </div>



        {{-- =========================================================
            LESSON TYPES
        ========================================================= --}}

        @php

            $selectedTeachingTypes =
                old('edit_availability_id')
                    ? []
                    : old('teaching_types', []);

        @endphp


        <div class="mb-3">

            <label class="form-label fw-semibold">

                {{ __('teacher.lesson_types') }}

            </label>


            <div class="d-flex flex-wrap gap-4">


                {{-- ONLINE --}}

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="teaching_types[]"
                        value="online"
                        id="teaching_type_online"
                        {{ in_array('online', $selectedTeachingTypes, true) ? 'checked' : '' }}
                    >

                    <label
                        class="form-check-label"
                        for="teaching_type_online"
                    >
                        {{ __('teacher.lesson_type_online') }}
                    </label>

                </div>



                {{-- FACE TO FACE --}}

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="teaching_types[]"
                        value="face_to_face"
                        id="teaching_type_face_to_face"
                        {{ in_array('face_to_face', $selectedTeachingTypes, true) ? 'checked' : '' }}
                    >

                    <label
                        class="form-check-label"
                        for="teaching_type_face_to_face"
                    >
                        {{ __('teacher.lesson_type_face_to_face') }}
                    </label>

                </div>



                {{-- PUBLIC PLACE --}}

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="teaching_types[]"
                        value="public_place"
                        id="teaching_type_public_place"
                        {{ in_array('public_place', $selectedTeachingTypes, true) ? 'checked' : '' }}
                    >

                    <label
                        class="form-check-label"
                        for="teaching_type_public_place"
                    >
                        {{ __('teacher.lesson_type_public_place') }}
                    </label>

                </div>


            </div>


            <div class="form-text">

                {{ __('teacher.lesson_types_help') }}

            </div>


            @if(!old('edit_availability_id'))

                @error('teaching_types')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror


                @error('teaching_types.*')

                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>

                @enderror

            @endif


        </div>


    </form>

</div>





{{-- =========================================================
    CALENDAR
========================================================= --}}

@php

    $month =
        (int) request(
            'month',
            now()->month
        );

    $year =
        (int) request(
            'year',
            now()->year
        );


    $firstDay =
        \Carbon\Carbon::create(
            $year,
            $month,
            1
        );


    $daysInMonth =
        $firstDay->daysInMonth;


    $startDay =
        $firstDay->dayOfWeekIso;


    $previousMonth =
        $firstDay
            ->copy()
            ->subMonth();


    $nextMonth =
        $firstDay
            ->copy()
            ->addMonth();


    $dayNames = [

        __('teacher.monday_short'),

        __('teacher.tuesday_short'),

        __('teacher.wednesday_short'),

        __('teacher.thursday_short'),

        __('teacher.friday_short'),

        __('teacher.saturday_short'),

        __('teacher.sunday_short'),

    ];

@endphp



<div class="card profile-card p-4 mt-4">


<div class="d-flex justify-content-between align-items-center mb-4 teacher-calendar-navigation">


        {{-- PREVIOUS --}}

        <a
            href="{{ route('teacher.availability', [
                'month' => $previousMonth->month,
                'year' => $previousMonth->year,
            ]) }}"
            class="btn btn-outline-secondary"
        >

            ← {{ __('teacher.previous') }}

        </a>



        {{-- MONTH --}}

        <h3 class="mb-0">

            {{
                $firstDay
                    ->copy()
                    ->locale(app()->getLocale())
                    ->translatedFormat('F Y')
            }}

        </h3>



        {{-- NEXT --}}

        <a
            href="{{ route('teacher.availability', [
                'month' => $nextMonth->month,
                'year' => $nextMonth->year,
            ]) }}"
            class="btn btn-outline-secondary"
        >

            {{ __('teacher.next') }} →

        </a>


    </div>



    <div
    class="teacher-availability-calendar"
    style="
        display:grid;
        grid-template-columns:repeat(7, 1fr);
        gap:1px;
        background:#dee2e6;
        border:1px solid #dee2e6;
    "
>


        {{-- DAY NAMES --}}

        @foreach($dayNames as $dayName)

        <div
    class="teacher-calendar-weekday"
    style="
        background:#f1f3f5;
        padding:12px;
        text-align:center;
        font-weight:600;
    "
>

                {{ $dayName }}

            </div>

        @endforeach



        {{-- EMPTY DAYS --}}

        @for($i = 1; $i < $startDay; $i++)

        <div
    class="teacher-calendar-empty"
    style="
        background:#f8f9fa;
        min-height:120px;
    "
>

            </div>

        @endfor



        {{-- DAYS --}}

        @for($day = 1; $day <= $daysInMonth; $day++)

            @php

                $currentDate =
                    \Carbon\Carbon::create(
                        $year,
                        $month,
                        $day
                    );


                $dateString =
                    $currentDate
                        ->format('Y-m-d');


                $slots =
                    $availabilities
                        ->filter(
                            function ($availability) use ($dateString) {

                                return
                                    \Carbon\Carbon::parse(
                                        $availability->available_date
                                    )->format('Y-m-d')
                                    ===
                                    $dateString;

                            }
                        );


                $isToday =
                    $currentDate->isToday();


                $dayIndex =
                    $currentDate->dayOfWeekIso - 1;

            @endphp



            <div
    class="teacher-calendar-day {{ $isToday ? 'is-today' : '' }}"
    style="
        background: {{ $isToday ? '#eef5ff' : '#ffffff' }};
        min-height:120px;
        padding:10px;
    "
>

                <div class="d-flex justify-content-between mb-2">

                    <strong>
                        {{ $day }}
                    </strong>

                    <small class="text-muted">
                        {{ $dayNames[$dayIndex] }}
                    </small>

                </div>



                @foreach($slots as $slot)


                    <div class="border rounded p-2 mb-2 bg-light">


                        {{-- DANCE --}}

                        <div class="fw-semibold">

                            {{
                                $slot->danceStyle?->name
                                ??
                                __('teacher.dance')
                            }}

                        </div>



                        {{-- TIME --}}

                        <small>

                            {{ substr($slot->start_time, 0, 5) }}

                            -

                            {{ substr($slot->end_time, 0, 5) }}

                        </small>



                        {{-- LESSON TYPES --}}

                        @if(is_array($slot->teaching_types) && count($slot->teaching_types))


                            <div class="mt-1 d-flex flex-wrap gap-1">


                                @foreach($slot->teaching_types as $teachingType)


                                    @if($teachingType === 'online')

                                        <span class="badge bg-white text-secondary border">

                                            {{
                                                __('teacher.lesson_type_online')
                                            }}

                                        </span>


                                    @elseif($teachingType === 'face_to_face')

                                        <span class="badge bg-white text-secondary border">

                                            {{
                                                __('teacher.lesson_type_face_to_face')
                                            }}

                                        </span>


                                    @elseif($teachingType === 'public_place')

                                        <span class="badge bg-white text-secondary border">

                                            {{
                                                __('teacher.lesson_type_public_place')
                                            }}

                                        </span>

                                    @endif


                                @endforeach


                            </div>


                        @endif


                    </div>


                @endforeach


            </div>


        @endfor


    </div>


</div>





{{-- =========================================================
    UPCOMING AVAILABILITY
========================================================= --}}

<div class="card profile-card p-4 mt-4">


    <h4 class="mb-4">

        {{ __('teacher.upcoming_availability') }}

    </h4>



    @if(isset($availabilities) && $availabilities->count())


        @php

            $sortedAvailabilities =
                $availabilities
                    ->sortByDesc(
                        function ($availability) {

                            return
                                \Carbon\Carbon::parse(
                                    $availability->available_date
                                )->format('Y-m-d')
                                .
                                ' '
                                .
                                \Carbon\Carbon::parse(
                                    $availability->start_time
                                )->format('H:i:s');

                        }
                    );

        @endphp

        <div class="teacher-availability-table-wrapper">

        <table class="table align-middle">


            <thead>

                <tr>


                    <th>
                        {{ __('teacher.date') }}
                    </th>


                    <th>
                        {{ __('teacher.dance_style') }}
                    </th>


                    <th>
                        {{ __('teacher.lesson_types') }}
                    </th>


                    <th>
                        {{ __('teacher.from') }}
                    </th>


                    <th>
                        {{ __('teacher.to') }}
                    </th>


                    <th>
                        {{ __('teacher.actions') }}
                    </th>


                </tr>

            </thead>



            <tbody>


                @foreach($sortedAvailabilities as $availability)


                    @php

                        $editHasError =

                            (int) old(
                                'edit_availability_id'
                            )

                            ===

                            (int) $availability->id;

                    @endphp



                    {{-- =====================================================
                        MAIN ROW
                    ====================================================== --}}

                    <tr>


                        {{-- DATE --}}

                        <td>

                            {{
                                \Carbon\Carbon::parse(
                                    $availability->available_date
                                )
                                ->locale(app()->getLocale())
                                ->translatedFormat(
                                    app()->getLocale() === 'fr'
                                        ? 'D d M Y'
                                        : 'D M d, Y'
                                )
                            }}

                        </td>



                        {{-- DANCE --}}

                        <td>

                            {{
                                $availability->danceStyle?->name
                                ??
                                __('teacher.dance')
                            }}

                        </td>



                        {{-- LESSON TYPES --}}

                        <td>


                            @if(
                                is_array($availability->teaching_types)
                                &&
                                count($availability->teaching_types)
                            )


                                <div class="d-flex flex-wrap gap-1">


                                    @foreach($availability->teaching_types as $teachingType)


                                        @if($teachingType === 'online')

                                            <span class="badge bg-light text-dark border">

                                                {{
                                                    __('teacher.lesson_type_online')
                                                }}

                                            </span>


                                        @elseif($teachingType === 'face_to_face')

                                            <span class="badge bg-light text-dark border">

                                                {{
                                                    __('teacher.lesson_type_face_to_face')
                                                }}

                                            </span>


                                        @elseif($teachingType === 'public_place')

                                            <span class="badge bg-light text-dark border">

                                                {{
                                                    __('teacher.lesson_type_public_place')
                                                }}

                                            </span>

                                        @endif


                                    @endforeach


                                </div>


                            @else


                                <span class="text-muted small">

                                    —

                                </span>


                            @endif


                        </td>



                        {{-- FROM --}}

                        <td>


                            @if(app()->getLocale() === 'fr')

                                {{
                                    \Carbon\Carbon::parse(
                                        $availability->start_time
                                    )->format('H:i')
                                }}

                            @else

                                {{
                                    \Carbon\Carbon::parse(
                                        $availability->start_time
                                    )->format('g:i A')
                                }}

                            @endif


                        </td>



                        {{-- TO --}}

                        <td>


                            @if(app()->getLocale() === 'fr')

                                {{
                                    \Carbon\Carbon::parse(
                                        $availability->end_time
                                    )->format('H:i')
                                }}

                            @else

                                {{
                                    \Carbon\Carbon::parse(
                                        $availability->end_time
                                    )->format('g:i A')
                                }}

                            @endif


                        </td>



                        {{-- ACTIONS --}}

                        <td>


                            <div class="d-flex align-items-center gap-2 flex-wrap">


                                {{-- PAID = LOCKED --}}

                                @if($availability->has_paid_booking)


                                    <span
                                        class="
                                            badge
                                            bg-success-subtle
                                            text-success
                                            border
                                        "
                                    >

                                        🔒 {{ __('teacher.paid_locked') }}

                                    </span>


                                @else


                                    {{-- EDIT --}}

                                    @if($availability->can_edit)


                                        <button
                                            type="button"
                                            class="btn btn-outline-primary btn-sm"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#editAvailability{{ $availability->id }}"
                                            aria-controls="editAvailability{{ $availability->id }}"
                                            aria-expanded="{{ $editHasError ? 'true' : 'false' }}"
                                        >

                                            {{ __('teacher.edit') }}

                                        </button>


                                    @else


                                        <span
                                            class="
                                                badge
                                                bg-light
                                                text-secondary
                                                border
                                            "
                                        >

                                            {{ __('teacher.past_availability') }}

                                        </span>


                                    @endif



                                    {{-- DELETE --}}

                                    @if($availability->can_delete)


                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'teacher.availability.destroy',
                                                $availability
                                            ) }}"
                                            class="m-0"
                                            onsubmit="return confirm('{{ __('teacher.delete_availability_confirmation') }}');"
                                        >

                                            @csrf

                                            @method('DELETE')


                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger btn-sm"
                                            >

                                                {{ __('teacher.delete') }}

                                            </button>


                                        </form>


                                    @endif


                                @endif


                            </div>


                        </td>


                    </tr>





                    {{-- =====================================================
                        EDIT FORM
                    ====================================================== --}}

                    @if($availability->can_edit)


                        <tr>


                            <td
                                colspan="6"
                                class="p-0 border-0"
                            >


                                <div
                                    id="editAvailability{{ $availability->id }}"
                                    class="collapse {{ $editHasError ? 'show' : '' }}"
                                >


                                    <div class="border rounded p-3 mb-3 bg-light">


                                        <div class="mb-3">

                                            <strong>

                                                {{ __('teacher.edit_availability') }}

                                            </strong>

                                        </div>



                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'teacher.availability.update',
                                                $availability
                                            ) }}"
                                        >


                                            @csrf

                                            @method('PUT')



                                            <input
                                                type="hidden"
                                                name="edit_availability_id"
                                                value="{{ $availability->id }}"
                                            >



                                            <div class="row g-3">



                                                {{-- DATE --}}

                                                <div class="col-md-3">


                                                    <label class="form-label">

                                                        {{ __('teacher.date') }}

                                                    </label>


                                                    <input
                                                        type="date"
                                                        name="available_date"
                                                        class="form-control"
                                                        required
                                                        value="{{
                                                            $editHasError
                                                                ? old('available_date')
                                                                : \Carbon\Carbon::parse(
                                                                    $availability->available_date
                                                                )->format('Y-m-d')
                                                        }}"
                                                    >


                                                </div>




                                                {{-- DANCE STYLE --}}

                                                <div class="col-md-3">


                                                    <label class="form-label">

                                                        {{ __('teacher.dance_style') }}

                                                    </label>


                                                    <select
                                                        name="dance_style_id"
                                                        class="form-select"
                                                        required
                                                    >


                                                        @php

                                                            $selectedStyleId =

                                                                $editHasError
                                                                    ? (int) old(
                                                                        'dance_style_id'
                                                                    )
                                                                    : (int) $availability->dance_style_id;

                                                        @endphp


                                                        @foreach($teacher->danceStyles as $style)


                                                            <option
                                                                value="{{ $style->id }}"
                                                                {{
                                                                    $selectedStyleId === (int) $style->id
                                                                        ? 'selected'
                                                                        : ''
                                                                }}
                                                            >

                                                                {{ $style->name }}

                                                            </option>


                                                        @endforeach


                                                    </select>


                                                </div>




                                                {{-- FROM --}}

                                                <div class="col-md-2">


                                                    <label class="form-label">

                                                        {{ __('teacher.from') }}

                                                    </label>


                                                    <select
    name="start_time"
    class="form-select"
    data-availability-start-time
    required
>


                                                        @php

                                                            $currentStart =

                                                                $editHasError
                                                                    ? old('start_time')
                                                                    : \Carbon\Carbon::parse(
                                                                        $availability->start_time
                                                                    )->format('H:i');

                                                        @endphp


                                                        @for($hour = 0; $hour < 24; $hour++)


                                                            @foreach([0, 15, 30, 45] as $minute)


                                                                @php

                                                                    $timeValue =
                                                                        sprintf(
                                                                            '%02d:%02d',
                                                                            $hour,
                                                                            $minute
                                                                        );


                                                                    $timeLabel =
                                                                        \Carbon\Carbon::createFromTime(
                                                                            $hour,
                                                                            $minute
                                                                        )->format(
                                                                            app()->getLocale() === 'fr'
                                                                                ? 'H:i'
                                                                                : 'g:i A'
                                                                        );

                                                                @endphp


                                                                <option
                                                                    value="{{ $timeValue }}"
                                                                    {{
                                                                        $currentStart === $timeValue
                                                                            ? 'selected'
                                                                            : ''
                                                                    }}
                                                                >

                                                                    {{ $timeLabel }}

                                                                </option>


                                                            @endforeach


                                                        @endfor


                                                    </select>


                                                </div>




                                                {{-- TO --}}

                                                <div class="col-md-2">


                                                    <label class="form-label">

                                                        {{ __('teacher.to') }}

                                                    </label>


                                                    <select
    name="end_time"
    class="form-select"
    data-availability-end-time
    required
>


                                                        @php

                                                            $currentEnd =

                                                                $editHasError
                                                                    ? old('end_time')
                                                                    : \Carbon\Carbon::parse(
                                                                        $availability->end_time
                                                                    )->format('H:i');

                                                        @endphp


                                                        @for($hour = 0; $hour < 24; $hour++)


                                                            @foreach([0, 15, 30, 45] as $minute)


                                                                @php

                                                                    $timeValue =
                                                                        sprintf(
                                                                            '%02d:%02d',
                                                                            $hour,
                                                                            $minute
                                                                        );


                                                                    $timeLabel =
                                                                        \Carbon\Carbon::createFromTime(
                                                                            $hour,
                                                                            $minute
                                                                        )->format(
                                                                            app()->getLocale() === 'fr'
                                                                                ? 'H:i'
                                                                                : 'g:i A'
                                                                        );

                                                                @endphp


                                                                <option
                                                                    value="{{ $timeValue }}"
                                                                    {{
                                                                        $currentEnd === $timeValue
                                                                            ? 'selected'
                                                                            : ''
                                                                    }}
                                                                >

                                                                    {{ $timeLabel }}

                                                                </option>


                                                            @endforeach


                                                        @endfor


                                                    </select>


                                                </div>




                                                {{-- ACTIONS --}}

                                                <div
                                                    class="
                                                        col-md-2
                                                        d-flex
                                                        align-items-end
                                                        gap-2
                                                    "
                                                >


                                                    <button
                                                        type="submit"
                                                        class="
                                                            btn
                                                            btn-primary
                                                            btn-sm
                                                            flex-grow-1
                                                        "
                                                    >

                                                        {{ __('teacher.save_changes') }}

                                                    </button>


                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-secondary btn-sm"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#editAvailability{{ $availability->id }}"
                                                    >

                                                        {{ __('teacher.cancel') }}

                                                    </button>


                                                </div>




                                                {{-- =================================================
                                                    LESSON TYPES
                                                ================================================= --}}

                                                @php

                                                    $currentTeachingTypes =

                                                        $editHasError
                                                            ? old(
                                                                'teaching_types',
                                                                []
                                                            )
                                                            : (
                                                                $availability->teaching_types
                                                                ??
                                                                []
                                                            );

                                                @endphp



                                                <div class="col-12">


                                                    <label class="form-label fw-semibold">

                                                        {{ __('teacher.lesson_types') }}

                                                    </label>


                                                    <div class="d-flex flex-wrap gap-4">



                                                        {{-- ONLINE --}}

                                                        <div class="form-check">


                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                name="teaching_types[]"
                                                                value="online"
                                                                id="edit_teaching_type_online_{{ $availability->id }}"
                                                                {{
                                                                    in_array(
                                                                        'online',
                                                                        $currentTeachingTypes,
                                                                        true
                                                                    )
                                                                        ? 'checked'
                                                                        : ''
                                                                }}
                                                            >


                                                            <label
                                                                class="form-check-label"
                                                                for="edit_teaching_type_online_{{ $availability->id }}"
                                                            >

                                                                {{
                                                                    __('teacher.lesson_type_online')
                                                                }}

                                                            </label>


                                                        </div>




                                                        {{-- FACE TO FACE --}}

                                                        <div class="form-check">


                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                name="teaching_types[]"
                                                                value="face_to_face"
                                                                id="edit_teaching_type_face_to_face_{{ $availability->id }}"
                                                                {{
                                                                    in_array(
                                                                        'face_to_face',
                                                                        $currentTeachingTypes,
                                                                        true
                                                                    )
                                                                        ? 'checked'
                                                                        : ''
                                                                }}
                                                            >


                                                            <label
                                                                class="form-check-label"
                                                                for="edit_teaching_type_face_to_face_{{ $availability->id }}"
                                                            >

                                                                {{
                                                                    __('teacher.lesson_type_face_to_face')
                                                                }}

                                                            </label>


                                                        </div>




                                                        {{-- PUBLIC PLACE --}}

                                                        <div class="form-check">


                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                name="teaching_types[]"
                                                                value="public_place"
                                                                id="edit_teaching_type_public_place_{{ $availability->id }}"
                                                                {{
                                                                    in_array(
                                                                        'public_place',
                                                                        $currentTeachingTypes,
                                                                        true
                                                                    )
                                                                        ? 'checked'
                                                                        : ''
                                                                }}
                                                            >


                                                            <label
                                                                class="form-check-label"
                                                                for="edit_teaching_type_public_place_{{ $availability->id }}"
                                                            >

                                                                {{
                                                                    __('teacher.lesson_type_public_place')
                                                                }}

                                                            </label>


                                                        </div>


                                                    </div>



                                                    <div class="form-text">

                                                        {{
                                                            __('teacher.lesson_types_help')
                                                        }}

                                                    </div>



                                                    @if($editHasError)


                                                        @error('teaching_types')

                                                            <div class="text-danger small mt-1">

                                                                {{ $message }}

                                                            </div>

                                                        @enderror



                                                        @error('teaching_types.*')

                                                            <div class="text-danger small mt-1">

                                                                {{ $message }}

                                                            </div>

                                                        @enderror


                                                    @endif


                                                </div>


                                            </div>


                                        </form>


                                    </div>


                                </div>


                            </td>


                        </tr>


                    @endif


                @endforeach


            </tbody>


        </table>
        </div>


    @else


        <div class="text-muted">

            {{ __('teacher.no_availability') }}

        </div>


    @endif


</div>



<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        const startTimeSelects =
            document.querySelectorAll(
                '[data-availability-start-time]'
            );


        const timeToMinutes =
            function (value) {

                if (!value) {
                    return null;
                }

                const parts =
                    value.split(':');

                if (parts.length < 2) {
                    return null;
                }

                const hours =
                    parseInt(
                        parts[0],
                        10
                    );

                const minutes =
                    parseInt(
                        parts[1],
                        10
                    );

                if (
                    Number.isNaN(hours)
                    ||
                    Number.isNaN(minutes)
                ) {
                    return null;
                }

                return (
                    hours * 60
                ) + minutes;
            };


        startTimeSelects.forEach(
            function (startSelect) {

                const form =
                    startSelect.closest(
                        'form'
                    );


                if (!form) {
                    return;
                }


                const endSelect =
                    form.querySelector(
                        '[data-availability-end-time]'
                    );


                if (!endSelect) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | SAVE ORIGINAL END TIME OPTIONS
                |--------------------------------------------------------------------------
                */

                const originalEndOptions =
                    Array
                        .from(
                            endSelect.options
                        )
                        .map(
                            function (option) {

                                return {
                                    value:
                                        option.value,

                                    text:
                                        option.textContent,

                                    selected:
                                        option.selected
                                };
                            }
                        );


                const rebuildEndTimeOptions =
                    function () {

                        const startMinutes =
                            timeToMinutes(
                                startSelect.value
                            );


                        const previousEndValue =
                            endSelect.value;


                        /*
                        |--------------------------------------------------------------------------
                        | CLEAR CURRENT LIST COMPLETELY
                        |--------------------------------------------------------------------------
                        */

                        endSelect.innerHTML =
                            '';


                        /*
                        |--------------------------------------------------------------------------
                        | PLACEHOLDER
                        |--------------------------------------------------------------------------
                        */

                        const placeholder =
                            document.createElement(
                                'option'
                            );

                        placeholder.value =
                            '';

                        placeholder.textContent =
                            '--:--';

                        endSelect.appendChild(
                            placeholder
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | NO START TIME SELECTED
                        |--------------------------------------------------------------------------
                        |
                        | Show every End Time.
                        |
                        */

                        if (
                            startMinutes
                            ===
                            null
                        ) {

                            originalEndOptions
                                .forEach(
                                    function (
                                        originalOption
                                    ) {

                                        if (
                                            originalOption.value
                                            ===
                                            ''
                                        ) {
                                            return;
                                        }


                                        const option =
                                            document.createElement(
                                                'option'
                                            );


                                        option.value =
                                            originalOption.value;


                                        option.textContent =
                                            originalOption.text;


                                        endSelect.appendChild(
                                            option
                                        );
                                    }
                                );


                            if (
                                previousEndValue
                                !==
                                ''
                            ) {

                                endSelect.value =
                                    previousEndValue;
                            }


                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | ONLY SHOW END TIMES AFTER START TIME
                        |--------------------------------------------------------------------------
                        |
                        | Example:
                        |
                        | From = 08:00
                        |
                        | NOT SHOWN:
                        | 00:00 -> 08:00
                        |
                        | SHOWN:
                        | 08:15 -> 23:45
                        |
                        */

                        originalEndOptions
                            .forEach(
                                function (
                                    originalOption
                                ) {

                                    if (
                                        originalOption.value
                                        ===
                                        ''
                                    ) {
                                        return;
                                    }


                                    const endMinutes =
                                        timeToMinutes(
                                            originalOption.value
                                        );


                                    if (
                                        endMinutes
                                        <=
                                        startMinutes
                                    ) {

                                        return;
                                    }


                                    const option =
                                        document.createElement(
                                            'option'
                                        );


                                    option.value =
                                        originalOption.value;


                                    option.textContent =
                                        originalOption.text;


                                    endSelect.appendChild(
                                        option
                                    );
                                }
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | KEEP CURRENT END TIME ONLY IF STILL VALID
                        |--------------------------------------------------------------------------
                        */

                        const previousEndMinutes =
                            timeToMinutes(
                                previousEndValue
                            );


                        if (
                            previousEndMinutes
                            !==
                            null
                            &&
                            previousEndMinutes
                            >
                            startMinutes
                        ) {

                            endSelect.value =
                                previousEndValue;

                        } else {

                            endSelect.value =
                                '';
                        }
                    };


                /*
                |--------------------------------------------------------------------------
                | WHEN START TIME CHANGES
                |--------------------------------------------------------------------------
                */

                startSelect.addEventListener(
                    'change',
                    rebuildEndTimeOptions
                );


                /*
                |--------------------------------------------------------------------------
                | INITIAL LOAD
                |--------------------------------------------------------------------------
                */

                rebuildEndTimeOptions();

            }
        );

    }
);
</script>
<style>

/* =========================================================
   MOBILE ONLY - TEACHER AVAILABILITY
   Desktop stays unchanged
========================================================= */

@media (max-width: 767px) {

    .teacher-panel .profile-card {
        padding:16px !important;
    }

    .teacher-panel
    .profile-card
    .row > div {
        width:100%;
    }

    .teacher-panel
    .form-control,
    .teacher-panel
    .form-select {
        width:100%;
        min-height:44px;
        font-size:16px;
    }


    .teacher-calendar-navigation {
        display:grid !important;

        grid-template-columns:
            auto
            1fr
            auto;

        gap:8px;

        align-items:center;

        margin-bottom:16px;
    }

    .teacher-calendar-navigation h3 {
        margin:0;

        font-size:16px;

        text-align:center;

        white-space:nowrap;
    }

    .teacher-calendar-navigation .btn {
        padding:8px 10px;

        font-size:11px;

        white-space:nowrap;
    }


    .teacher-availability-calendar {
        display:block !important;

        background:transparent !important;

        border:0 !important;
    }


    .teacher-calendar-weekday {
        display:none !important;
    }


    .teacher-calendar-empty {
        display:none !important;
    }


    .teacher-calendar-day {
        width:100% !important;

        min-height:0 !important;

        margin-bottom:10px;

        padding:14px !important;

        border:1px solid #E5E7EB;

        border-radius:12px;

        background:#FFFFFF !important;

        overflow:visible !important;
    }


    .teacher-calendar-day.is-today {
        background:#EEF5FF !important;

        border-color:#BFDBFE;
    }


    .teacher-calendar-day
    > .d-flex:first-child {
        display:flex !important;

        align-items:center !important;

        justify-content:flex-start !important;

        gap:8px;

        margin-bottom:10px !important;
    }


    .teacher-calendar-day
    > .d-flex:first-child strong {
        font-size:15px;
    }


    .teacher-calendar-day
    > .d-flex:first-child small {
        font-size:13px;

        font-weight:700;

        white-space:nowrap;

        color:#475569 !important;
    }


    .teacher-calendar-day
    .border.rounded {
        margin-bottom:8px !important;

        padding:10px !important;

        border-radius:10px !important;
    }


    .teacher-calendar-day
    .fw-semibold {
        font-size:13px;
    }


    .teacher-calendar-day small {
        white-space:normal;
    }


    .teacher-availability-table-wrapper {
        width:100%;

        overflow-x:auto;

        -webkit-overflow-scrolling:touch;
    }


    .teacher-availability-table-wrapper table {
        min-width:760px;
    }


    .teacher-availability-table-wrapper th,
    .teacher-availability-table-wrapper td {
        white-space:nowrap;
    }

}


@media (max-width: 430px) {

    .teacher-calendar-navigation {
        grid-template-columns:
            1fr
            1fr;
    }

    .teacher-calendar-navigation h3 {
        grid-column:1 / -1;
        grid-row:1;

        margin-bottom:4px;
    }

    .teacher-calendar-navigation a:first-child {
        grid-column:1;
        grid-row:2;
    }

    .teacher-calendar-navigation a:last-child {
        grid-column:2;
        grid-row:2;

        text-align:center;
    }

}

</style>

@endsection