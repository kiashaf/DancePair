@extends('admin.layout')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student')

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
                action="{{ route(
                    'admin.students.update',
                    $student
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
                                'admin.students'
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
                                    $student->user->name
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
                                    $student->user->email
                                ) }}"
                                required
                            >

                        </div>


                        {{-- ROLE --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Role
                            </label>

                            <select
                                name="role"
                                class="form-select"
                                required
                            >

                                <option
                                    value="student"
                                    {{ old(
                                        'role',
                                        $student->user->role
                                    ) === 'student'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Student
                                </option>

                                <option
                                    value="teacher"
                                    {{ old(
                                        'role',
                                        $student->user->role
                                    ) === 'teacher'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Teacher
                                </option>

                            </select>

                        </div>


                        {{-- ACTIVE --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Account Status
                            </label>

                            <select
                                name="active"
                                class="form-select"
                                required
                            >

                                <option
                                    value="1"
                                    {{ (string) old(
                                        'active',
                                        (int) $student->user->active
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
                                        (int) $student->user->active
                                    ) === '0'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>


                        {{-- DANCE EXPERIENCE --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Dance Experience
                            </label>

                            <select
                                name="has_dance_experience"
                                class="form-select"
                                required
                            >

                                <option
                                    value="1"
                                    {{ (string) old(
                                        'has_dance_experience',
                                        (int) $student->has_dance_experience
                                    ) === '1'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Yes
                                </option>

                                <option
                                    value="0"
                                    {{ (string) old(
                                        'has_dance_experience',
                                        (int) $student->has_dance_experience
                                    ) === '0'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    No
                                </option>

                            </select>

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


                        {{-- CONFIRM PASSWORD --}}
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
                   PROFILE PHOTO
                ====================================================== --}}

                <div class="card profile-card p-4 mb-4">

                    <h4 class="mb-4">
                        Profile Photo
                    </h4>


                    @if($student->profile_photo)

                        <div class="mb-3">

                            <img
                                src="{{ asset(
                                    'storage/' .
                                    $student->profile_photo
                                ) }}"
                                alt="Student Profile"
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



                {{-- =====================================================
                   STUDENT INFORMATION
                ====================================================== --}}

                <div class="card profile-card p-4 mb-4">

                    <h4 class="mb-4">
                        Student Information
                    </h4>


                    <div class="row g-3">


                        {{-- BIRTH DATE --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Birth Date
                            </label>

                            <input
                                type="date"
                                name="birth_date"
                                class="form-control"
                                value="{{ old(
                                    'birth_date',
                                    optional(
                                        $student->birth_date
                                    )->format('Y-m-d')
                                ) }}"
                            >

                        </div>


                        {{-- GENDER --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Gender
                            </label>

                            <select
                                name="gender"
                                class="form-select"
                            >

                                <option value="">
                                    Select Gender
                                </option>

                                <option
                                    value="male"
                                    {{ old(
                                        'gender',
                                        $student->gender
                                    ) === 'male'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Male
                                </option>

                                <option
                                    value="female"
                                    {{ old(
                                        'gender',
                                        $student->gender
                                    ) === 'female'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Female
                                </option>

                                <option
                                    value="other"
                                    {{ old(
                                        'gender',
                                        $student->gender
                                    ) === 'other'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Other
                                </option>

                                <option
                                    value="prefer_not_to_say"
                                    {{ old(
                                        'gender',
                                        $student->gender
                                    ) === 'prefer_not_to_say'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Prefer not to say
                                </option>

                            </select>

                        </div>


                        {{-- EXPERIENCE LEVEL --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Experience Level
                            </label>

                            <select
                                name="experience_level"
                                class="form-select"
                            >

                                <option value="">
                                    Select Level
                                </option>

                                <option
                                    value="beginner"
                                    {{ old(
                                        'experience_level',
                                        $student->experience_level
                                    ) === 'beginner'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Beginner
                                </option>

                                <option
                                    value="intermediate"
                                    {{ old(
                                        'experience_level',
                                        $student->experience_level
                                    ) === 'intermediate'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Intermediate
                                </option>

                                <option
                                    value="advanced"
                                    {{ old(
                                        'experience_level',
                                        $student->experience_level
                                    ) === 'advanced'
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    Advanced
                                </option>

                            </select>

                        </div>



                        {{-- CITY --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                City
                            </label>

                            <input
                                type="text"
                                name="city"
                                class="form-control"
                                value="{{ old(
                                    'city',
                                    $student->city
                                ) }}"
                            >

                        </div>


                        {{-- PROVINCE --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Province
                            </label>

                            <input
                                type="text"
                                name="province"
                                class="form-control"
                                value="{{ old(
                                    'province',
                                    $student->province
                                ) }}"
                            >

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
                                    $student->country ?? 'Canada'
                                ) }}"
                            >

                        </div>


                        {{-- BIO --}}
                        <div class="col-12">

                            <label class="form-label">
                                Bio
                            </label>

                            <textarea
                                name="bio"
                                class="form-control"
                                rows="5"
                            >{{ old(
                                'bio',
                                $student->bio
                            ) }}</textarea>

                        </div>

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
                            'admin.students'
                        ) }}"
                        class="btn btn-outline-secondary px-4"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection