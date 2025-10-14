@extends('layouts.custom')

@section('content')
    <div class=" mt-5">
        <h3 class="mb-4 text-center fw-bold text-primary">Rider Performance Shipment Report</h3>

        <!-- Filters -->
        <form id="filterForm" class="row g-3 mb-4 bg-light p-3 rounded shadow-sm">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label for="user_name" class="form-label">User Name</label>
                <input type="text" name="user_name" id="user_name" value="{{ $userName }}" class="form-control"
                    placeholder="Search by user name">
            </div>
            {{-- <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div> --}}
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 mr-2">Filter</button>
                <a href="#" id="clearFilter" class="btn btn-info w-100 mr-2">Clear </a>
                <a href="#" id="exportPdfBtn" class="btn btn-danger w-100 mr-2"><i
                        class="fas fa-download fa text-white"></i> PDF </a>
                <a href="{{ route('reports.rider-performance-export-excel', [
                    'start_date' => request('start_date'),
                    'end_date' => request('end_date'),
                    'user_name' => request('user_name'),
                ]) }}"
                    class="btn btn-success w-100"><i class="fas fa-download fa text-white"></i> Excel </a>
            </div>

        </form>

        <!-- Table -->
        <div class="table-responsive shadow-sm">
            <table class="table table-bordered text-primary align-middle" id="reportTable">
                <thead class="text-success text-center">
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Total Shipments</th>
                        <th>Total Amount (KES)</th>
                        <th>Total Volume (KG)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report as $index => $item)
                        <tr class="text-center">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->total_shipments }}</td>
                            <td>{{ number_format($item->total_amount, 2) }}</td>
                            <td>{{ number_format($item->total_volume, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();

                let formData = {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                    user_name: $('#user_name').val(),
                };

                $.ajax({
                    url: "{{ route('reports.rider-performance') }}",
                    method: "GET",
                    data: formData,
                    beforeSend: function() {
                        $('#reportTable tbody').html(
                            '<tr><td colspan="5" class="text-center text-muted">Loading...</td></tr>'
                        );
                    },
                    success: function(response) {
                        if (response.length === 0) {
                            $('#reportTable tbody').html(
                                '<tr><td colspan="5" class="text-center text-muted">No data found.</td></tr>'
                            );
                        } else {
                            let rows = '';
                            $.each(response, function(index, item) {
                                rows += `
                            <tr class="text-center">
                                <td>${index + 1}</td>
                                <td>${item.name}</td>
                                <td>${item.total_shipments}</td>
                                <td>${parseFloat(item.total_amount).toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                <td>${parseFloat(item.total_volume).toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                            </tr>
                        `;
                            });
                            $('#reportTable tbody').html(rows);
                        }
                    },
                    error: function() {
                        $('#reportTable tbody').html(
                            '<tr><td colspan="5" class="text-center text-danger">An error occurred. Please try again.</td></tr>'
                        );
                    }
                });
            });
            // PDF Export Handler
            $('#exportPdfBtn').on('click', function(e) {
                e.preventDefault();
                let start_date = $('#start_date').val();
                let end_date = $('#end_date').val();
                let user_name = $('#user_name').val();

                let url = "{{ route('shipment.report.pdf') }}" +
                    `?start_date=${start_date}&end_date=${end_date}&user_name=${user_name}`;
                window.open(url, '_blank');
            });
            $('#clearFilter').on('click', function(e) {
                e.preventDefault();
                window.location.href = "{{ route('reports.rider-performance') }}";
            });

        });
    </script>
@endsection
