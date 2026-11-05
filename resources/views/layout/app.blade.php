<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bus Maintenance Management System</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
</head>

<body>
<nav class="topnav">
    <!-- Left: main pages --> 
    
    <!-- Left: main pages -->
    <div class="nav-left">
        @auth
            <a href="{{ url('/home') }}"
               class="{{ request()->is('home') ? 'active' : '' }}">
                Home
            </a>

            @if (Auth::user()->role === 'Admin & Operation')
                <a href="{{ url('/admin-users') }}"
                   class="{{ request()->is('admin-users') ? 'active' : '' }}">
                    User Management
                </a>

                <a href="{{ url('/admin-maintenance') }}"
                   class="{{ request()->is('admin-maintenance') ? 'active' : '' }}">
                    Maintenance
                </a>

                <a href="{{ url('/admin-busstatus') }}"
                   class="{{ request()->is('admin-busstatus') ? 'active' : '' }}">
                    Bus Availability
                </a>

            @elseif (Auth::user()->role === 'Bus Driver')
                <a href="{{ url('/driver-busstatus') }}"
                   class="{{ request()->is('driver-busstatus') ? 'active' : '' }}">
                    Bus Availability
                </a>

                <a href="{{ url('/driver-reportID') }}"
                   class="{{ request()->is('driver-reportID', 'driver-maintenance', 'maintenance-status') ? 'active' : '' }}">
                    Maintenance
                </a>

            @elseif (Auth::user()->role === 'Mechanic')
                <a href="{{ url('/mechanic-maintenance') }}"
                   class="{{ request()->is('mechanic-maintenance', 'mechanic-pending', 'mechanic-completed', 'mechanic-history') ? 'active' : '' }}">
                    Maintenance
                </a>
            @endif

        @else
            <a href="{{ url('/') }}">Welcome</a>
        @endauth
    </div>

    <!-- Right: account -->
    <div class="nav-right">
        @auth 
            @if (Auth::user()->role == 'Bus Driver') 
                <a href="{{ url('/driver-inbox') }}">📥</a>
            @endif
        @endauth

        @auth
            <a href="/profile">Profile</a>

            <button type="button" onclick="openLogoutModal()">Log Out</button>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endauth
    </div>

</nav>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal-overlay" style="display: none;">
    <div class="maintenance-modal logout-modal" role="dialog"
         aria-modal="true" aria-labelledby="logoutTitle">

        <div class="modal-heading">
            <div>
                <h2 id="logoutTitle">Confirm Logout</h2>
                <p>Are you sure you want to log out of your account?</p>
            </div>

            <button type="button" class="modal-close"
                    onclick="closeLogoutModal()"
                    aria-label="Close">&times;</button>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn-cancel"
                    onclick="closeLogoutModal()">
                Cancel
            </button>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Yes, Log Out</button>
            </form>
        </div>

    </div>
</div>

<div class="content">
    @yield('content')
</div>

<footer class="footer">
    <p>© 2026 Bus Maintenance Management System. All Rights Reserved.</p>
</footer>

<script>
    function openLogoutModal() {
        document.getElementById('logoutModal').style.display = 'flex';
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }

    document.getElementById('logoutModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeLogoutModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLogoutModal();
        }
    });
</script>

@yield('scripts')
</body>
</html>