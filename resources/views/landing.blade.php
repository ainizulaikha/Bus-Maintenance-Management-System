
@extends('layout.app')

@section('content')

<div class="landing-container">

    <!-- Hero Section -->
    <section class="landing-hero">

        <div class="landing-content">

            <span class="landing-label">
                BUS MAINTENANCE MANAGEMENT SYSTEM
            </span>

            <h1>
                Keeping Every Bus
                <span>Ready for the Road.</span>
            </h1>

            <p>
                A centralised platform designed to support reliable bus
                operations through bus availability monitoring, breakdown
                reporting and coordinated maintenance management.
            </p>

            <div class="landing-hero-actions">
                <a href="{{ url('/login') }}" class="landing-login-btn">
                    Access BMMS <span>→</span>
                </a>
            </div>

            <div class="landing-hero-note">
                <span>✦</span>
                One system. Better maintenance coordination.
            </div>

        </div>

        <!-- Hero Visual -->
        <div class="landing-visual">

            <div class="landing-visual-circle"></div>

            <div class="landing-fleet-card">

                <div class="landing-fleet-heading">
                    <div>
                        <span>BMMS OVERVIEW</span>
                        <h3>Fleet Operations</h3>
                    </div>

                    <span class="landing-fleet-icon">🚌</span>
                </div>

                <div class="landing-fleet-illustration">
                    <div class="landing-road">
                        <div class="landing-road-line"></div>
                    </div>

                    <div class="landing-main-bus">🚌</div>

                    <div class="landing-small-bus">🚌</div>
                </div>

                <div class="landing-fleet-caption">
                    <span class="landing-caption-dot"></span>
                    <span>Supporting smoother bus operations</span>
                </div>

            </div>

            <div class="landing-floating-tag landing-tag-one">
                <span>🔧</span>
                Maintenance Tracking
            </div>

            <div class="landing-floating-tag landing-tag-two">
                <span>📋</span>
                Breakdown Reports
            </div>

        </div>

    </section>


    <!-- System Overview -->
    <section class="landing-overview">

        <div class="landing-section-heading">
            <span>THE BMMS PLATFORM</span>

            <h2>
                Everything Needed to Keep
                <span>Operations Moving.</span>
            </h2>

            <p>
                BMMS brings essential bus maintenance activities together
                in one organised platform.
            </p>
        </div>

        <div class="landing-feature-grid">

            <article class="landing-feature-card">
                <div class="landing-feature-icon">🚌</div>

                <span class="landing-feature-number">01</span>

                <h3>Bus Availability</h3>

                <p>
                    Monitor bus availability and identify buses that are
                    available, on standby or undergoing maintenance.
                </p>
            </article>

            <article class="landing-feature-card">
                <div class="landing-feature-icon">🔧</div>

                <span class="landing-feature-number">02</span>

                <h3>Maintenance Management</h3>

                <p>
                    Manage maintenance approvals, mechanic assignments
                    and repair progress through a centralised system.
                </p>
            </article>

            <article class="landing-feature-card">
                <div class="landing-feature-icon">📋</div>

                <span class="landing-feature-number">03</span>

                <h3>Breakdown Reporting</h3>

                <p>
                    Allow bus drivers to submit breakdown reports and
                    monitor the progress of their maintenance requests.
                </p>
            </article>

        </div>

    </section>


    <!-- How BMMS Works -->
    <section class="landing-workflow">

        <div class="landing-section-heading">
            <span>HOW IT WORKS</span>

            <h2>A Connected Maintenance Process</h2>

            <p>
                Each role contributes to a more organised maintenance
                workflow.
            </p>
        </div>

        <div class="landing-workflow-grid">

            <div class="landing-workflow-item">

                <div class="landing-workflow-number">01</div>

                <div class="landing-workflow-icon">👨‍✈️</div>

                <h3>Driver Reports</h3>

                <p>
                    The bus driver submits a breakdown report with
                    the necessary bus and problem information.
                </p>

            </div>

            <div class="landing-workflow-arrow">→</div>

            <div class="landing-workflow-item">

                <div class="landing-workflow-number">02</div>

                <div class="landing-workflow-icon">🗂️</div>

                <h3>Admin Approves</h3>

                <p>
                    Admin & Operation reviews the request and assigns
                    an available mechanic.
                </p>

            </div>

            <div class="landing-workflow-arrow">→</div>

            <div class="landing-workflow-item">

                <div class="landing-workflow-number">03</div>

                <div class="landing-workflow-icon">🔧</div>

                <h3>Mechanic Repairs</h3>

                <p>
                    The mechanic carries out the maintenance work
                    and updates its progress until completion.
                </p>

            </div>

        </div>

    </section>


    <!-- Bottom CTA -->
    <section class="landing-bottom">

        <div>
            <span class="landing-bottom-label">BMMS ACCESS</span>

            <h2>Let's Keep Every Bus Moving.</h2>

            <p>
                Log in to access the functions assigned to your role.
            </p>
        </div>

        <a href="{{ url('/login') }}" class="landing-login-btn">
            Login to BMMS <span>→</span>
        </a>
    </section>
</div>

@endsection
