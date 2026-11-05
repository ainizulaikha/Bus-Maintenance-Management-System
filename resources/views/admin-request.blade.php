@extends('layout.app')

@section('content')

<div class="maintenance-container">
    @if (session('success'))
        <div class="success-toast" id="successToast">
            <span class="toast-icon">✓</span>
            <span>{{ session('success') }}</span>
            <button type="button" onclick="closeToast()">×</button>
        </div>
    @endif

    <section class="maintenance-header">
        <h1>Maintenance Requests</h1>
        <p>Review and manage submitted maintenance requests.</p>
    </section>

    <!-- Maintenance Navigation -->
    <nav class="maintenance-nav">
        <a href="{{ url('/admin-maintenance') }}">Maintenance</a>

        <a href="{{ url('/admin-request') }}" class="request-link">
            Requests
            <span class="nav-count">{{ $pendingCount }}</span>
        </a>

        <a href="{{ url('/admin-records') }}">Records History</a>
        <a href="{{ url('/admin-generate') }}">Generate Report</a>
    </nav>

    <!-- Request Table -->
    <section class="maintenance-table-section">
        <h2>Pending Requests</h2>

        <table class="maintenance-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Bus ID</th>
                    <th>Driver ID</th>
                    <th>Report Date</th>
                    <th>Details</th>
                    <th>Report Information</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($reports as $report)
                    <tr>
                        <td>{{ $report->report_id }}</td>
                        <td>{{ $report->bus_id }}</td>
                        <td>{{ $report->user_id }}</td>
                        <td>{{ $report->report_date->format('d/m/Y') }}</td>
                        <td>{{ $report->description }}</td>
                        <td>
                            <button type="button"
                                    onclick="viewReport('{{ $report->id }}')">
                                View Details
                            </button>
                        </td>
                        <td>
                            <!-- Approve Form -->
                            <form action="{{ url('/admin-request/' . $report->id . '/approve') }}" method="POST">
                                @csrf

                                <select name="assigned_mechanic_id" required>
                                    <option value="">Select Mechanic</option>

                                    @foreach ($mechanics as $mechanic)
                                        <option value="{{ $mechanic->user_id }}">
                                            {{ $mechanic->name }} ({{ $mechanic->user_id }})
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit">Approve</button>
                            </form>

                            <!-- Reject Form -->
                            <form action="{{ url('/admin-request/' . $report->id . '/reject') }}" method="POST">
                                @csrf

                                <textarea name="rejection_reason"
                                        placeholder="Enter rejection reason"
                                        required></textarea>

                                <button type="submit">Reject</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">
                            No pending maintenance requests.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>

<!-- Report Details Modal -->
<div id="reportDetailsModal" class="report-modal">
    <div class="report-modal-content">

        <div class="report-modal-header">
            <h2>Breakdown Report Details</h2>
            <button type="button" onclick="closeReportModal()">×</button>
        </div>

        <div class="report-modal-body">
            <p><strong>Report ID:</strong> <span id="detailReportID"></span></p>
            <p><strong>Bus ID:</strong> <span id="detailBusID"></span></p>
            <p><strong>Driver ID:</strong> <span id="detailDriverID"></span></p>
            <p><strong>Date:</strong> <span id="detailDate"></span></p>
            <p><strong>Time:</strong> <span id="detailTime"></span></p>
            <p><strong>Location:</strong> <span id="detailPlace"></span></p>
            <p><strong>Description:</strong></p>
            <p id="detailDescription"></p>

            <div>
                <strong>Uploaded Pictures:</strong>
                <div id="detailPictures" class="report-picture-list"></div>
            </div>
        </div>

    </div>
</div>

<script>
    // View Breakdown Report Details
    const breakdownReports = @json($reports);

    function viewReport(reportID) {
        const report = breakdownReports.find(
            item => item.id == reportID
        );

        if (!report) return;

        document.getElementById('detailReportID').textContent = report.report_id;
        document.getElementById('detailBusID').textContent = report.bus_id;
        document.getElementById('detailDriverID').textContent = report.user_id;
        document.getElementById('detailDate').textContent = report.report_date;
        document.getElementById('detailTime').textContent = report.report_time;
        document.getElementById('detailPlace').textContent = report.place;
        document.getElementById('detailDescription').textContent = report.description;

        const pictureContainer = document.getElementById('detailPictures');

        pictureContainer.innerHTML = '';

        if (report.pics && report.pics.length > 0) {
            report.pics.forEach(function (picture) {
                const image = document.createElement('img');

                image.src = '/storage/' + picture;
                image.alt = 'Breakdown report picture';

                pictureContainer.appendChild(image);
            });
        } else {
            pictureContainer.textContent = 'No pictures uploaded.';
        }

        document.getElementById('reportDetailsModal').style.display = 'flex';
    }

    function closeReportModal() {
        document.getElementById('reportDetailsModal').style.display = 'none';
    }

    function closeToast() {
        const toast = document.getElementById('successToast');

        if (toast) {
            toast.remove();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const toast = document.getElementById('successToast');

        if (toast) {
            setTimeout(function () {
                toast.remove();
            }, 4000);
        }
    });
</script>

@endsection