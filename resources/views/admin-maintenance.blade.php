
@extends('layout.app')

@section('content')

<div class="maintenance-container">

    <section class="maintenance-header">
        <h1>Maintenance Management</h1>
        <p>Monitor and manage bus maintenance activities.</p>
    </section>

    <!-- Maintenance Navigation -->
    <nav class="maintenance-nav">
        <a href="{{ url('/admin-maintenance') }}">Maintenance</a>
        <a href="{{ url('/admin-request') }}" class="request-link">Requests
            <span class="nav-count">
                {{ \App\Models\BreakdownReport::where('status', 'Pending')->count() }}
            </span></a>
        <a href="{{ url('/admin-records') }}">Records History</a>
        <a href="{{ url('/admin-generate') }}">Generate Report</a>
    </nav>

    <!-- Maintenance Table -->
    <section class="maintenance-table-section">
        <h2>Ongoing Maintenance</h2>

        <table class="maintenance-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Bus ID</th>
                    <th>Maintenance Details</th>
                    <th>Mechanic</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($reports as $report)
                    <tr>
                        <td>{{ $report->report_id }}</td>
                        <td>{{ $report->bus_id }}</td>
                        <td>{{ $report->description }}</td>
                        <td>
                            @php
                                $mechanic = \App\Models\User::where('user_id', $report->assigned_mechanic_id)->first();
                            @endphp

                            {{ $mechanic ? $mechanic->name . ' (' . $mechanic->user_id . ')' : 'Not Assigned' }}
                        </td>
                        <td>{{ $report->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">
                            No ongoing maintenance tasks.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

</div>

@endsection
