@extends('admin.layout')

@section('title', 'Teachers')
@section('page-title', 'Teachers')

@section('content')

<style>

.teacher-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 8px;
}

.admin-delete-btn {
    border: 1px solid #DC2626;
    border-radius: 9px;

    padding: 8px 14px;

    background: #FFFFFF;
    color: #DC2626;

    font-size: 12px;
    font-weight: 700;

    cursor: pointer;

    transition: .15s ease;
}

.admin-delete-btn:hover {
    background: #DC2626;
    color: #FFFFFF;
}

.teacher-actions form {
    margin: 0;
}

</style>


<div class="admin-page-card">

    <div class="admin-page-header">

        <div>

            <h3 class="mb-1">
                Teachers
            </h3>

            <p class="text-muted mb-0">
                Search and manage registered teachers.
            </p>

        </div>

        <div class="admin-count">
            {{ $teachers->count() }} Teachers
        </div>

    </div>


    <div class="admin-toolbar">

        <div class="admin-search-wrap">

            <input
                type="text"
                id="teacherSearch"
                class="admin-search-input"
                placeholder="Search by name, email, city, province or dance style..."
            >

        </div>

    </div>


    <div class="table-responsive">

        <table
            class="admin-teachers-table"
            id="teachersTable"
        >

            <thead>

                <tr>

                    <th>
                        Teacher
                    </th>

                    <th>
                        Location
                    </th>

                    <th>
                        Experience
                    </th>

                    <th>
                        Hourly Rate
                    </th>

                    <th class="text-end">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($teachers as $teacher)

                    <tr class="teacher-row">


                        {{-- TEACHER --}}
                        <td>

                            <div class="teacher-person">

                                <div class="teacher-avatar">

                                    {{ strtoupper(
                                        substr(
                                            $teacher->user->name ?? 'T',
                                            0,
                                            1
                                        )
                                    ) }}

                                </div>


                                <div>

                                    <div class="teacher-name">

                                        {{ $teacher->user->name ?? '—' }}

                                    </div>


                                    <div class="teacher-email">

                                        {{ $teacher->user->email ?? '—' }}

                                    </div>

                                </div>

                            </div>

                        </td>


                        {{-- LOCATION --}}
                        <td>

                            <div class="teacher-location">

                                {{ $teacher->city ?? '—' }}

                                @if($teacher->province)

                                    <span>
                                        {{ $teacher->province }}
                                    </span>

                                @endif

                            </div>

                        </td>


                        {{-- EXPERIENCE --}}
                        <td>

                            <span class="teacher-value">

                                {{ $teacher->experience_years ?? 0 }}

                                {{ ($teacher->experience_years ?? 0) == 1
                                    ? 'year'
                                    : 'years'
                                }}

                            </span>

                        </td>


                        {{-- RATE --}}
                        <td>

                            <span class="teacher-value">

                                ${{ number_format(
                                    (float) ($teacher->hourly_rate ?? 0),
                                    2
                                ) }}

                            </span>

                        </td>


                        {{-- ACTIONS --}}
                        <td class="text-end">

                            <div class="teacher-actions">


                                {{-- EDIT --}}
                                <a
                                    href="{{ route(
                                        'admin.teachers.edit',
                                        $teacher
                                    ) }}"
                                    class="admin-edit-btn"
                                >
                                    Edit
                                </a>


                                {{-- DELETE --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                                        'admin.teachers.destroy',
                                        $teacher
                                    ) }}"
                                    onsubmit="
                                        return confirm(
                                            'Are you sure you want to permanently delete {{ addslashes($teacher->user->name ?? 'this teacher') }}?\n\nThis will also delete related test bookings, payments, reviews and availability.\n\nThis action cannot be undone.'
                                        );
                                    "
                                >

                                    @csrf
                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="admin-delete-btn"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center py-5 text-muted"
                        >
                            No teachers found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const searchInput =
            document.getElementById(
                'teacherSearch'
            );

        const rows =
            document.querySelectorAll(
                '.teacher-row'
            );


        if (!searchInput) {
            return;
        }


        searchInput.addEventListener(
            'input',
            function () {

                const value =
                    this.value
                        .toLowerCase()
                        .trim();


                rows.forEach(
                    function (row) {

                        const text =
                            row.innerText
                                .toLowerCase();


                        row.style.display =
                            text.includes(value)
                                ? ''
                                : 'none';

                    }
                );

            }
        );

    }
);

</script>

@endsection