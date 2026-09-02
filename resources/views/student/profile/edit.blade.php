@extends('student.layout')

@section('title', __('student.my_profile'))
@section('page-title', __('student.my_profile'))

@section('content')


<style>

/* =========================================================
   CUSTOM PROFILE PHOTO PICKER
========================================================= */

.student-photo-picker {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
}

.student-photo-input {
    display: none !important;
}

.student-photo-button {
    min-height: 42px;
    padding: 9px 16px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 auto;

    margin: 0;

    border: 1px solid #0d6efd;
    border-radius: 9px;

    background: #ffffff;
    color: #0d6efd;

    font-size: 14px;
    font-weight: 600;

    cursor: pointer;

    white-space: nowrap;

    transition:
        background .15s ease,
        color .15s ease,
        border-color .15s ease,
        box-shadow .15s ease;
}

.student-photo-button:hover {
    background: #0d6efd;
    color: #ffffff;

    box-shadow: 0 4px 12px rgba(13, 110, 253, .12);
}

.student-photo-file-name {
    min-height: 42px;

    flex: 1;

    display: flex;
    align-items: center;

    min-width: 0;

    padding: 9px 13px;

    border: 1px solid #d5e0e8;
    border-radius: 9px;

    background: #ffffff;

    color: #64748b;

    font-size: 13px;

    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.student-photo-file-name.has-file {
    color: #0f172a;
    font-weight: 500;
}

@media (max-width: 700px) {

    .student-photo-picker {
        flex-direction: column;
        align-items: stretch;
    }

    .student-photo-button {
        width: 100%;
    }

}

</style>



@if(session('success'))

    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>

@endif


@if($errors->any())

    <div class="alert alert-danger mb-4">

        <ul class="mb-0">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif



<div class="card profile-card p-4">


    <div class="mb-4">

        <h3 class="mb-1">
            {{ __('student.student_profile') }}
        </h3>

        <p class="text-muted mb-0">
            {{ __('student.profile_subtitle') }}
        </p>

    </div>



    <form
        method="POST"
        action="{{ route('student.profile.update') }}"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')



        {{-- =========================================================
           PROFILE PHOTO
        ========================================================== --}}

        <div class="card profile-card p-4 mb-4">


            <h5 class="mb-3">
                {{ __('student.profile_photo') }}
            </h5>


            <div class="d-flex align-items-center gap-4">


                @if($student->profile_photo)


                    <img
                        src="{{ asset('storage/' . $student->profile_photo) }}"
                        alt="{{ auth()->user()->name }}"
                        style="
                            width:110px;
                            height:110px;
                            object-fit:cover;
                            border-radius:50%;
                            border:4px solid white;
                        "
                    >


                @else


                    <div
                        style="
                            width:110px;
                            height:110px;
                            border-radius:50%;
                            background:#DFF2FF;
                            color:#0369A1;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            font-size:34px;
                            font-weight:700;
                        "
                    >

                        {{ strtoupper(
                            substr(
                                auth()->user()->name ?? 'S',
                                0,
                                1
                            )
                        ) }}

                    </div>


                @endif



                <div class="flex-grow-1">


                    <label class="form-label">
                        {{ __('student.upload_new_photo') }}
                    </label>



                    {{-- CUSTOM FILE PICKER --}}

                    <div class="student-photo-picker">


                        <input
                            type="file"
                            id="profile_photo"
                            name="profile_photo"
                            class="student-photo-input"
                            accept="image/jpeg,image/png,image/webp"
                        >


                        <label
                            for="profile_photo"
                            class="student-photo-button"
                        >

                            {{ __('student.upload_new_photo') }}

                        </label>


                        <div
                            id="profile_photo_file_name"
                            class="student-photo-file-name"
                        >
                            —
                        </div>


                    </div>


                    <small class="text-muted d-block mt-2">
                        {{ __('student.photo_help') }}
                    </small>


                </div>


            </div>

        </div>



        {{-- =========================================================
           NAME + EMAIL
        ========================================================== --}}

        <div class="row g-4 mb-4">


            <div class="col-md-6">

                <label class="form-label">
                    {{ __('student.name') }}
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name', auth()->user()->name) }}"
                    required
                >

            </div>



            <div class="col-md-6">

                <label class="form-label">
                    {{ __('student.email') }}
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', auth()->user()->email) }}"
                    required
                >

            </div>


        </div>



        {{-- =========================================================
           BIRTH DATE + GENDER
        ========================================================== --}}

        <div class="row g-4 mb-4">


            <div class="col-md-6">

                <label class="form-label">
                    {{ __('student.birth_date') }}
                </label>

                <input
                    type="date"
                    name="birth_date"
                    class="form-control"
                    lang="{{ app()->getLocale() }}"
                    value="{{ old(
                        'birth_date',
                        $student->birth_date
                            ? $student->birth_date->format('Y-m-d')
                            : ''
                    ) }}"
                >

            </div>



            <div class="col-md-6">

                <label class="form-label">
                    {{ __('student.gender') }}
                </label>


                <select
                    name="gender"
                    class="form-select"
                >

                    <option value="">
                        {{ __('student.select_gender') }}
                    </option>


                    <option
                        value="male"
                        {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}
                    >
                        {{ __('student.male') }}
                    </option>


                    <option
                        value="female"
                        {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}
                    >
                        {{ __('student.female') }}
                    </option>


                    <option
                        value="other"
                        {{ old('gender', $student->gender) === 'other' ? 'selected' : '' }}
                    >
                        {{ __('student.other') }}
                    </option>


                    <option
                        value="prefer_not_to_say"
                        {{ old('gender', $student->gender) === 'prefer_not_to_say' ? 'selected' : '' }}
                    >
                        {{ __('student.prefer_not_to_say') }}
                    </option>

                </select>

            </div>


        </div>



        {{-- =========================================================
           LOCATION
        ========================================================== --}}

        <h5 class="mb-3">
            {{ __('student.location') }}
        </h5>


        <div class="row g-4 mb-4">


            <div class="col-md-4">

                <label class="form-label">
                    {{ __('student.province') }}
                </label>

                <input
                    type="text"
                    name="province"
                    class="form-control"
                    value="{{ old('province', $student->province) }}"
                    placeholder="Quebec"
                >

            </div>



            <div class="col-md-4">

                <label class="form-label">
                    {{ __('student.city') }}
                </label>

                <input
                    type="text"
                    name="city"
                    class="form-control"
                    value="{{ old('city', $student->city) }}"
                    placeholder="Longueuil"
                >

            </div>



            <div class="col-md-4">

                <label class="form-label">
                    {{ __('student.country') }}
                </label>

                <input
                    type="text"
                    name="country"
                    class="form-control"
                    value="{{ old('country', $student->country ?? 'Canada') }}"
                >

            </div>


        </div>



        {{-- =========================================================
           DANCE EXPERIENCE
        ========================================================== --}}

        <h5 class="mb-3">
            {{ __('student.dance_experience') }}
        </h5>


        <div class="row g-4 mb-4">


            <div class="col-md-6">

                <label class="form-label">
                    {{ __('student.taken_dance_classes_before') }}
                </label>


                <select
                    name="has_dance_experience"
                    class="form-select"
                    required
                >

                    <option
                        value="0"
                        {{ (string) old(
                            'has_dance_experience',
                            (int) $student->has_dance_experience
                        ) === '0' ? 'selected' : '' }}
                    >
                        {{ __('student.first_time') }}
                    </option>


                    <option
                        value="1"
                        {{ (string) old(
                            'has_dance_experience',
                            (int) $student->has_dance_experience
                        ) === '1' ? 'selected' : '' }}
                    >
                        {{ __('student.taken_classes_before') }}
                    </option>

                </select>

            </div>



            <div class="col-md-6">

                <label class="form-label">
                    {{ __('student.experience_level') }}
                </label>


                <select
                    name="experience_level"
                    class="form-select"
                >

                    <option value="">
                        {{ __('student.select_your_level') }}
                    </option>


                    <option
                        value="beginner"
                        {{ old('experience_level', $student->experience_level) === 'beginner' ? 'selected' : '' }}
                    >
                        {{ __('student.beginner') }}
                    </option>


                    <option
                        value="intermediate"
                        {{ old('experience_level', $student->experience_level) === 'intermediate' ? 'selected' : '' }}
                    >
                        {{ __('student.intermediate') }}
                    </option>


                    <option
                        value="advanced"
                        {{ old('experience_level', $student->experience_level) === 'advanced' ? 'selected' : '' }}
                    >
                        {{ __('student.advanced') }}
                    </option>

                </select>

            </div>


        </div>



        {{-- =========================================================
           BIO
        ========================================================== --}}

        <div class="mb-4">

            <label class="form-label">
                {{ __('student.about_me') }}
            </label>


            <textarea
                name="bio"
                class="form-control"
                rows="5"
                placeholder="{{ __('student.bio_placeholder') }}"
            >{{ old('bio', $student->bio) }}</textarea>


            <small class="text-muted">
                {{ __('student.bio_help') }}
            </small>

        </div>



        {{-- =========================================================
           CHANGE PASSWORD
        ========================================================== --}}

        <h5 class="mb-3">
            {{ __('student.change_password') }}
        </h5>


        <div class="row g-4 mb-4">


            <div class="col-md-6">

                <label class="form-label">
                    {{ __('student.new_password') }}
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="{{ __('student.password_placeholder') }}"
                >

                <small class="text-muted">
                    {{ __('student.password_help') }}
                </small>

            </div>



            <div class="col-md-6">

                <label class="form-label">
                    {{ __('student.confirm_new_password') }}
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="{{ __('student.confirm_password_placeholder') }}"
                >

            </div>


        </div>



        {{-- =========================================================
           SAVE
        ========================================================== --}}

        <div class="d-flex justify-content-end">

            <button
                type="submit"
                class="btn btn-primary px-5"
            >
                {{ __('student.save_profile') }}
            </button>

        </div>


    </form>

</div>



<script>

document.addEventListener('DOMContentLoaded', function () {

    const photoInput =
        document.getElementById('profile_photo');

    const photoFileName =
        document.getElementById('profile_photo_file_name');


    if (
        photoInput
        &&
        photoFileName
    ) {

        photoInput.addEventListener(
            'change',
            function () {

                if (
                    this.files
                    &&
                    this.files.length > 0
                ) {

                    photoFileName.textContent =
                        this.files[0].name;

                    photoFileName.classList.add(
                        'has-file'
                    );

                } else {

                    photoFileName.textContent = '—';

                    photoFileName.classList.remove(
                        'has-file'
                    );

                }

            }
        );

    }

});

</script>


@endsection