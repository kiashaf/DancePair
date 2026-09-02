@extends('admin.layout')

@section('title', 'Students')
@section('page-title', 'Students')

@section('content')

<div class="admin-page-card">

    <div class="admin-page-header">

        <div>
            <h3 class="mb-1">Students</h3>

            <p class="text-muted mb-0">
                Search and manage registered students.
            </p>
        </div>

        <div class="admin-count">
            {{ $students->count() }} Students
        </div>

    </div>


    <div class="admin-toolbar">

        <div class="admin-search-wrap">

            <input
                type="text"
                id="studentSearch"
                class="admin-search-input"
                placeholder="Search by name or email..."
            >

        </div>

    </div>


    <div class="table-responsive">

        <table class="admin-teachers-table">

            <thead>
                <tr>
                    <th>Student</th>
                    <th>Email</th>
                    <th>Account</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>

                @forelse($students as $student)

                    <tr class="student-row">

                        <td>

                            <div class="teacher-person">

                                <div class="teacher-avatar">
                                    {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
                                </div>

                                <div class="teacher-name">
                                    {{ $student->user->name ?? '—' }}
                                </div>

                            </div>

                        </td>


                        <td>
                            {{ $student->user->email ?? '—' }}
                        </td>


                        <td>
                            <span class="teacher-value">
                                Student
                            </span>
                        </td>


                        <td class="text-end">

                            <a
                                href="{{ route('admin.students.edit', $student) }}"
                                class="admin-edit-btn"
                            >
                                Edit
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4"
                            class="text-center py-5 text-muted">

                            No students found.

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('studentSearch');
    const rows = document.querySelectorAll('.student-row');

    searchInput.addEventListener('input', function () {

        const value = this.value.toLowerCase().trim();

        rows.forEach(function (row) {

            const text = row.innerText.toLowerCase();

            row.style.display = text.includes(value)
                ? ''
                : 'none';

        });

    });

});
</script>

@endsection