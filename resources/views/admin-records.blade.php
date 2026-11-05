
@extends('layout.app')

@section('content')

<div class="maintenance-container">

    <section class="maintenance-header">
        <h1>Maintenance Records History</h1>
        <p>View previous and completed bus maintenance records.</p>
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

    <!-- Records Table -->
    <section class="maintenance-table-section">
        <h2>Maintenance History</h2>

        <table class="maintenance-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Bus ID</th>
                    <th>Details</th>
                    <th>Date</th>
                    <th>Remarks</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($reports as $report)
                    <tr>
                        <td>{{ $report->report_id }}</td>
                        <td>{{ $report->bus_id }}</td>
                        <td>{{ $report->description }}</td>
                        <td>{{ $report->report_date->format('d/m/Y') }}</td>
                        <td>
                            @if ($report->status === 'Rejected')
                                Rejected: {{ $report->rejection_reason }}
                            @else
                                Completed
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">
                            No maintenance records available.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

</div>

@endsection
