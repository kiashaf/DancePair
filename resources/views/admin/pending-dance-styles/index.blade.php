@extends('admin.layout')

@section('title', 'Pending Dance Styles')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                Pending Dance Styles
            </h2>

            <p class="text-muted mb-0">
                Review dance styles submitted by instructors.
            </p>
        </div>

        <span class="badge bg-warning text-dark fs-6">
            {{ $pendingDanceStyles->count() }} Pending
        </span>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-body p-0">

            @if($pendingDanceStyles->isEmpty())

                <div class="text-center py-5">

                    <h5 class="mb-2">
                        No pending dance styles
                    </h5>

                    <p class="text-muted mb-0">
                        There are currently no instructor submissions waiting for approval.
                    </p>

                </div>

            @else

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Dance Style
                                </th>

                                <th>
                                    Instructor
                                </th>

                                <th>
                                    Hourly Rate
                                </th>

                                <th>
                                    Submitted
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($pendingDanceStyles as $style)

                                @php

                                    $submittingTeacher =
                                        $style->submittedByTeacher;

                                    $teacherForStyle =
                                        $style
                                            ->teachers
                                            ->firstWhere(
                                                'id',
                                                $style->submitted_by_teacher_id
                                            );

                                    $hourlyRate =
                                        $teacherForStyle
                                            ?->pivot
                                            ?->hourly_rate;

                                @endphp


                                <tr>

                                    <td>

                                        <strong>
                                            {{ $style->name }}
                                        </strong>

                                        <div class="mt-1">

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                        </div>

                                    </td>


                                    <td>

                                        @if($submittingTeacher)

                                            <div class="fw-semibold">

                                                {{ $submittingTeacher->user?->name ?? 'Instructor' }}

                                            </div>

                                            @if($submittingTeacher->user?->email)

                                                <small class="text-muted">

                                                    {{ $submittingTeacher->user->email }}

                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                Unknown instructor
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($hourlyRate !== null)

                                            <strong>

                                                ${{ number_format(
                                                    (float) $hourlyRate,
                                                    2
                                                ) }}

                                            </strong>

                                            <span class="text-muted">
                                                CAD / hour
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $style->created_at
                                            ?->format('Y-m-d H:i')
                                            ?? '—'
                                        }}

                                    </td>


                                    <td class="text-end">

                                        <div
                                            class="d-flex justify-content-end gap-2"
                                        >

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.pending-dance-styles.approve',
                                                    $style
                                                ) }}"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-success btn-sm"
                                                >
                                                    Approve
                                                </button>

                                            </form>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.pending-dance-styles.reject',
                                                    $style
                                                ) }}"
                                                onsubmit="return confirm(
                                                    'Reject this dance style?'
                                                );"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-danger btn-sm"
                                                >
                                                    Reject
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection