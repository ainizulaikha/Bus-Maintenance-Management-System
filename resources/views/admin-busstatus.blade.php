@extends('layout.app')

@section('content')

@if (session('success'))
    <div id="successToast" class="success-toast">
        <span class="success-toast-icon">✓</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="maintenance-container">

    <section class="maintenance-header">
        <h1>Manage Bus Availability</h1>
        <p>View and manage the availability status of buses.</p>
    </section>

    <!-- Bus Status Summary -->
    <section class="bus-status-summary">
        <div class="status-card">
            <h2>Total Buses</h2>
            <p>{{ $totalBuses }}</p>
        </div>

        <div class="status-card">
            <h2>Available</h2>
            <p>{{ $availableCount }}</p>
        </div>

        <div class="status-card">
            <h2>Standby</h2>
            <p>{{ $standbyCount }}</p>
        </div>

        <div class="status-card">
            <h2>Under Maintenance</h2>
            <p>{{ $maintenanceCount }}</p>
        </div>
    </section>

    <!-- Bus Management Actions -->
    <section class="bus-management-actions">

        <button type="button" class="bus-action-card add-bus-button"
            onclick="openAddBusModal()">

            <span class="bus-action-icon">🚌</span>

            <span class="bus-action-title">Add New Bus</span>

            <span class="bus-action-description">
                Register a new bus into the system
            </span>
        </button>

        <button type="button" class="bus-action-card edit-route-button"
            onclick="openEditRouteModal()">

            <span class="bus-action-icon"> 📍</span>

            <span class="bus-action-title">Edit Bus Route</span>

            <span class="bus-action-description">
                Modify an existing bus route
            </span>

        </button>
    </section>

    <form id="busSearchForm" method="GET" action="{{ url('/admin-busstatus') }}" class="bus-search-filter" onsubmit="return false;">
        <div class="bus-search-heading">
            <div class="bus-search-heading-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <div>
                <h2>Find a Bus</h2>
                <p>Search and filter bus availability records.</p>
            </div>
        </div>

        <div class="bus-search-controls">

            <div class="bus-search-field">
                <label for="busSearchInput">Search Bus</label>
                <div class="bus-search-input-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                        type="text"
                        id="busSearchInput"
                        name="search"
                        placeholder="Search Bus ID or Route..."
                        value="{{ request('search') }}"
                    >
                </div>
            </div>

            <div class="bus-filter-field">
                <label for="busStatusFilter">Availability Status</label>
                <select name="status" id="busStatusFilter">
                    <option value="">All Availability</option>
                    <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Standby" {{ request('status') == 'Standby' ? 'selected' : '' }}>Standby</option>
                    <option value="Under Maintenance" {{ request('status') == 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                </select>
            </div>

            <div class="bus-search-buttons">
                <button type="submit" class="bus-search-submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Search
                </button>

                <a href="{{ url('/admin-busstatus') }}" class="bus-search-reset">
                    <i class="fa-solid fa-rotate-left"></i>
                    Reset
                </a>
            </div>

        </div>
    </form>

    <!-- Bus Availability Table -->
    <section class="maintenance-table-section">

        <h2>Bus Availability</h2>

        <table class="maintenance-table">
            <thead>
                <tr>
                    <th>Bus ID</th>
                    <th>Route</th>
                    <th>Current Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($buses as $bus)
                    <tr class="bus-row"
                        data-bus-id="{{ strtolower($bus->bus_id) }}"
                        data-route="{{ strtolower($bus->route) }}"
                        data-status="{{ $bus->status }}">

                        <td>{{ $bus->bus_id }}</td>
                        <td>{{ $bus->route }}</td>
                        <td>{{ $bus->status }}</td>
                        <td>
                            <button type="button"
                                class="btn-main"
                                onclick="openUpdateModal(
                                    '{{ $bus->id }}',
                                    '{{ $bus->bus_id }}',
                                    '{{ addslashes($bus->route) }}',
                                    '{{ $bus->status }}'
                                )">
                                Update
                            </button>
                            <form action="{{ route('admin-busstatus.delete', $bus->id) }}"
                                method="POST"
                                style="display: inline;"
                                onsubmit="return confirm('Are you sure you want to delete bus {{ $bus->bus_id }}? This action cannot be undone.');">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-delete">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No bus records available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <a href="{{ url('/home') }}" class="btn-main">Back to Homepage</a>

</div>

<!-- Update Bus Availability Modal -->
<div id="updateBusModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h2>Update Bus Availability</h2>
        <p>Update the availability status of this bus.</p>

        <form id="updateBusForm" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Bus ID</label>
                <input type="text" id="modalBusID" readonly>
            </div>

            <div class="form-group">
                <label>Route</label>
                <input type="text" id="modalBusRoute" readonly>
            </div>

            <div class="form-group">
                <label>Availability Status</label>

                <select name="status" id="modalBusStatus" required>
                    <option value="Available">Available</option>
                    <option value="Standby">Standby</option>
                    <option value="Under Maintenance">Under Maintenance</option>
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeUpdateModal()">
                    Cancel
                </button>

                <button type="submit" class="btn-main">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add New Bus Modal -->
<div id="addBusModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h2>Add New Bus</h2>
        <p>Register a new bus into the system.</p>

        <form method="POST" action="{{ route('admin-busstatus.store') }}">
            @csrf

            <div class="form-group">
                <label>Bus ID</label>
                <input type="text" value="Automatically Generated" readonly>
            </div>

            <div class="form-group">
                <label>Bus Route</label>
                <input
                    type="text"
                    name="route"
                    placeholder="Example: Cyberjaya - Putrajaya Sentral"
                    maxlength="255"
                    required>
            </div>

            <div class="form-group">
                <label>Availability Status</label>

                <select name="status" required>
                    <option value="Available">Available</option>
                    <option value="Standby">Standby</option>
                    <option value="Under Maintenance">Under Maintenance</option>
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeAddBusModal()">
                    Cancel
                </button>

                <button type="submit" class="btn-main">
                    Add Bus
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Bus Route Modal -->
<div id="editRouteModal" class="modal-overlay" style="display: none;">

    <div class="modal-content">

        <h2>Edit Bus Route</h2>
        <p>Select a bus and update its route.</p>

        <form id="editRouteForm" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Select Bus</label>

                <select id="editRouteBusID" required>
                    <option value="">-- Select Bus ID --</option>

                    @foreach ($buses as $bus)
                        <option
                            value="{{ $bus->id }}"
                            data-route="{{ $bus->route }}">
                            {{ $bus->bus_id }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="form-group">
                <label>Current Route</label>

                <input
                    type="text"
                    id="currentBusRoute"
                    readonly
                    placeholder="Select a bus first">
            </div>

            <div class="form-group">
                <label>New Route</label>

                <input
                    type="text"
                    name="route"
                    id="newBusRoute"
                    maxlength="255"
                    placeholder="Enter the new bus route"
                    required>
            </div>

            <div class="modal-actions">

                <button type="button" onclick="closeEditRouteModal()">
                    Cancel
                </button>

                <button type="submit" id="saveRouteButton">
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    let originalBusStatus = '';

    function openUpdateModal(id, busID, route, status) {
        document.getElementById('modalBusID').value = busID;
        document.getElementById('modalBusRoute').value = route;
        document.getElementById('modalBusStatus').value = status;

        originalBusStatus = status;

        document.getElementById('updateBusForm').action =
            '/admin-busstatus/' + id + '/update';

        updateSaveButton();

        document.getElementById('updateBusModal').style.display = 'flex';
    }

    function updateSaveButton() {
        const currentStatus = document.getElementById('modalBusStatus').value;
        const saveButton = document.querySelector('#updateBusForm button[type="submit"]');

        if (currentStatus !== originalBusStatus) {
            saveButton.classList.add('has-changes');
        } else {
            saveButton.classList.remove('has-changes');
        }
    }

    document.getElementById('modalBusStatus').addEventListener('change', updateSaveButton);

    function closeUpdateModal() {
        document.getElementById('updateBusModal').style.display = 'none';
    }

    document.getElementById('updateBusModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeUpdateModal();
        }
    });

    function openAddBusModal() {
        document.getElementById('addBusModal').style.display = 'flex';
    }

    function closeAddBusModal() {
        document.getElementById('addBusModal').style.display = 'none';
    }

    document.getElementById('addBusModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeAddBusModal();
        }
    });

    let originalBusRoute = '';

    function openEditRouteModal() {
        const modal = document.getElementById('editRouteModal');
        const busSelect = document.getElementById('editRouteBusID');

        busSelect.value = '';
        document.getElementById('currentBusRoute').value = '';
        document.getElementById('newBusRoute').value = '';

        originalBusRoute = '';

        updateRouteSaveButton();

        modal.style.display = 'flex';
    }

    function closeEditRouteModal() {
        document.getElementById('editRouteModal').style.display = 'none';
    }

    document.getElementById('editRouteBusID').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        const route = selectedOption.dataset.route || '';

        document.getElementById('currentBusRoute').value = route;
        document.getElementById('newBusRoute').value = route;

        originalBusRoute = route;

        const busId = this.value;

        if (busId) {
            document.getElementById('editRouteForm').action =
                '/admin-busstatus/' + busId + '/edit-route';
        }

        updateRouteSaveButton();
    });

    function updateRouteSaveButton() {
        const newRoute = document.getElementById('newBusRoute').value.trim();
        const saveButton = document.getElementById('saveRouteButton');

        if (newRoute !== originalBusRoute.trim() && originalBusRoute !== '') {
            saveButton.classList.add('has-changes');
        } else {
            saveButton.classList.remove('has-changes');
        }
    }

    document.getElementById('newBusRoute').addEventListener('input', updateRouteSaveButton);

    document.getElementById('editRouteModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeEditRouteModal();
        }
    });

    // Live Bus Search and Filter
    const busSearchInput = document.getElementById('busSearchInput');
    const busStatusFilter = document.getElementById('busStatusFilter');
    const busRows = document.querySelectorAll('.bus-row');

    function filterBuses() {
        const searchValue = busSearchInput.value.toLowerCase().trim();
        const selectedStatus = busStatusFilter.value;

        busRows.forEach(function(row) {
            const busID = row.dataset.busId;
            const route = row.dataset.route;
            const status = row.dataset.status;

            const matchesSearch =
                busID.includes(searchValue) ||
                route.includes(searchValue);

            const matchesStatus =
                selectedStatus === '' || status === selectedStatus;

            row.style.display =
                matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    busSearchInput.addEventListener('input', filterBuses);
    busStatusFilter.addEventListener('change', filterBuses);
</script>

<script>
    const successToast = document.getElementById('successToast');

    if (successToast) {
        setTimeout(function () {
            successToast.classList.add('hide');
        }, 3000);

        setTimeout(function () {
            successToast.remove();
        }, 3500);
    }
</script>

@endsection