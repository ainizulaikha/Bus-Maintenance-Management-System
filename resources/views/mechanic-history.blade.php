
@extends('layout.app')

@section('content')

<div class="maintenance-container">

    <section class="maintenance-header">
        <h1>Maintenance History</h1>
        <p>View your completed maintenance tasks and repair records.</p>
    </section>

    <nav class="task-nav">
        <a href="{{ url('/mechanic-maintenance') }}">All Tasks</a>
        <a href="{{ url('/mechanic-tostart') }}">To Start</a>
        <a href="{{ url('/mechanic-inprogress') }}">In Progress</a>
        <a href="{{ url('/mechanic-history') }}">History</a>
    </nav>

    <section class="assigned-tasks">
        <h2>Completed Maintenance Records</h2>

        <table class="maintenance-table">
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Bus ID</th>
                    <th>Task Details</th>
                    <th>Completion Date</th>
                    <th>Status</th>
                    <th>Repair Notes</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($reports as $task)
                    <tr>
                        <td>{{ $task->report_id }}</td>
                        <td>{{ $task->bus_id }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->updated_at->format('d/m/Y') }}</td>
                        <td>
                            <span class="task-status">{{ $task->status }}</span>
                        </td>
                        <td>
                            {{ $task->repair_notes ?: 'No repair notes recorded.' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            No completed maintenance records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

</div>

@endsection
