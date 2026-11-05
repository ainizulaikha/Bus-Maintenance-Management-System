
@extends('layout.app')

@section('content')

<div class="maintenance-container">

    <!-- Page Header -->
    <section class="maintenance-header">
        <h1>Check Maintenance Status</h1>
        <p>Enter your report ID to view the maintenance progress of your bus.</p>
    </section>

    <!-- Enter Report ID Form -->
    <section class="report-form-section">

        <h2>Find Your Maintenance Report</h2>

        <p>
            Please enter the Report ID you received after submitting
            your bus breakdown report.
        </p>

        <form method="GET" action="{{ url('/maintenance-status') }}">

            <div class="form-group">
                <label for="report_id">Report ID</label>

                <input
                    type="text"
                    name="report_id"
                    id="report_id"
                    placeholder="e.g. RP0000"
                    value="{{ old('report_id', request('report_id')) }}"
                    required
                >
            </div>

            <button type="submit">Check Status</button>

        </form>

    </section>

    @if(isset($report))

    <section class="report-status-section">

        <h2>Maintenance Report Details</h2>

        <div class="report-details">
            <div class="detail-item">
                <span>Report ID</span>
                <strong>{{ $report->report_id }}</strong>
            </div>

            <div class="detail-item">
                <span>Bus ID</span>
                <strong>{{ $report->bus_id }}</strong>
            </div>

            <div class="detail-item">
                <span>Report Date</span>
                <strong>{{ $report->report_date->format('d M Y') }}</strong>
            </div>

            <div class="detail-item">
                <span>Report Time</span>
                <strong>{{ \Carbon\Carbon::parse($report->report_time)->format('h:i A') }}</strong>
            </div>

            <div class="detail-item">
                <span>Location</span>
                <strong>{{ $report->place }}</strong>
            </div>

            <div class="detail-item">
                <span>Current Status</span>
                <strong>{{ $report->status }}</strong>
            </div>

            <div class="detail-item full">
                <span>Breakdown Description</span>
                <p>{{ $report->description }}</p>
            </div>
        </div>
    </section>

    @endif

    <!-- Information -->
    <section class="driver-bus-info">
        @if ($errors->any())
            <div class="error-modal-overlay" id="errorModal">
                <div class="error-modal">
                    <div class="error-icon">!</div>

                    <h2>Report Not Found</h2>

                    <p>
                        We couldn't find your maintenance report.
                        Please check your Report ID and try again.
                    </p>

                    <button type="button" onclick="closeErrorModal()">
                        Try Again
                    </button>
                </div>
            </div>
        @endif

        <h2>Need Help?</h2>
        <p>
            You can find your Report ID from the breakdown report
            you submitted or in the inbox. Make sure the ID is entered correctly.
        </p>
    </section>

    <!-- Back to Homepage -->
    <a href="{{ url('/home') }}" class="btn-main">
        Back to Homepage
    </a>

</div>

<script>
function closeErrorModal() {
    const modal = document.getElementById('errorModal');

    if (modal) {
        modal.remove();
    }
}
</script>

@endsection
