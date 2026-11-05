
@extends('layout.app')

@section('content')

<div class="login-page">

    <!-- Left Welcome Panel -->
    <section class="login-welcome">

        <div class="login-welcome-content">

            <div class="login-brand">
                <div class="login-brand-icon">🚌</div>
                <span>BMMS</span>
            </div>

            <div class="login-welcome-text">
                <span class="login-eyebrow">BUS MAINTENANCE MANAGEMENT SYSTEM</span>

                <h1>Welcome Back!</h1>

                <p>
                    Keeping every bus ready for the road starts with
                    better maintenance coordination.
                </p>
            </div>

            <div class="login-bus-visual">
                <div class="login-decor-circle"></div>
                <div class="login-bus">🚌</div>

                <div class="login-road">
                    <div class="login-road-line"></div>
                </div>
            </div>

            <div class="login-welcome-footer">
                <span>✦</span>
                One system. Better bus operations.
            </div>

        </div>

    </section>

    <!-- Right Login Panel -->
    <section class="login-form-panel">

        <div class="login-form-card">

            <div class="login-form-heading">
                <img src="{{ asset('image/logo.png') }}"
                     class="login-logo"
                     alt="BMMS Logo">

                <span class="login-form-label">ACCOUNT ACCESS</span>

                <h2>Sign in to BMMS</h2>

                <p>Enter your account details to continue.</p>
            </div>

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="login-input-group">
                    <label for="user_id">User ID</label>

                    <input
                        type="text"
                        name="user_id"
                        id="user_id"
                        placeholder="Enter your User ID"
                        value="{{ old('user_id') }}"
                        required
                        autocomplete="username"
                    >

                    @error('user_id')
                        <div class="alert-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="login-input-group">
                    <label for="password">Password</label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >

                    @error('password')
                        <div class="alert-error">{{ $message }}</div>
                    @enderror
                </div>

                @if ($errors->has('email'))
                    <div class="alert-error">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <button type="submit" class="login-submit-btn">
                    Log In <span>→</span>
                </button>

            </form>

            <div class="login-form-footer">
                <span>BMMS · Bus Maintenance Management System</span>
            </div>
        </div>
    </section>
</div>

@endsection
