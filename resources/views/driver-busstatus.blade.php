@extends('layout.app')

@section('content')

<div class="maintenance-container">

    <!-- Page Header -->
    <section class="maintenance-header">
        <h1>Bus Availability Status</h1>
        <p>View the current availability of buses.</p>
    </section>

    <!-- Bus Status Summary -->
    <section class="bus-status-summary">
        <div class="status-card">
            <h2>Total Buses</h2>
            <p>{{ $totalBuses }}</p>
        </div>

        <div class="status-card">
            <h2>Available</h2>
            <p>{{ $availableCount }}</p>
        </div>

        <div class="status-card">
            <h2>Standby</h2>
            <p>{{ $standbyCount }}</p>
        </div>

        <div class="status-card">
            <h2>Under Maintenance</h2>
            <p>{{ $maintenanceCount }}</p>
        </div>

    </section>

    <!-- Bus Status Table -->
    <section class="maintenance-table-section">

        <h2>Bus Availability</h2>

        <table class="maintenance-table">

            <thead>
                <tr>
                    <th>Bus ID</th>
                    <th>Route</th>
                    <th>Availability Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($buses as $bus)

                    <tr>
                        <td>{{ $bus->bus_id }}</td>
                        <td>{{ $bus->route }}</td>
                        <td>{{ $bus->status }}</td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="3">No bus records available.</td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </section>

    <!-- Back to Homepage -->
    <a href="{{ url('/home') }}" class="btn-main">
        Back to Homepage
    </a>

</div>

@endsection