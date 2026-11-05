
@extends('layout.app')

@section('content')

<div class="maintenance-container">
    <section class="maintenance-header">
        <h1>Generate Maintenance Report</h1>
        <p>Generate reports about bus maintenance activities.</p>
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
    
    <!-- Report Generation Form -->
    <section class="generate-report-section">
        <div class="generate-report-heading">
            <div class="generate-report-icon">📊</div>
            <div>
                <span class="generate-section-label">REPORT CENTRE</span>
                <h2>Generate a New Report</h2>
                <p>Select the report category and date range to retrieve your records.</p>
            </div>
        </div>

        <form action="{{ url('/admin-generate/result') }}" method="GET">
            <div class="generate-form-grid">
                <div class="generate-form-group">
                    <label for="report_type">Report Type</label>
                    <select name="report_type" id="report_type" required>
                        <option value="">Select Report Type</option>
                        <option value="maintenance">Maintenance Report</option>
                        <option value="breakdown">Breakdown Report</option>
                        <option value="bus_status">Bus Availability Report</option>
                    </select>
                    <small>Choose the type of information you want to view.</small>
                </div>

                <div class="generate-form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" required>
                    <small>Beginning of the reporting period.</small>
                </div>

                <div class="generate-form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" required>
                    <small>End of the reporting period.</small>
                </div>

            </div>

            <div class="generate-form-footer">
                <p>✦ Select a date range to generate your report.</p>
                <button type="submit" class="generate-report-button">
                    <span>Generate Report</span>
                    <span>→</span>
                </button>
            </div>
        </form>
    </section>

    @if (isset($reports))
        <section class="generated-report-section">

            <div class="generated-report-heading">
                <div>
                    <span class="generate-section-label">REPORT RESULTS</span>
                    <h2>Generated Report</h2>
                    <p>Review the records retrieved for your selected period.</p>
                </div>

                <div class="generated-report-icon">📄</div>
            </div>

            <div class="generated-report-summary">
                <div>
                    <span>REPORT TYPE</span>
                    <strong>
                        @if ($reportType === 'maintenance')
                            Maintenance Report
                        @elseif ($reportType === 'breakdown')
                            Breakdown Report
                        @else
                            Bus Availability Report
                        @endif
                    </strong>
                </div>

                <div>
                    <span>REPORT PERIOD</span>
                    <strong>
                        {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                        – 
                        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </strong>
                </div>

                <div>
                    <span>TOTAL RECORDS</span>
                    <strong>{{ $reports->count() }}</strong>
                </div>
            </div>

            <div class="generated-report-table-wrapper">
                <table class="maintenance-table generated-report-table">
                    <thead>
                        @if ($reportType === 'bus_status')
                            <tr>
                                <th>Bus ID</th>
                                <th>Route</th>
                                <th>Status</th>
                            </tr>
                        @else
                            <tr>
                                <th>Report ID</th>
                                <th>Bus ID</th>
                                <th>Driver ID</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        @endif
                    </thead>

                    <tbody>
                        @forelse ($reports as $report)
                            <tr>
                                @if ($reportType === 'bus_status')
                                    <td>{{ $report->bus_id }}</td>
                                    <td>{{ $report->route }}</td>
                                    <td>{{ $report->status }}</td>
                                @else
                                    <td>{{ $report->report_id }}</td>
                                    <td>{{ $report->bus_id }}</td>
                                    <td>{{ $report->user_id }}</td>
                                    <td>{{ $report->description }}</td>
                                    <td>{{ $report->report_date->format('d/m/Y') }}</td>
                                    <td>{{ $report->status }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $reportType === 'bus_status' ? 3 : 6 }}"
                                    class="generated-report-empty">
                                    No records found for the selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="generated-report-footer">
                <span>BMMS · Bus Maintenance Management System</span>

                <button type="button" onclick="window.print()" class="print-button">
                    🖨 Print Report
                </button>
            </div>

        </section>
    @endif

</div>

@endsection
