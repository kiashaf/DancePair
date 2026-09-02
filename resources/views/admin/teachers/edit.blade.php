@extends('admin.layout')

@section('title', 'Edit Teacher')
@section('page-title', 'Edit Teacher')

@section('content')

<div class="container py-3">

    <div class="row justify-content-center">

        <div class="col-xl-10">


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

                        @foreach(
                            $errors->all()
                            as $error
                        )

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif



            <form
                method="POST"
                action="{{ route(
                    'admin.teachers.update',
                    $teacher
                ) }}"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')


                {{-- =====================================================
                   ACCOUNT CONTROL
                ====================================================== --}}

                <div class="card profile-card p-4 mb-4">

                    <div
                        class="
                            d-flex
                            justify-content-between
                            align-items-center
                            mb-4
                        "
                    >

                        <div>

                            <h4 class="mb-1">
                                Account Control
                            </h4>

                            <small class="text-muted">
                                Administrative account settings.
                            </small>

                        </div>


                        <a
                            href="{{ route(
                                'admin.teachers'
                            ) }}"
                            class="btn btn-outline-secondary"
                        >
                            Back
                        </a>

                    </div>


                    <div class="row g-3">


                        {{-- NAME --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old(
                                    'name',
                                    $teacher->user->name
                                ) }}"
                                required
                            >

                        </div>


                        {{-- EMAIL --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old(
                                    'email',
                                    $teacher->user->email
                                ) }}"
                                required
                            >

                        </div>


                        {{-- ROLE --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Role
                            </label>

                            <select
                                name="role"
                                class="form-select"
                            >

                                <option
                                    value="teacher"
                                    {{ old(
                                        'role',
                                        $teacher->user->role
                                    ) === 'teacher'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Teacher
                                </option>

                                <option
                                    value="student"
                                    {{ old(
                                        'role',
                                        $teacher->user->role
                                    ) === 'student'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Student
                                </option>

                            </select>

                        </div>


                        {{-- ACTIVE --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Account Status
                            </label>

                            <select
                                name="active"
                                class="form-select"
                            >

                                <option
                                    value="1"
                                    {{ (string) old(
                                        'active',
                                        (int) $teacher->user->active
                                    ) === '1'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    {{ (string) old(
                                        'active',
                                        (int) $teacher->user->active
                                    ) === '0'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>


                        {{-- VERIFIED --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Verification
                            </label>

                            <select
                                name="verified"
                                class="form-select"
                            >

                                <option
                                    value="1"
                                    {{ (string) old(
                                        'verified',
                                        (int) $teacher->verified
                                    ) === '1'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Verified
                                </option>

                                <option
                                    value="0"
                                    {{ (string) old(
                                        'verified',
                                        (int) $teacher->verified
                                    ) === '0'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Pending
                                </option>

                            </select>

                        </div>


                        {{-- CURRENCY --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Currency
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $teacher->currency ?? 'CAD' }}"
                                readonly
                            >

                        </div>


                        {{-- PASSWORD --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                autocomplete="new-password"
                                placeholder="Leave blank to keep current password"
                            >

                        </div>


                        {{-- CONFIRM --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Confirm New Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                autocomplete="new-password"
                                placeholder="Confirm new password"
                            >

                        </div>

                    </div>

                </div>



                {{-- =====================================================
                   MEDIA
                ====================================================== --}}

                <div class="card profile-card p-4 mb-4">

                    <h4 class="mb-4">
                        Profile Media
                    </h4>


                    <div class="row g-4">


                        {{-- PHOTO --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Profile Photo
                            </label>


                            @if(
                                $teacher->profile_photo
                            )

                                <div class="mb-3">

                                    <img
                                        src="{{ asset(
                                            'storage/' .
                                            $teacher->profile_photo
                                        ) }}"
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
                                JPG, PNG or WebP. Max 5 MB.
                            </small>

                        </div>


                        {{-- VIDEO --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Introduction Video
                            </label>


                            @if(
                                $teacher->intro_video
                            )

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
                                            src="{{ asset(
                                                'storage/' .
                                                $teacher->intro_video
                                            ) }}"
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
                                MP4, MOV or WebM. Max 50 MB.
                            </small>

                        </div>

                    </div>

                </div>



                {{-- =====================================================
                   TEACHER INFORMATION
                ====================================================== --}}

                <div class="card profile-card p-4 mb-4">

                    <h4 class="mb-4">
                        Teacher Information
                    </h4>


                    <div class="mb-3">

                        <label class="form-label">
                            Bio
                        </label>

                        <textarea
                            name="bio"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'bio',
                            $teacher->bio
                        ) }}</textarea>

                    </div>


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                Years of Experience
                            </label>

                            <input
                                type="number"
                                name="experience_years"
                                class="form-control"
                                min="0"
                                max="80"
                                value="{{ old(
                                    'experience_years',
                                    $teacher->experience_years
                                ) }}"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Default Hourly Rate
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    $
                                </span>

                                <input
                                    type="number"
                                    name="hourly_rate"
                                    class="form-control"
                                    step="0.01"
                                    min="0"
                                    value="{{ old(
                                        'hourly_rate',
                                        $teacher->hourly_rate
                                    ) }}"
                                >

                                <span class="input-group-text">
                                    CAD
                                </span>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =====================================================
                   LOCATION
                ====================================================== --}}

                <div class="card profile-card p-4 mb-4">

                    <h4 class="mb-4">
                        Location
                    </h4>


                    <div class="row g-3">


                        {{-- PROVINCE --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Province
                            </label>

                            <select
                                id="province"
                                name="province"
                                class="form-select"
                            >

                                <option value="">
                                    Select Province
                                </option>

                                @foreach(
                                    $provinces
                                    as $province
                                )

                                    <option
                                        value="{{ $province->id }}"
                                        {{ old(
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
                        <div class="col-md-4">

                            <label class="form-label">
                                City
                            </label>

                            <select
                                id="city"
                                name="city"
                                class="form-select"
                            >

                                <option value="">
                                    Select City
                                </option>

                                @foreach(
                                    $cities
                                    as $city
                                )

                                    <option
                                        value="{{ $city->name }}"
                                        {{ old(
                                            'city',
                                            $teacher->city
                                        ) === $city->name
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
                        <div class="col-md-4">

                            <label class="form-label">
                                Country
                            </label>

                            <input
                                type="text"
                                name="country"
                                class="form-control"
                                value="{{ old(
                                    'country',
                                    $teacher->country ?? 'Canada'
                                ) }}"
                            >

                        </div>

                    </div>

                </div>



                {{-- =====================================================
                   DANCE STYLES & PRICES
                ====================================================== --}}

                <div class="card profile-card p-4 mb-4">

                    <h4 class="mb-1">
                        Dance Styles & Hourly Rates
                    </h4>

                    <p class="text-muted mb-4">
                        Admin can modify every dance style offered by this teacher and its hourly price.
                    </p>


                    <div class="row g-3">

                        @foreach(
                            $danceStyles
                            as $style
                        )

                            @php

                                $teacherStyle =
                                    $teacher
                                        ->danceStyles
                                        ->firstWhere(
                                            'id',
                                            $style->id
                                        );

                                $isSelected =
                                    $teacherStyle !== null;

                                $currentRate =
                                    $teacherStyle
                                        ? $teacherStyle
                                            ->pivot
                                            ->hourly_rate
                                        : null;

                            @endphp


                            <div class="col-md-6">

                                <div
                                    style="
                                        border:1px solid #E2E8F0;
                                        border-radius:14px;
                                        padding:16px;
                                        background:#FAFCFE;
                                    "
                                >

                                    <div class="form-check mb-3">

                                        <input
                                            class="
                                                form-check-input
                                                dance-style-checkbox
                                            "
                                            type="checkbox"
                                            name="dance_styles[]"
                                            value="{{ $style->id }}"
                                            id="style_{{ $style->id }}"
                                            data-style-id="{{ $style->id }}"
                                            {{ old(
                                                'dance_styles'
                                            )
                                                ? (
                                                    in_array(
                                                        $style->id,
                                                        old(
                                                            'dance_styles',
                                                            []
                                                        )
                                                    )
                                                        ? 'checked'
                                                        : ''
                                                )
                                                : (
                                                    $isSelected
                                                        ? 'checked'
                                                        : ''
                                                )
                                            }}
                                        >

                                        <label
                                            class="
                                                form-check-label
                                                fw-semibold
                                            "
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

                                        <label class="form-label small">
                                            Hourly Rate
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
                                                    'dance_rates.' .
                                                    $style->id,
                                                    $currentRate
                                                ) }}"
                                            >

                                            <span class="input-group-text">
                                                CAD / hour
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>



                {{-- =====================================================
                   SAVE
                ====================================================== --}}

                <div class="d-flex gap-3">

                    <button
                        type="submit"
                        class="btn btn-dark px-5"
                    >
                        Save All Changes
                    </button>


                    <a
                        href="{{ route(
                            'admin.teachers'
                        ) }}"
                        class="
                            btn
                            btn-outline-secondary
                            px-4
                        "
                    >
                        Cancel
                    </a>

                </div>

            </form>



            {{-- =====================================================
               DANGER ZONE
            ====================================================== --}}

            <div
                class="mt-5 p-4"
                style="
                    background:#FFF7F7;
                    border:1px solid #FECACA;
                    border-radius:18px;
                "
            >

                <h5
                    style="
                        color:#991B1B;
                        font-weight:800;
                    "
                >
                    Danger Zone
                </h5>

                <p
                    style="
                        color:#7F1D1D;
                        font-size:12px;
                    "
                >
                    Permanently delete this teacher and related test data.
                    Use Inactive instead if you only want to temporarily block access.
                </p>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.teachers.destroy',
                        $teacher
                    ) }}"
                    onsubmit="
                        return confirm(
                            'Permanently delete this teacher? This cannot be undone.'
                        );
                    "
                >

                    @csrf
                    @method('DELETE')


                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Teacher Permanently
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | PROVINCE -> CITY
        |--------------------------------------------------------------------------
        */

        const provinceSelect =
            document.getElementById(
                'province'
            );

        const citySelect =
            document.getElementById(
                'city'
            );


        if (
            provinceSelect
            &&
            citySelect
        ) {
            provinceSelect.addEventListener(
                'change',
                function () {

                    const provinceId =
                        this.value;


                    citySelect.innerHTML =
                        '<option value="">Loading...</option>';

                    citySelect.disabled =
                        true;


                    if (!provinceId) {

                        citySelect.innerHTML =
                            '<option value="">Select province first</option>';

                        citySelect.disabled =
                            false;

                        return;
                    }


                    fetch(
                        `/teacher/cities/${provinceId}`
                    )
                        .then(
                            response =>
                                response.json()
                        )
                        .then(
                            cities => {

                                citySelect.innerHTML =
                                    '<option value="">Select city</option>';


                                cities.forEach(
                                    city => {

                                        const option =
                                            document.createElement(
                                                'option'
                                            );

                                        option.value =
                                            city.name;

                                        option.textContent =
                                            city.name;

                                        citySelect.appendChild(
                                            option
                                        );

                                    }
                                );


                                citySelect.disabled =
                                    false;
                            }
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DANCE STYLE PRICES
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.dance-style-checkbox'
            )
            .forEach(
                function (checkbox) {

                    function updateRateBox()
                    {
                        const styleId =
                            checkbox.dataset.styleId;

                        const rateBox =
                            document.getElementById(
                                'rate_box_' +
                                styleId
                            );

                        const rateInput =
                            document.getElementById(
                                'rate_' +
                                styleId
                            );


                        if (
                            checkbox.checked
                        ) {
                            rateBox.style.display =
                                'block';

                            rateInput.required =
                                true;
                        }
                        else {
                            rateBox.style.display =
                                'none';

                            rateInput.required =
                                false;
                        }
                    }


                    checkbox.addEventListener(
                        'change',
                        updateRateBox
                    );


                    updateRateBox();
                }
            );

    }
);

</script>

@endsection