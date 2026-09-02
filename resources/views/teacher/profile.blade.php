@extends('teacher.layout')

@section('title', __('teacher.my_profile'))
@section('page-title', __('teacher.my_profile'))

@section('content')

<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-12">

            <!-- <h2 class="mb-4">
                {{ __('teacher.edit_teacher_profile') }}
            </h2> -->


            {{-- SUCCESS --}}
            @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

            @endif


            {{-- ERRORS --}}
            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('teacher.profile.update') }}"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')


                {{-- ========================================= --}}
                {{-- PROFILE MEDIA --}}
                {{-- ========================================= --}}

                <div class="card profile-card p-4 mb-4">

                    <h4 class="mb-4">
                        {{ __('teacher.profile_media') }}
                    </h4>


                    <div class="row g-4">

                        {{-- PROFILE PHOTO --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                {{ __('teacher.profile_photo') }}
                            </label>

                            @if($teacher->profile_photo)

                                <div class="mb-3">

                                    <img
                                        src="{{ asset('storage/' . $teacher->profile_photo) }}"
                                        alt="{{ __('teacher.profile_photo') }}"
                                        style="
                                            width:120px;
                                            height:120px;
                                            object-fit:cover;
                                            border-radius:50%;
                                        "
                                    >

                                </div>

                            @endif

                            <input
                                type="file"
                                name="profile_photo"
                                class="form-control"
                                accept="image/*"
                            >

                            <small class="text-muted">
                                {{ __('teacher.photo_help') }}
                            </small>

                        </div>


                        {{-- INTRO VIDEO --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                {{ __('teacher.introduction_video') }}
                            </label>

                            @if($teacher->intro_video)

                                <div class="mb-3">

                                    <video
                                        controls
                                        style="
                                            width:100%;
                                            max-width:320px;
                                            border-radius:12px;
                                        "
                                    >
                                        <source
                                            src="{{ asset('storage/' . $teacher->intro_video) }}"
                                        >
                                    </video>

                                </div>

                            @endif

                            <input
                                type="file"
                                name="intro_video"
                                class="form-control"
                                accept="video/mp4,video/webm,video/quicktime"
                            >

                            <small class="text-muted">
                                {{ __('teacher.video_help') }}
                            </small>

                        </div>

                    </div>

                </div>



                {{-- ========================================= --}}
                {{-- ACCOUNT INFORMATION --}}
                {{-- ========================================= --}}

                <div class="card profile-card p-4 mb-4">

                    <h4 class="mb-4">
                        {{ __('teacher.account_information') }}
                    </h4>


                    <div class="row g-3">

                        {{-- NAME --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                {{ __('teacher.name') }}
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name', auth()->user()->name) }}"
                                required
                            >

                        </div>


                        {{-- EMAIL --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                {{ __('teacher.email') }}
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email', auth()->user()->email) }}"
                                required
                            >

                        </div>


                        {{-- PASSWORD --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                {{ __('teacher.new_password') }}
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                autocomplete="new-password"
                                placeholder="{{ __('teacher.password_placeholder') }}"
                            >

                        </div>


                        {{-- CONFIRM PASSWORD --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                {{ __('teacher.confirm_new_password') }}
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                autocomplete="new-password"
                                placeholder="{{ __('teacher.confirm_password_placeholder') }}"
                            >

                        </div>

                    </div>

                </div>



                {{-- ========================================= --}}
                {{-- TEACHER PROFILE --}}
                {{-- ========================================= --}}

                <div class="card profile-card p-4 mb-4">

                    <h4 class="mb-4">
                        {{ __('teacher.teacher_information') }}
                    </h4>


                    {{-- BIO --}}
                    <div class="mb-3">

                        <label class="form-label">
                            {{ __('teacher.bio') }}
                        </label>

                        <textarea
                            name="bio"
                            class="form-control"
                            rows="5"
                        >{{ old('bio', $teacher->bio) }}</textarea>

                    </div>


                    {{-- EXPERIENCE + RATE --}}
                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                {{ __('teacher.years_of_experience') }}
                            </label>

                            <input
                                type="number"
                                name="experience_years"
                                value="{{ old('experience_years', $teacher->experience_years) }}"
                                class="form-control"
                                min="0"
                                max="80"
                            >

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                {{ __('teacher.default_hourly_rate') }}
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="hourly_rate"
                                value="{{ old('hourly_rate', $teacher->hourly_rate) }}"
                                class="form-control"
                                min="0"
                            >

                        </div>

                    </div>



                    {{-- ========================================= --}}
                    {{-- LOCATION --}}
                    {{-- ========================================= --}}

                    <div class="row">

                        {{-- PROVINCE --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                {{ __('teacher.province') }}
                            </label>

                            <select
                                id="province"
                                name="province"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    {{ __('teacher.select_province') }}
                                </option>


                                @foreach($provinces as $province)

                                    <option
                                        value="{{ $province->id }}"
                                        {{
                                            old(
                                                'province',
                                                $selectedProvince?->id
                                            ) == $province->id
                                                ? 'selected'
                                                : ''
                                        }}
                                    >
                                        {{ $province->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- CITY --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                {{ __('teacher.city') }}
                            </label>

                            <select
                                id="city"
                                name="city"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    {{ __('teacher.select_city') }}
                                </option>


                                @foreach($cities as $city)

                                    <option
                                        value="{{ $city->name }}"
                                        {{
                                            old('city', $teacher->city) === $city->name
                                                ? 'selected'
                                                : ''
                                        }}
                                    >
                                        {{ $city->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- COUNTRY --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                {{ __('teacher.country') }}
                            </label>

                            <input
                                type="text"
                                name="country"
                                value="{{ old('country', $teacher->country ?? 'Canada') }}"
                                class="form-control"
                                readonly
                            >

                        </div>

                    </div>



                    {{-- ========================================= --}}
                    {{-- DANCE STYLES --}}
                    {{-- ========================================= --}}

                    <div class="mb-4">

                        <label class="form-label fw-bold mb-3">
                            {{ __('teacher.dance_styles_hourly_rates') }}
                        </label>


                        <div class="row g-3">

                            @foreach($danceStyles as $style)

                                @php
                                    $teacherStyle = $teacher->danceStyles
                                        ->firstWhere('id', $style->id);

                                    $isSelected = $teacherStyle !== null;

                                    $currentRate = $teacherStyle
                                        ? $teacherStyle->pivot->hourly_rate
                                        : null;
                                @endphp


                                <div class="col-md-6">

                                    <div class="dance-style-price-card">

                                        <div class="form-check mb-2">

                                            <input
                                                class="form-check-input dance-style-checkbox"
                                                type="checkbox"
                                                name="dance_styles[]"
                                                value="{{ $style->id }}"
                                                id="style_{{ $style->id }}"
                                                data-style-id="{{ $style->id }}"
                                                {{ $isSelected ? 'checked' : '' }}
                                            >

                                            <label
                                                class="form-check-label fw-semibold"
                                                for="style_{{ $style->id }}"
                                            >
                                                {{ $style->name }}
                                            </label>

                                        </div>


                                        <div
                                            id="rate_box_{{ $style->id }}"
                                            class="dance-style-rate"
                                            @if(!$isSelected)
                                                style="display:none;"
                                            @endif
                                        >

                                            <label
                                                for="rate_{{ $style->id }}"
                                                class="form-label small text-muted"
                                            >
                                                {{ __('teacher.hourly_rate') }}
                                            </label>


                                            <div class="input-group">

                                                <span class="input-group-text">
                                                    $
                                                </span>

                                                <input
                                                    type="number"
                                                    id="rate_{{ $style->id }}"
                                                    name="dance_rates[{{ $style->id }}]"
                                                    class="form-control"
                                                    step="0.01"
                                                    min="0"
                                                    value="{{ old(
                                                        'dance_rates.' . $style->id,
                                                        $currentRate
                                                    ) }}"
                                                    placeholder="0.00"
                                                >

                                                <span class="input-group-text">
                                                    CAD / {{ __('teacher.hour') }}
                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>


                    {{-- SAVE --}}
                    <div class="d-flex justify-content-end">

                        <button
                            type="submit"
                            class="btn btn-primary px-5"
                        >
                            {{ __('teacher.save_profile') }}
                        </button>

                    </div>

                </div>

            </form>

        </div>
    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const provinceSelect =
        document.getElementById('province');

    const citySelect =
        document.getElementById('city');


    provinceSelect.addEventListener('change', function () {

        const provinceId = this.value;


        citySelect.innerHTML =
            '<option value="">{{ __("teacher.loading") }}</option>';

        citySelect.disabled = true;


        if (!provinceId) {

            citySelect.innerHTML =
                '<option value="">{{ __("teacher.select_province_first") }}</option>';

            return;
        }


        fetch(`/teacher/cities/${provinceId}`)
            .then(response => response.json())
            .then(cities => {

                citySelect.innerHTML =
                    '<option value="">{{ __("teacher.select_city") }}</option>';


                cities.forEach(city => {

                    const option =
                        document.createElement('option');

                    option.value = city.name;

                    option.textContent = city.name;

                    citySelect.appendChild(option);

                });


                citySelect.disabled = false;

            })
            .catch(error => {

                console.error(error);

                citySelect.innerHTML =
                    '<option value="">{{ __("teacher.unable_load_cities") }}</option>';

                citySelect.disabled = false;

            });

    });


    document
        .querySelectorAll('.dance-style-checkbox')
        .forEach(function (checkbox) {

            checkbox.addEventListener('change', function () {

                const styleId = this.dataset.styleId;

                const rateBox =
                    document.getElementById(
                        'rate_box_' + styleId
                    );

                const rateInput =
                    document.getElementById(
                        'rate_' + styleId
                    );


                if (this.checked) {

                    rateBox.style.display = 'block';

                    rateInput.required = true;

                } else {

                    rateBox.style.display = 'none';

                    rateInput.required = false;

                }

            });


            const styleId =
                checkbox.dataset.styleId;

            const rateInput =
                document.getElementById(
                    'rate_' + styleId
                );

            rateInput.required =
                checkbox.checked;

        });

});

</script>

@endsection