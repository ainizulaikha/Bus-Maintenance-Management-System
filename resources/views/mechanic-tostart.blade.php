
@extends('layout.app')

@section('content')

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

<div class="maintenance-container">

    <section class="maintenance-header">
        <h1>Pending Tasks</h1>
        <p>View maintenance tasks waiting to be started.</p>
    </section>

    <nav class="task-nav">
        <a href="{{ url('/mechanic-maintenance') }}">All Tasks</a>
        <a href="{{ url('/mechanic-tostart') }}">To Start</a>
        <a href="{{ url('/mechanic-inprogress') }}">In Progress</a>
        <a href="{{ url('/mechanic-history') }}">History</a>
    </nav>

    <section class="assigned-tasks">
        <h2>Pending Maintenance Tasks</h2>

        <table class="maintenance-table">
            <thead>
                <tr>
                    <th>Task ID</th>
                    <th>Bus ID</th>
                    <th>Task Details</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($reports as $task)
                    <tr>
                        <td>{{ $task->report_id }}</td>
                        <td>{{ $task->bus_id }}</td>
                        <td>{{ $task->description }}</td>
                        <td>
                            <span class="task-status">{{ $task->status }}</span>
                        </td>
                        <td>
                            <button type="button"
                                    class="btn-update-task"
                                    data-task-id="{{ $task->report_id }}"
                                    data-bus-id="{{ $task->bus_id }}"
                                    data-problem="{{ $task->description }}"
                                    data-status="{{ $task->status }}"
                                    data-notes="{{ $task->repair_notes }}"
                                    onclick="openMaintenanceModal(this)">
                                Update
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">
                            No tasks waiting to be started.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>

<div id="maintenanceModal" class="modal-overlay" style="display: none;">
    <div class="maintenance-modal" role="dialog"
         aria-modal="true" aria-labelledby="modalTitle">

        <div class="modal-heading">
            <div>
                <h2 id="modalTitle">Update Maintenance Status</h2>
                <p>Update the progress of your assigned task.</p>
            </div>

            <button type="button" class="modal-close"
                    onclick="closeMaintenanceModal()"
                    aria-label="Close">&times;</button>
        </div>

        <form id="maintenanceUpdateForm" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="modalTaskId">Task ID</label>
                <input type="text" id="modalTaskId" readonly>
            </div>

            <div class="form-group">
                <label for="modalBusId">Bus ID</label>
                <input type="text" id="modalBusId" readonly>
            </div>

            <div class="form-group">
                <label for="modalProblem">Task Details</label>
                <input type="text" id="modalProblem" readonly>
            </div>

            <div class="form-group">
                <label for="modalStatus">Maintenance Status</label>
                <select id="modalStatus" name="status" required>
                    <option value="Approved">Approved</option>
                    <option value="In Progress">In Progress</option>
                </select>
            </div>

            <div class="form-group">
                <label for="modalNotes">Repair Notes</label>
                <textarea id="modalNotes" name="repair_notes" rows="4"
                          placeholder="Enter repair details or work completed..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel"
                        onclick="closeMaintenanceModal()">
                    Cancel
                </button>

                <button type="submit">Save Changes</button>
            </div>

        </form>
    </div>
</div>

<script>
    let selectedTaskButton = null;

    function openMaintenanceModal(button) {
        selectedTaskButton = button;

        document.getElementById('maintenanceUpdateForm').action =
            "{{ url('/mechanic-maintenance') }}/" +
            encodeURIComponent(button.dataset.taskId) + "/update";
        document.getElementById('modalTaskId').value =
            button.dataset.taskId;
        document.getElementById('modalBusId').value =
            button.dataset.busId;
        document.getElementById('modalProblem').value =
            button.dataset.problem;
        document.getElementById('modalStatus').value =
            button.dataset.status;
        document.getElementById('modalNotes').value =
            button.dataset.notes || '';

        document.getElementById('maintenanceModal').style.display = 'flex';
    }

    function closeMaintenanceModal() {
        document.getElementById('maintenanceModal').style.display = 'none';
        selectedTaskButton = null;
    }

    document.getElementById('maintenanceModal')
        .addEventListener('click', function (event) {
            if (event.target === this) {
                closeMaintenanceModal();
            }
        });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMaintenanceModal();
        }
    });

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
            setTimeout(() => successToast.remove(), 4000);
        }

        if (errorToast) {
            setTimeout(() => errorToast.remove(), 4000);
        }
    });
</script>

@endsection
