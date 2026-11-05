@extends('layout.app')

@section('content')

<div class="maintenance-container">

    <section class="maintenance-header">
        <h1>Report Bus Breakdown</h1>
        <p>Fill in the details below so the maintenance team can respond.</p>
    </section>

    <section class="report-form-section">
        <form method="POST" action="{{ route('breakdown-report.store') }}" enctype="multipart/form-data">
            @csrf

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-modal-overlay" id="errorModal">
                    <div class="error-modal">

                        <div class="error-icon">
                            !
                        </div>

                        <h2>Unable to Submit Report</h2>

                        <p>Please check the following problem:</p>

                        <div class="error-list">
                            @foreach ($errors->all() as $error)
                                <p>
                                    @if (str_contains($error, 'failed to upload'))
                                        One or more pictures could not be uploaded. Please check that each picture does not exceed 5 MB and try again.
                                    @else
                                        {{ $error }}
                                    @endif
                                </p>
                            @endforeach
                        </div>

                        <button type="button" onclick="closeErrorModal()">
                            Fix Report
                        </button>

                    </div>
                </div>
            @endif

            <div class="form-grid">
                <div class="form-group">
                    <label for="bus_id">Bus ID</label>
                    <input type="text" name="bus_id" id="bus_id" value="{{ old ('bus_id') }}" required>
                </div>

                <div class="form-group">
                    <label for="user_id">Employee ID</label>
                    <input type="text" name="user_id" id="user_id" value="{{ Auth::user()->user_id }}" readonly>
                </div>

                <div class="form-group">
                    <label for="report_date">Date</label>
                    <input type="date" name="report_date" id="report_date" value="{{ old ('report_date') }}" required>
                </div>

                <div class="form-group">
                    <label for="report_time">Time of Breakdown</label>
                    <input type="time" name="report_time" id="report_time" value="{{ old ('report_time') }}" required>
                </div>

                <div class="form-group full">
                    <label for="place">Place of Breakdown</label>
                    <input type="text" name="place" id="place" placeholder="Cyberjaya, Selangor" value="{{ old ('place') }}" required>
                </div>

                <div class="form-group full">
                    <label for="description">Brief Description</label>
                    <textarea name="description" id="description"  rows="5"
                        placeholder="The bus suddenly started losing power and stopped." required>{{ old ('description') }}</textarea>
                </div>

                <div class="form-group full">
                    <label for="pics">Pictures of Bus</label>
                    <input type="file" name="pics[]" id="pics" accept="image/*" multiple>
                    <small>You may upload the pictures of the bus damage.</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit">Submit Report</button>
            </div>
        </form>
    </section>

    <a href="{{ url('/home') }}" class="btn-main">Back to Homepage</a>

</div>

<script>
    function closeErrorModal() {
        const modal = document.getElementById('errorModal');

        if (modal) {
            modal.remove();
        }
    }
</script>

@endsection