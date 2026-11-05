@extends('layout.app')

@section('content')

<div class="maintenance-container driver-inbox-container">

    <!-- Inbox Header -->
    <section class="driver-inbox-header">
        <div>
            <h1>My Inbox</h1>
            <p>Stay updated on your breakdown reports and maintenance progress.</p>
        </div>

        <div class="inbox-total">
            <span class="inbox-total-number">{{ $reports->count() }}</span>
            <span class="inbox-total-label">Total Reports</span>
        </div>
    </section>

    <!-- Inbox Content -->
    <section class="driver-inbox-section">

        <div class="driver-inbox-toolbar">
            <div>
                <h2>All Messages</h2>
                <p>Your submitted breakdown reports</p>
            </div>

            <span class="inbox-count">
                {{ $reports->count() }} reports
            </span>
        </div>

        @if($reports->count() > 0)

            <div class="driver-inbox-list">

                @foreach($reports as $report)

                    <article class="driver-inbox-item">

                        <div class="inbox-message-icon">
                            <span>🚌</span>
                        </div>

                        <div class="inbox-message-content">

                            <div class="inbox-message-top">
                                <h3>{{ $report->report_id }}</h3>

                                <span class="inbox-message-date">
                                    {{ $report->created_at->format('d M Y') }}
                                </span>
                            </div>

                            <p class="inbox-message-subject">
                                Breakdown Report – Bus {{ $report->bus_id }}
                            </p>

                            <p class="inbox-message-description">
                                {{ Str::limit($report->description, 100) }}
                            </p>

                            <div class="inbox-message-bottom">

                                <span class="inbox-status status-{{ strtolower(str_replace(' ', '-', $report->status)) }}">
                                    {{ $report->status }}
                                </span>

                                <a href="{{ url('/maintenance-status?report_id=' . $report->report_id) }}"
                                   class="inbox-view-link">
                                    View Details
                                    <span>→</span>
                                </a>

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="driver-inbox-empty">
                <div class="inbox-empty-icon">📭</div>
                <h3>Your inbox is empty</h3>
                <p>You haven't submitted any breakdown reports yet.</p>

                <a href="{{ url('/breakdown-report') }}" class="btn-main">
                    Submit a Breakdown Report
                </a>
            </div>

        @endif

    </section>

    <a href="{{ url('/home') }}" class="btn-main inbox-back-button">
        Back to Homepage
    </a>
</div>

@endsection