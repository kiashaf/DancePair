@extends('teacher.layout')



@section('title', __('teacher.my_profile'))

@section('page-title', __('teacher.my_profile'))



@section('content')





<style>



/* =========================================================

   TEACHING OPTIONS

========================================================= */



.teaching-options-section {

    margin-bottom: 28px;

}



.teaching-options-title {

    margin-bottom: 6px;



    color: #1F2937;



    font-size: 14px;

    font-weight: 700;

}



.teaching-options-help {

    margin-bottom: 14px;



    color: #6B7280;



    font-size: 12px;

}



.teaching-options-grid {

    display: grid;



    grid-template-columns:

        repeat(

            3,

            minmax(0, 1fr)

        );



    gap: 12px;

}



.teaching-option {

    position: relative;

}



.teaching-option-input {

    position: absolute;



    opacity: 0;



    pointer-events: none;

}



.teaching-option-label {

    display: flex;



    align-items: flex-start;



    gap: 12px;



    width: 100%;

    min-height: 92px;



    padding: 16px;



    margin: 0;



    border: 1px solid #DCE5E0;

    border-radius: 12px;



    background: #FFFFFF;



    cursor: pointer;



    transition:

        border-color .18s ease,

        background .18s ease,

        box-shadow .18s ease;

}



.teaching-option-label:hover {

    border-color: #AFCFC0;



    background: #FAFCFB;

}



.teaching-option-check {

    display: flex;



    align-items: center;

    justify-content: center;



    flex: 0 0 auto;



    width: 20px;

    height: 20px;



    margin-top: 1px;



    border: 1.5px solid #CBD5E1;

    border-radius: 6px;



    background: #FFFFFF;



    color: #FFFFFF;



    font-size: 12px;

    font-weight: 800;



    transition:

        background .18s ease,

        border-color .18s ease;

}



.teaching-option-check::after {

    content: '✓';



    opacity: 0;

}



.teaching-option-content {

    min-width: 0;

}



.teaching-option-name {

    display: block;



    margin-bottom: 4px;



    color: #1F2937;



    font-size: 13px;

    font-weight: 700;

}



.teaching-option-description {

    display: block;



    color: #6B7280;



    font-size: 11px;

    line-height: 1.45;

}



.teaching-option-input:checked

+

.teaching-option-label {

    border-color: #78B497;



    background: #F3FAF6;



    box-shadow:

        0 0 0 1px

        rgba(

            23,

            120,

            77,

            .05

        );

}



.teaching-option-input:checked

+

.teaching-option-label

.teaching-option-check {

    border-color: #17784D;



    background: #17784D;

}



.teaching-option-input:checked

+

.teaching-option-label

.teaching-option-check::after {

    opacity: 1;

}



.teaching-options-error {

    margin-top: 9px;



    color: #B42318;



    font-size: 11px;

}



/* =========================================================

   RESPONSIVE

========================================================= */



@media(max-width: 767px) {



    .teaching-options-grid {

        grid-template-columns: 1fr;

    }



}



</style>







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



                            <li>

                                {{ $error }}

                            </li>



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

                                id="profile_photo"

                                class="form-control"

                                accept="image/*"

                                data-max-mb="5"

                            >



                            <div

                                id="profile_photo_size_error"

                                class="alert alert-danger mt-2 py-2"

                                style="display:none;"

                            ></div>





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

                                id="intro_video"

                                class="form-control"

                                accept="video/mp4,video/webm,video/quicktime"

                                data-max-mb="50"

                            >



                            <div

                                id="intro_video_size_error"

                                class="alert alert-danger mt-2 py-2"

                                style="display:none;"

                            ></div>





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

                                            old(

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

                    {{-- TEACHING OPTIONS --}}

                    {{-- ========================================= --}}



                    <div class="teaching-options-section">



                        <div class="teaching-options-title">



                            {{ __('teacher.teaching_options') }}



                        </div>





                        <div class="teaching-options-help">



                            {{ __('teacher.teaching_options_help') }}



                        </div>





                        <div class="teaching-options-grid">





                            {{-- FACE TO FACE --}}



                            <div class="teaching-option">



                                <input

                                    type="hidden"

                                    name="teaches_in_person"

                                    value="0"

                                >





                                <input

                                    type="checkbox"

                                    name="teaches_in_person"

                                    value="1"

                                    id="teaches_in_person"

                                    class="teaching-option-input"

                                    {{

                                        old(

                                            'teaches_in_person',

                                            $teacher->teaches_in_person

                                        )

                                            ? 'checked'

                                            : ''

                                    }}

                                >





                                <label

                                    for="teaches_in_person"

                                    class="teaching-option-label"

                                >



                                    <span class="teaching-option-check"></span>





                                    <span class="teaching-option-content">



                                        <span class="teaching-option-name">



                                            {{ __('teacher.face_to_face') }}



                                        </span>





                                        <span class="teaching-option-description">



                                            {{ __('teacher.face_to_face_help') }}



                                        </span>



                                    </span>



                                </label>



                            </div>







                            {{-- PUBLIC PLACE --}}



                            <div class="teaching-option">



                                <input

                                    type="hidden"

                                    name="teaches_public_place"

                                    value="0"

                                >





                                <input

                                    type="checkbox"

                                    name="teaches_public_place"

                                    value="1"

                                    id="teaches_public_place"

                                    class="teaching-option-input"

                                    {{

                                        old(

                                            'teaches_public_place',

                                            $teacher->teaches_public_place

                                        )

                                            ? 'checked'

                                            : ''

                                    }}

                                >





                                <label

                                    for="teaches_public_place"

                                    class="teaching-option-label"

                                >



                                    <span class="teaching-option-check"></span>





                                    <span class="teaching-option-content">



                                        <span class="teaching-option-name">



                                            {{ __('teacher.public_place') }}



                                        </span>





                                        <span class="teaching-option-description">



                                            {{ __('teacher.public_place_help') }}



                                        </span>



                                    </span>



                                </label>



                            </div>







                            {{-- ONLINE --}}



                            <div class="teaching-option">



                                <input

                                    type="hidden"

                                    name="teaches_online"

                                    value="0"

                                >





                                <input

                                    type="checkbox"

                                    name="teaches_online"

                                    value="1"

                                    id="teaches_online"

                                    class="teaching-option-input"

                                    {{

                                        old(

                                            'teaches_online',

                                            $teacher->teaches_online

                                        )

                                            ? 'checked'

                                            : ''

                                    }}

                                >





                                <label

                                    for="teaches_online"

                                    class="teaching-option-label"

                                >



                                    <span class="teaching-option-check"></span>





                                    <span class="teaching-option-content">



                                        <span class="teaching-option-name">



                                            {{ __('teacher.online') }}



                                        </span>





                                        <span class="teaching-option-description">



                                            {{ __('teacher.online_help') }}



                                        </span>



                                    </span>



                                </label>



                            </div>



                        </div>





                        @error('teaching_options')



                            <div class="teaching-options-error">



                                {{ $message }}



                            </div>



                        @enderror



                    </div>









                    {{-- ========================================= --}}

                    {{-- DANCE STYLES --}}

                    {{-- ========================================= --}}

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

                $isPendingCustomStyle =
                    (bool) ($style->pending ?? false)
                    &&
                    (int) ($style->submitted_by_teacher_id ?? 0)
                    ===
                    (int) $teacher->id;

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

                            @if($isPendingCustomStyle)

                                <span class="badge bg-warning text-dark ms-2">

                                    {{ app()->getLocale() === 'fr'
                                        ? 'En attente'
                                        : 'Pending'
                                    }}

                                </span>

                            @endif

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


        @php

            $oldCustomDanceStyles =
                old(
                    'custom_dance_styles',
                    []
                );

            $hasOldCustomDanceStyles =
                collect(
                    $oldCustomDanceStyles
                )->contains(
                    function ($row) {

                        return
                            trim(
                                (string) (
                                    $row['name']
                                    ?? ''
                                )
                            )
                            !==
                            ''
                            ||
                            (
                                $row['rate']
                                ?? ''
                            )
                            !==
                            '';
                    }
                );

        @endphp


        <div class="col-12">

            <div class="border rounded p-3 bg-light">

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="custom_dance_style_toggle"
                        {{ $hasOldCustomDanceStyles ? 'checked' : '' }}
                    >

                    <label
                        class="form-check-label fw-semibold"
                        for="custom_dance_style_toggle"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Autre / Style non répertorié'
                            : 'Other / Style not listed'
                        }}

                    </label>

                </div>


                <div
                    id="custom_dance_style_fields"
                    class="mt-3"
                    @if(!$hasOldCustomDanceStyles)
                        style="display:none;"
                    @endif
                >

                    <div id="custom_dance_styles_container">

                        @if($hasOldCustomDanceStyles)

                            @foreach(
                                $oldCustomDanceStyles
                                as $customIndex => $customDanceStyle
                            )

                                <div
                                    class="custom-dance-style-row border rounded p-3 mb-3"
                                    data-custom-style-row
                                >

                                    <div class="row g-3 align-items-end">

                                        <div class="col-md-6">

                                            <label class="form-label">

                                                {{ app()->getLocale() === 'fr'
                                                    ? 'Nom du style de danse'
                                                    : 'Dance Style Name'
                                                }}

                                            </label>

                                            <input
                                                type="text"
                                                name="custom_dance_styles[{{ $customIndex }}][name]"
                                                class="form-control custom-dance-style-name"
                                                value="{{ $customDanceStyle['name'] ?? '' }}"
                                                maxlength="255"
                                                placeholder="{{ app()->getLocale() === 'fr'
                                                    ? 'Ex. Afro Fusion'
                                                    : 'e.g. Afro Fusion'
                                                }}"
                                            >

                                        </div>


                                        <div class="col-md-5">

                                            <label class="form-label">
                                                {{ __('teacher.hourly_rate') }}
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">
                                                    $
                                                </span>

                                                <input
                                                    type="number"
                                                    name="custom_dance_styles[{{ $customIndex }}][rate]"
                                                    class="form-control custom-dance-style-rate"
                                                    value="{{ $customDanceStyle['rate'] ?? '' }}"
                                                    step="0.01"
                                                    min="0"
                                                    max="99999.99"
                                                    placeholder="0.00"
                                                >

                                                <span class="input-group-text">
                                                    CAD / {{ __('teacher.hour') }}
                                                </span>

                                            </div>

                                        </div>


                                        <div class="col-md-1">

                                            <button
                                                type="button"
                                                class="btn btn-outline-danger btn-sm w-100"
                                                data-remove-custom-style
                                            >
                                                ×
                                            </button>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        @endif

                    </div>


                    <button
                        type="button"
                        id="add_custom_dance_style"
                        class="btn btn-outline-primary btn-sm"
                    >

                        + {{ app()->getLocale() === 'fr'
                            ? 'Ajouter un autre style'
                            : 'Add another style'
                        }}

                    </button>

                </div>

            </div>

        </div>

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



            {{-- @include('profiles.secondary') --}}



            {{-- ========================================= --}}

{{-- PAYOUT ACCOUNT --}}

{{-- ========================================= --}}



<div class="card profile-card p-4 mb-4">



    <h4 class="mb-3">

        {{ app()->getLocale() === 'fr'

            ? 'Compte de versement'

            : 'Payout Account'

        }}

    </h4>



    <p class="text-muted mb-4">

        {{ app()->getLocale() === 'fr'

            ? 'Connectez votre compte de versement pour recevoir vos revenus de DancePair en toute sécurité.'

            : 'Connect your payout account to securely receive your DancePair earnings.'

        }}

    </p>



    @if($teacher->stripe_payouts_enabled)



        <div class="alert alert-success mb-0">



            <strong>

                ✓

                {{ app()->getLocale() === 'fr'

                    ? 'Compte connecté'

                    : 'Account connected'

                }}

            </strong>



            <div class="mt-1">

                {{ app()->getLocale() === 'fr'

                    ? 'Votre compte est prêt à recevoir des versements.'

                    : 'Your account is ready to receive payouts.'

                }}

            </div>



        </div>



    @else



        <a

            href="{{ route('teacher.stripe.connect') }}"

            class="btn btn-primary"

        >

            {{ app()->getLocale() === 'fr'

                ? 'Connecter mon compte'

                : 'Connect payout account'

            }}

        </a>



    @endif



</div>

        </div>



    </div>



</div>









<script>



document.addEventListener(

    'DOMContentLoaded',

    function () {



        const provinceSelect =

            document.getElementById(

                'province'

            );



        const citySelect =

            document.getElementById(

                'city'

            );





        provinceSelect.addEventListener(

            'change',

            function () {



                const provinceId =

                    this.value;





                citySelect.innerHTML =

                    '<option value="">{{ __("teacher.loading") }}</option>';



                citySelect.disabled =

                    true;





                if (!provinceId) {



                    citySelect.innerHTML =

                        '<option value="">{{ __("teacher.select_province_first") }}</option>';



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

                                '<option value="">{{ __("teacher.select_city") }}</option>';





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

                    )

                    .catch(

                        error => {



                            console.error(

                                error

                            );



                            citySelect.innerHTML =

                                '<option value="">{{ __("teacher.unable_load_cities") }}</option>';



                            citySelect.disabled =

                                false;

                        }

                    );

            }

        );





        document

            .querySelectorAll(

                '.dance-style-checkbox'

            )

            .forEach(

                function (

                    checkbox

                ) {



                    checkbox.addEventListener(

                        'change',

                        function () {



                            const styleId =

                                this.dataset.styleId;



                            const rateBox =

                                document.getElementById(

                                    'rate_box_'

                                    +

                                    styleId

                                );



                            const rateInput =

                                document.getElementById(

                                    'rate_'

                                    +

                                    styleId

                                );





                            if (this.checked) {



                                rateBox.style.display =

                                    'block';



                                rateInput.required =

                                    true;



                            } else {



                                rateBox.style.display =

                                    'none';



                                rateInput.required =

                                    false;

                            }

                        }

                    );





                    const styleId =

                        checkbox.dataset.styleId;



                    const rateInput =

                        document.getElementById(

                            'rate_'

                            +

                            styleId

                        );



                    rateInput.required =

                        checkbox.checked;

                }

            );


            const customDanceStyleToggle =
    document.getElementById(
        'custom_dance_style_toggle'
    );

const customDanceStyleFields =
    document.getElementById(
        'custom_dance_style_fields'
    );

const customDanceStylesContainer =
    document.getElementById(
        'custom_dance_styles_container'
    );

const addCustomDanceStyleButton =
    document.getElementById(
        'add_custom_dance_style'
    );

let customDanceStyleIndex =
    customDanceStylesContainer
        ? customDanceStylesContainer
            .querySelectorAll(
                '[data-custom-style-row]'
            )
            .length
        : 0;


const isFrench =
    document.documentElement.lang
        .toLowerCase()
        .startsWith('fr');


function createCustomDanceStyleRow() {

    const index =
        customDanceStyleIndex++;

    const row =
        document.createElement(
            'div'
        );

    row.className =
        'custom-dance-style-row border rounded p-3 mb-3';

    row.setAttribute(
        'data-custom-style-row',
        ''
    );

    row.innerHTML = `

        <div class="row g-3 align-items-end">

            <div class="col-md-6">

                <label class="form-label">
                    ${isFrench
                        ? 'Nom du style de danse'
                        : 'Dance Style Name'
                    }
                </label>

                <input
                    type="text"
                    name="custom_dance_styles[${index}][name]"
                    class="form-control custom-dance-style-name"
                    maxlength="255"
                    placeholder="${isFrench
                        ? 'Ex. Afro Fusion'
                        : 'e.g. Afro Fusion'
                    }"
                >

            </div>


            <div class="col-md-5">

                <label class="form-label">
                    ${isFrench
                        ? 'Tarif horaire'
                        : 'Hourly Rate'
                    }
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        $
                    </span>

                    <input
                        type="number"
                        name="custom_dance_styles[${index}][rate]"
                        class="form-control custom-dance-style-rate"
                        step="0.01"
                        min="0"
                        max="99999.99"
                        placeholder="0.00"
                    >

                    <span class="input-group-text">
                        CAD / ${isFrench
                            ? 'heure'
                            : 'hour'
                        }
                    </span>

                </div>

            </div>


            <div class="col-md-1">

                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm w-100"
                    data-remove-custom-style
                >
                    ×
                </button>

            </div>

        </div>
    `;

    customDanceStylesContainer
        .appendChild(
            row
        );
}


function ensureCustomRow() {

    if (
        !customDanceStylesContainer
            .querySelector(
                '[data-custom-style-row]'
            )
    ) {

        createCustomDanceStyleRow();

    }
}


if (customDanceStyleToggle) {

    customDanceStyleToggle
        .addEventListener(
            'change',
            function () {

                if (this.checked) {

                    customDanceStyleFields
                        .style
                        .display =
                        'block';

                    ensureCustomRow();

                } else {

                    customDanceStyleFields
                        .style
                        .display =
                        'none';

                    customDanceStylesContainer
                        .innerHTML =
                        '';

                    customDanceStyleIndex =
                        0;

                }

            }
        );

}


if (addCustomDanceStyleButton) {

    addCustomDanceStyleButton
        .addEventListener(
            'click',
            function () {

                createCustomDanceStyleRow();

            }
        );

}


if (customDanceStylesContainer) {

    customDanceStylesContainer
        .addEventListener(
            'click',
            function (event) {

                const removeButton =
                    event.target.closest(
                        '[data-remove-custom-style]'
                    );

                if (!removeButton) {
                    return;
                }

                const row =
                    removeButton.closest(
                        '[data-custom-style-row]'
                    );

                if (row) {

                    row.remove();

                }

                if (
                    customDanceStyleToggle
                    &&
                    customDanceStyleToggle.checked
                ) {

                    ensureCustomRow();

                }

            }
        );

}
        const profilePhotoInput =

            document.getElementById(

                'profile_photo'

            );





        const introVideoInput =

            document.getElementById(

                'intro_video'

            );





        const profilePhotoError =

            document.getElementById(

                'profile_photo_size_error'

            );





        const introVideoError =

            document.getElementById(

                'intro_video_size_error'

            );





        function checkFileSize(

            input,

            errorBox,

            fileType

        ) {



            if (

                !input

                ||

                !input.files

                ||

                !input.files.length

            ) {



                return;

            }





            const file =

                input.files[0];





            const maxMb =

                parseFloat(

                    input.dataset.maxMb

                );





            const maxBytes =

                maxMb * 1024 * 1024;





            if (file.size > maxBytes) {



                const isFrench =

                    document.documentElement.lang

                        .toLowerCase()

                        .startsWith('fr');





                if (fileType === 'video') {



                    errorBox.textContent =

                        isFrench

                            ? `Cette vidéo est trop volumineuse. Veuillez choisir une vidéo de moins de ${maxMb} Mo.`

                            : `This video is too large. Please choose a video smaller than ${maxMb} MB.`;



                } else {



                    errorBox.textContent =

                        isFrench

                            ? `Cette photo est trop volumineuse. Veuillez choisir une photo de moins de ${maxMb} Mo.`

                            : `This photo is too large. Please choose a photo smaller than ${maxMb} MB.`;

                }





                errorBox.style.display =

                    'block';





                input.classList.add(

                    'is-invalid'

                );





                input.value =

                    '';





                return;

            }





            errorBox.style.display =

                'none';





            errorBox.textContent =

                '';





            input.classList.remove(

                'is-invalid'

            );

        }





        if (profilePhotoInput) {



            profilePhotoInput.addEventListener(

                'change',

                function () {



                    checkFileSize(

                        this,

                        profilePhotoError,

                        'photo'

                    );

                }

            );

        }





        if (introVideoInput) {



            introVideoInput.addEventListener(

                'change',

                function () {



                    checkFileSize(

                        this,

                        introVideoError,

                        'video'

                    );

                }

            );

        }





    }

);



</script>



@endsection