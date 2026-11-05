
@extends('layout.app')

@section('content')

<div class="maintenance-container">
    <section class="maintenance-header">
        <h1>My Maintenance Status</h1>
        <p>View the progress of your submitted breakdown report.</p>
    </section>

    <!-- Bus Information -->
    <section class="driver-bus-info">
        <h2>Bus Information</h2>

        <p><strong>Bus ID:</strong> {{ $report->bus_id }}</p>
        <p><strong>Route:</strong>
            {{ \App\Models\Bus::where('bus_id', $report->bus_id)->value('route') ?? 'Not available' }}
        </p>
        <p><strong>Report ID:</strong> {{ $report->report_id }}</p>
        <p><strong>Problem:</strong> {{ $report->description }}</p>
        <p><strong>Report Date:</strong> {{ $report->report_date->format('d M Y') }}</p>
    </section>

    <!-- Maintenance Progress -->
    @php
        $stages = [
            1 => 'Report Submitted',
            2 => 'Request Approved',
            3 => 'In Progress',
            4 => 'Completed'
        ];

        $statusStages = [
            'Pending' => 1,
            'Approved' => 2,
            'In Progress' => 3,
            'Completed' => 4,
        ];

        $currentStage = $statusStages[$report->status] ?? 0;

        $busPosition = $currentStage > 0
            ? 12.5 + (($currentStage - 1) / 3) * 75
            : 12.5;
    @endphp

    <section class="driver-progress">
        <h2>Maintenance Progress</h2>

        @if ($report->status === 'Rejected')
            <p><strong>Current Status:</strong> Rejected</p>
            <p>{{ $report->rejection_reason }}</p>
        @else
            <p><strong>Current Status:</strong> {{ $report->status }}</p>

            <div class="bus-progress-card">
                <h3>Maintenance Journey</h3>

                <div class="bus-road-track">
                    <div class="road-centre-line"></div>

                    <div class="moving-bus" style="left: {{ $busPosition }}%">
                        🚌
                    </div>
                </div>

                <div class="stage-track">
                    @foreach ($stages as $number => $stage)
                        <div class="stage-item {{ $number < $currentStage ? 'done' : '' }} {{ $number == $currentStage ? 'current' : '' }}">

                            <span class="stage-circle">{{ $number }}</span>
                            <span class="stage-label">{{ $stage }}</span>

                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <!-- Maintenance Details -->
    <section class="driver-repair-details">
        <h2>Maintenance Details</h2>

        @if ($report->repair_notes)
            <p>{{ $report->repair_notes }}</p>
        @else
            <p>No maintenance notes have been added yet.</p>
        @endif
    </section>

    <a href="{{ url('/home') }}" class="btn-main">
        Back to Homepage
    </a>
</div>

@endsection
