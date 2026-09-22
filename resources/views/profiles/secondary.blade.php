@php
    $account = auth()->user();

    $source = $account?->role;

    $target = $source === 'student'
        ? 'teacher'
        : ($source === 'teacher' ? 'student' : null);

    $hasSecondaryProfile = false;

    if ($target === 'teacher') {
        $hasSecondaryProfile = \App\Models\Teacher::where(
            'user_id',
            $account->id
        )->exists();
    }

    if ($target === 'student') {
        $hasSecondaryProfile = \App\Models\Student::where(
            'user_id',
            $account->id
        )->exists();
    }
@endphp

@if($target)

    <div class="card profile-card p-4 mb-4">

        <h4 class="mb-2">
            {{ __('profile_access.' . $target . '_title') }}
        </h4>

        <p class="text-muted mb-3">
            {{ __('profile_access.' . $target . '_description') }}
        </p>

        @if($hasSecondaryProfile)

            <div class="d-flex flex-wrap gap-2">

                <a
                    href="{{ route($target . '.profile.edit') }}"
                    class="btn btn-outline-primary"
                >
                    {{ __('profile_access.edit_' . $target) }}
                </a>

                <a
                    href="{{ route($target . '.dashboard') }}"
                    class="btn btn-outline-secondary"
                >
                    {{ __('profile_access.' . $target . '_dashboard') }}
                </a>

            </div>

        @else

            <form
                method="POST"
                action="{{ route($source . '.profile.create-' . $target) }}"
            >
                @csrf

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    {{ __('profile_access.create_' . $target) }}
                </button>

            </form>

        @endif

    </div>

@endif