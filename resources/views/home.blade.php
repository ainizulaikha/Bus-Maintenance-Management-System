
@extends('layout.app')

@section('content')

@if (session('success'))
    <div class="success-modal-overlay" id="successModal">
        <div class="success-modal">
            <div class="success-icon">
                <span class="css-check"></span>
            </div>

            <h2>Report Submitted!</h2>

            <p>
                Your breakdown report has been successfully
                submitted to the maintenance team.
            </p>

            <div class="report-id-box">
                <span>YOUR REPORT ID</span>
                <strong>{{ session('report_id') }}</strong>
            </div>

            <button type="button" onclick="closeSuccessModal()">
                OK
            </button>
        </div>
    </div>
@endif

<div class="home-container">
    
    <!-- ADMIN & OPERATION HOMEPAGE -->
    @if ($user->role === 'Admin & Operation')

        <!-- Admin Welcome Section -->
        <section class="admin-hero">
            <div class="admin-hero-content">
                <span class="admin-hero-label">BUS MAINTENANCE MANAGEMENT SYSTEM</span>

                <h1>Welcome, {{ $user->name }}!</h1>

                <p>
                    Keep track of fleet operations, manage maintenance
                    activities and ensure every bus is ready for the road.
                </p>

                <a href="{{ url('/admin-request') }}" class="admin-hero-button">
                    <span>Review Maintenance Requests</span>
                    <span class="admin-button-arrow">→</span>
                </a>
            </div>

            <div class="admin-hero-visual">
                <div class="admin-hero-circle"></div>
                <div class="admin-hero-road">
                    <span class="admin-road-line"></span>
                </div>
                <div class="admin-hero-bus">🚌</div>
                <span class="admin-hero-decoration admin-decoration-one">✦</span>
                <span class="admin-hero-decoration admin-decoration-two">✦</span>
            </div>
        </section>


        <!-- Fleet Overview -->
        <section class="admin-fleet-section">

            <div class="admin-section-heading">
                <div>
                    <span class="admin-section-label">FLEET OVERVIEW</span>
                    <h2>Bus Availability</h2>
                </div>

                <a href="{{ url('/admin-busstatus') }}" class="admin-section-link">
                    View All Buses →
                </a>
            </div>

            <div class="admin-fleet-grid">

                <div class="admin-fleet-card total-buses">
                    <div class="admin-fleet-card-top">
                        <span>Total Fleet</span>
                        <span class="admin-fleet-icon">🚌</span>
                    </div>
                    <strong>{{ $busStats['total'] }}</strong>
                    <p>Registered buses</p>
                </div>

                <div class="admin-fleet-card available-buses">
                    <div class="admin-fleet-card-top">
                        <span>Available</span>
                        <span class="admin-fleet-icon">✓</span>
                    </div>
                    <strong>{{ $busStats['available'] }}</strong>
                    <p>Ready for service</p>
                </div>

                <div class="admin-fleet-card standby-buses">
                    <div class="admin-fleet-card-top">
                        <span>Standby</span>
                        <span class="admin-fleet-icon">⏱</span>
                    </div>
                    <strong>{{ $busStats['standby'] }}</strong>
                    <p>On standby</p>
                </div>

                <div class="admin-fleet-card maintenance-buses">
                    <div class="admin-fleet-card-top">
                        <span>Under Maintenance</span>
                        <span class="admin-fleet-icon">🔧</span>
                    </div>
                    <strong>{{ $busStats['underMaintenance'] }}</strong>
                    <p>Currently being serviced</p>
                </div>

            </div>
        </section>


        <!-- Pending Maintenance Requests -->
        <section class="admin-pending-section">

            <div class="admin-pending-icon">📋</div>

            <div class="admin-pending-content">
                <span class="admin-section-label">REQUIRES ATTENTION</span>
                <h2>Maintenance Requests</h2>
                <p>
                    Review breakdown reports submitted by bus drivers
                    and manage their approval.
                </p>
            </div>

            <div class="admin-pending-count">
                <strong>{{ $pendingRequests }}</strong>
                <span>Pending Requests</span>
            </div>

            <a href="{{ url('/admin-request') }}" class="admin-pending-button">
                Review Requests →
            </a>

        </section>


        <!-- Quick Access -->
        <section class="admin-functions">

            <div class="admin-section-heading">
                <div>
                    <span class="admin-section-label">QUICK ACCESS</span>
                    <h2>Manage Your Operations</h2>
                </div>
            </div>

            <div class="admin-function-grid">

                <a href="{{ url('/admin-users') }}" class="admin-function-card">
                    <div class="admin-function-icon users-icon">👥</div>
                    <div>
                        <h3>User Management</h3>
                        <p>Manage employee accounts, roles and user access.</p>
                    </div>
                    <span class="admin-card-arrow">→</span>
                </a>

                <a href="{{ url('/admin-busstatus') }}" class="admin-function-card">
                    <div class="admin-function-icon buses-icon">🚌</div>
                    <div>
                        <h3>Bus Management</h3>
                        <p>View bus information and update availability status.</p>
                    </div>
                    <span class="admin-card-arrow">→</span>
                </a>

                <a href="{{ url('/admin-maintenance') }}" class="admin-function-card">
                    <div class="admin-function-icon maintenance-icon">🔧</div>
                    <div>
                        <h3>Maintenance Management</h3>
                        <p>Monitor assigned tasks and ongoing maintenance activities.</p>
                    </div>
                    <span class="admin-card-arrow">→</span>
                </a>

                <a href="{{ url('/admin-generate') }}" class="admin-function-card">
                    <div class="admin-function-icon reports-icon">📊</div>
                    <div>
                        <h3>Generate Reports</h3>
                        <p>Generate reports on bus and maintenance activities.</p>
                    </div>
                    <span class="admin-card-arrow">→</span>
                </a>

            </div>
        </section>

    <!-- MECHANIC HOMEPAGE -->
    @elseif ($user->role === 'Mechanic')

    <!-- Mechanic Welcome Section -->
    <section class="mechanic-hero">
        <div class="mechanic-hero-content">
            <span class="mechanic-hero-label">
                MECHANIC WORKSPACE
            </span>

            <h1>Welcome, {{ $user->name }}!</h1>

            <p>
                Your maintenance workspace. View your assigned tasks,
                update repair progress and review completed maintenance work.
            </p>

            <a href="{{ url('/mechanic-maintenance') }}"
                class="mechanic-hero-button">
                <span>View All Tasks</span>
                <span>→</span>
            </a>
        </div>

        <div class="mechanic-hero-visual">
            <div class="mechanic-hero-circle"></div>
            <div class="mechanic-hero-toolbox">🧰</div>
            <span class="mechanic-hero-decoration mechanic-decoration-one">✦</span>
            <span class="mechanic-hero-decoration mechanic-decoration-two">✦</span>
        </div>
    </section>

    <!-- Mechanic Quick Access -->
    <section class="mechanic-functions">
        <div class="mechanic-section-heading">
            <div>
                <span class="mechanic-section-label">QUICK ACCESS</span>
                <h2>Maintenance Tasks</h2>
            </div>
        </div>

        <div class="mechanic-function-grid">

            <a href="{{ url('/mechanic-maintenance') }}"
                class="mechanic-function-card">

                <div class="mechanic-function-icon all-tasks-icon">📋</div>

                <div>
                    <h3>All Tasks</h3>
                    <p>View all maintenance tasks assigned to you.</p>
                </div>

                <span class="mechanic-card-arrow">→</span>
            </a>

            <a href="{{ url('/mechanic-tostart') }}"
                class="mechanic-function-card">

                <div class="mechanic-function-icon tostart-icon">🔧</div>

                <div>
                    <h3>To Start</h3>
                    <p>View tasks waiting for maintenance to begin.</p>
                </div>

                <span class="mechanic-card-arrow">→</span>
            </a>

            <a href="{{ url('/mechanic-inprogress') }}"
                class="mechanic-function-card">

                <div class="mechanic-function-icon progress-icon">⚙️</div>

                <div>
                    <h3>In Progress</h3>
                    <p>Monitor and update your ongoing repair tasks.</p>
                </div>

                <span class="mechanic-card-arrow">→</span>
            </a>

            <a href="{{ url('/mechanic-history') }}"
                class="mechanic-function-card">

                <div class="mechanic-function-icon history-icon">📁</div>

                <div>
                    <h3>History</h3>
                    <p>Review your previously completed maintenance tasks.</p>
                </div>

                <span class="mechanic-card-arrow">→</span>
            </a>

        </div>
    </section>
    
    <!-- BUS DRIVER HOMEPAGE -->
    @elseif ($user->role === 'Bus Driver')

        <section class="driver-hero">
            <div class="driver-hero-content">
                <span class="driver-hero-label">BUS MAINTENANCE MANAGEMENT SYSTEM</span>

                <h1>Hello, {{ $user->name }}!</h1>

                <p>
                    Your daily bus operations, made easier.
                    Check bus availability, report breakdowns
                    and keep track of maintenance progress.
                </p>

                <a href="{{ url('/breakdown-report') }}" class="driver-hero-button">
                    <span>Report a Breakdown</span>
                    <span class="driver-button-arrow">→</span>
                </a>
            </div>

            <div class="driver-hero-visual">
                <div class="driver-hero-circle"></div>
                <div class="driver-hero-road">
                    <span class="driver-road-line"></span>
                </div>
                <div class="driver-hero-bus">🚌</div>
                <span class="driver-hero-decoration decoration-one">✦</span>
                <span class="driver-hero-decoration decoration-two">✦</span>
            </div>
        </section>

        <section class="driver-route-section">
            <div class="driver-section-heading">
                <div>
                    <span class="driver-section-label">YOUR DAILY JOURNEY</span>
                    <h2>My Assigned Route</h2>
                </div>

                <span class="driver-route-status">● Active Route</span>
            </div>

            <div class="driver-route-card">
                <div class="driver-route-main">
                    <div class="driver-route-number">
                        <span>ROUTE</span>
                        <strong>B-101</strong>
                    </div>

                    <div class="driver-route-name">
                        <h3>Cyberjaya – Putrajaya Sentral</h3>
                        <p>Your currently assigned bus route</p>
                    </div>
                </div>

                <div class="driver-route-details">
                    <div class="driver-route-point">
                        <span class="route-point-icon">●</span>
                        <div>
                            <span>STARTING POINT</span>
                            <strong>Cyberjaya</strong>
                        </div>
                    </div>

                    <div class="driver-route-connector"></div>

                    <div class="driver-route-point">
                        <span class="route-point-icon destination">●</span>
                        <div>
                            <span>DESTINATION</span>
                            <strong>Putrajaya Sentral</strong>
                        </div>
                    </div>
                </div>

                <div class="driver-route-footer">
                    <span>🚌 Your assigned service route</span>
                </div>
            </div>
        </section>

        <section class="driver-functions">
            <div class="driver-section-heading">
                <div>
                    <span class="driver-section-label">QUICK ACCESS</span>
                    <h2>What would you like to do?</h2>
                </div>
            </div>

            <div class="driver-function-grid">

                <a href="{{ url('/driver-busstatus') }}" class="driver-function-card">
                    <div class="driver-function-icon bus-icon">🚌</div>
                    <div>
                        <h3>Bus Availability</h3>
                        <p>Check the current availability and status of buses.</p>
                    </div>
                    <span class="driver-card-arrow">→</span>
                </a>

                <a href="{{ url('/breakdown-report') }}" class="driver-function-card">
                    <div class="driver-function-icon report-icon">📝</div>
                    <div>
                        <h3>Report Breakdown</h3>
                        <p>Submit a report if your bus experiences a breakdown.</p>
                    </div>
                    <span class="driver-card-arrow">→</span>
                </a>

                <a href="{{ url('/driver-reportID') }}" class="driver-function-card">
                    <div class="driver-function-icon status-icon">🔧</div>
                    <div>
                        <h3>Maintenance Status</h3>
                        <p>Track the progress of your submitted maintenance report.</p>
                    </div>
                    <span class="driver-card-arrow">→</span>
                </a>
            </div>
        </section>
    @endif

</div>

<script>
    function closeSuccessModal() {
        const modal = document.getElementById('successModal');

        if (modal) {
            modal.remove();
        }
    }
</script>

@endsection
