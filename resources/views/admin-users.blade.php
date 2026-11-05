@extends('layout.app')

@section('content')

<div class="maintenance-container">

    <section class="maintenance-header">
        <h1>Create New Account</h1>
        <p>Fill in the details below to create an employee account.</p>
    </section>

    <section class="admin-create-section">
        <div class="admin-create-heading">
            <div class="admin-create-icon">✦</div>
            <div>
                <span class="admin-page-label">EMPLOYEE MANAGEMENT</span>
                <h2>Create New Account</h2>
                <p>Register a new employee and provide their BMMS account details.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="success-toast" id="successToast">
                <span class="toast-icon">✓</span>
                <span>{{ session('success') }}</span>
                <button type="button" onclick="closeToast()">×</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="error-toast" id="errorToast">
                <span class="toast-icon">!</span>
                <span>{{ $errors->first() }}</span>
                <button type="button" onclick="closeErrorToast()">×</button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin-users.store') }}">
            @csrf

            <div class="admin-create-grid">
                <div class="admin-create-field">
                    <label for="user_id">User ID</label>
                    <input type="text" name="user_id" id="user_id"
                        placeholder="e.g. ME0001"
                        value="{{ old('user_id') }}" required>
                </div>

                <div class="admin-create-field">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email"
                        placeholder="employee@bmms.com"
                        value="{{ old('email') }}" required>
                </div>

                <div class="admin-create-field">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name"
                        placeholder="Enter employee's full name"
                        value="{{ old('name') }}" required>
                </div>

                <div class="admin-create-field">
                    <label for="role">User Role</label>
                    <select name="role" id="role" required>
                        <option value="">Select a role</option>
                        <option value="Mechanic" {{ old('role') == 'Mechanic' ? 'selected' : '' }}>Mechanic</option>
                        <option value="Bus Driver" {{ old('role') == 'Bus Driver' ? 'selected' : '' }}>Bus Driver</option>
                    </select>
                </div>

                <div class="admin-create-field admin-password-field">
                    <label for="password">Initial Password</label>
                    <input type="password" name="password" id="password"
                        placeholder="Create an initial password" required>
                </div>
            </div>

            <div class="admin-create-footer">
                <p>✦ The employee can use these details to log in to BMMS.</p>
                <button type="submit" class="admin-create-button">
                    <span>＋</span> Create Account
                </button>
            </div>
        </form>
    </section>

    <div class="admin-employee-section">
        <div class="admin-employee-heading">
            <div>
                <span class="admin-page-label">EMPLOYEE DIRECTORY</span>
                <h2>Registered Employees</h2>
                <p>View, search and manage employee accounts.</p>
            </div>

            <span class="admin-employee-total">
                {{ $users->count() }} Employees
            </span>
        </div>

            <!-- Employee Search -->
            <div class="employee-filter-section admin-employee-filters">

                <input
                    type="text"
                    id="employeeSearch"
                    placeholder="Search by User ID or employee name..."
                >

                <!-- Role Filter Buttons -->
                <div class="employee-role-filters">
                    <button type="button" class="role-filter active" data-role="all">
                        All Employees ({{ $users->count() }})
                    </button>

                    <button type="button" class="role-filter" data-role="Admin & Operation">
                        Admin & Operation ({{ $users->where('role', 'Admin & Operation')->count() }})
                    </button>

                    <button type="button" class="role-filter" data-role="Bus Driver">
                        Bus Driver ({{ $users->where('role', 'Bus Driver')->count() }})
                    </button>

                    <button type="button" class="role-filter" data-role="Mechanic">
                        Mechanic ({{ $users->where('role', 'Mechanic')->count() }})
                    </button>
                </div>

                <!-- Account Status Filter -->
                <select id="employeeStatus">
                    <option value="all">All Account Status</option>
                    <option value="active">Active</option>
                    <option value="deactivated">Deactivated</option>
                </select>

            </div>

            @if ($users->isEmpty())
                <p>No employee accounts have been registered yet.</p>
            @else
                <div class="table-responsive">
                    <table class="admin-employee-table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Account Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                <tr class="employee-row"
                                    data-user-id="{{ strtolower($user->user_id) }}"
                                    data-name="{{ strtolower($user->name) }}"
                                    data-role="{{ $user->role }}"
                                    data-status="{{ $user->is_active ? 'active' : 'deactivated' }}">
                                    <td>{{ $user->user_id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        @if ($user->is_active)
                                            <span class="status-available">Active</span>
                                        @else
                                            <span class="status-unavailable">Deactivated</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn-main admin-edit-toggle"
                                                onclick="document.getElementById('edit-user-{{ $user->id }}').style.display = 'block';">
                                            Edit
                                        </button>

                                        @if ($user->is_active)
                                            <form method="POST"
                                                action="{{ route('admin-users.deactivate', $user->id) }}"
                                                onsubmit="return confirm('Are you sure you want to deactivate this account?');">
                                                @csrf
                                                <button type="submit" class="btn-main admin-account-button deactivate-button">
                                                    Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST"
                                                action="{{ route('admin-users.activate', $user->id) }}"
                                                onsubmit="return confirm('Are you sure you want to reactivate this account?');">
                                                @csrf
                                                <button type="submit" class="btn-main admin-account-button reactivate-button">
                                                    Reactivate
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="6">
                                        <div id="edit-user-{{ $user->id }}" class="admin-edit-panel" style="display: none;">
                                            <h3>Edit Employee: {{ $user->name }}</h3>

                                            <form method="POST"
                                                action="{{ route('admin-users.update', $user->id) }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="name"
                                                        value="{{ $user->name }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="email"
                                                        value="{{ $user->email }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Role</label>
                                                    <select name="role" required>
                                                        <option value="Mechanic"
                                                            {{ $user->role === 'Mechanic' ? 'selected' : '' }}>
                                                            Mechanic
                                                        </option>
                                                        <option value="Bus Driver"
                                                            {{ $user->role === 'Bus Driver' ? 'selected' : '' }}>
                                                            Bus Driver
                                                        </option>
                                                    </select>
                                                </div>

                                                <button type="submit" class="btn-main">Save Changes</button>

                                                <button type="button" class="btn-main admin-cancel-button"
                                                        onclick="document.getElementById('edit-user-{{ $user->id }}').style.display = 'none';">
                                                    Cancel
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    <!-- Back button on the left -->
    <a href="{{ url('/home') }}" class="btn-main">Back to Homepage</a>

</div>

<script>
    function closeToast() {
        const toast = document.getElementById('successToast');
        if (toast) toast.remove();
    }

    function closeErrorToast() {
        const toast = document.getElementById('errorToast');
        if (toast) toast.remove();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const successToast = document.getElementById('successToast');
        const errorToast = document.getElementById('errorToast');

        if (successToast) {
            setTimeout(function () {
                successToast.remove();
            }, 4000);
        }

        if (errorToast) {
            setTimeout(function () {
                errorToast.remove();
            }, 4000);
        }
    });

    // Employee Search and Filter
    const employeeSearch = document.getElementById('employeeSearch');
    const employeeStatus = document.getElementById('employeeStatus');
    const roleButtons = document.querySelectorAll('.role-filter');
    const employeeRows = document.querySelectorAll('.employee-row');

    let selectedRole = 'all';

    function filterEmployees() {
        const searchValue = employeeSearch.value.toLowerCase().trim();
        const selectedStatus = employeeStatus.value;

        employeeRows.forEach(function(row) {
            const userID = row.dataset.userId;
            const name = row.dataset.name;
            const role = row.dataset.role;
            const status = row.dataset.status;

            const matchesSearch =
                userID.includes(searchValue) ||
                name.includes(searchValue);

            const matchesRole =
                selectedRole === 'all' || role === selectedRole;

            const matchesStatus =
                selectedStatus === 'all' || status === selectedStatus;

            row.style.display =
                matchesSearch && matchesRole && matchesStatus ? '' : 'none';

            // Hide the Edit Employee form row together with its employee row
            const editRow = row.nextElementSibling;

            if (editRow) {
                editRow.style.display = row.style.display;
            }
        });
    }

    // Search by User ID or name
    employeeSearch.addEventListener('input', filterEmployees);

    // Filter by account status
    employeeStatus.addEventListener('change', filterEmployees);

    // Filter by role buttons
    roleButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            selectedRole = this.dataset.role;

            roleButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });

            this.classList.add('active');

            filterEmployees();
        });
    });
</script>

@endsection