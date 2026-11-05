
@extends('layout.app')

@section('content')

@php
    $user = auth()->user();
@endphp

<div class="maintenance-container profile-container">

    <!-- Profile Header -->
    <section class="maintenance-header">
        <h1>My Profile</h1>
        <p>View your personal and account information.</p>
    </section>

    <!-- Profile Card -->
    <section class="profile-main-card">

        <!-- Profile Banner -->
        <div class="profile-banner">
            <div class="profile-banner-decoration"></div>
            <span class="profile-banner-label">BMMS ACCOUNT</span>
        </div>

        <!-- Profile Heading -->
        <div class="profile-user-heading">

            <div class="profile-picture">
                <img src="{{ asset('image/profile-picture.jpg') }}"
                     alt="Profile Picture">
            </div>

            <div class="profile-user-info">
                <h2>{{ $user->name }}</h2>
                <span class="profile-role">{{ $user->role }}</span>
            </div>

        </div>

        <!-- Account Information -->
        <div class="profile-information">

            <div class="profile-section-heading">
                <div>
                    <h3>Account Information</h3>
                    <p>Your registered account details</p>
                </div>
                <span class="profile-section-icon">✦</span>
            </div>

            <div class="profile-information-grid">

                <div class="profile-information-item">
                    <span class="profile-info-label">User ID</span>
                    <p>{{ $user->user_id }}</p>
                </div>

                <div class="profile-information-item">
                    <span class="profile-info-label">Full Name</span>
                    <p>{{ $user->name }}</p>
                </div>

                <div class="profile-information-item">
                    <span class="profile-info-label">Email Address</span>
                    <p>{{ $user->email }}</p>
                </div>

                <div class="profile-information-item">
                    <span class="profile-info-label">User Role</span>
                    <p>{{ $user->role }}</p>
                </div>

            </div>

        </div>

        <div class="profile-footer">
            <span>BMMS · Bus Maintenance Management System</span>
            <span class="profile-account-status">● Active Account</span>
        </div>

    </section>

</div>

@endsection
