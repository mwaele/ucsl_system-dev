<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-primary">Driver Shipment Analysis</h6>
        <a href="{{ route('reports.export.driver') }}" class="btn btn-sm btn-danger">
            <i class="fas fa-download"></i> Export PDF
        </a>
    </div>

    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="driver_id" class="form-control">
                        <option value="">-- Select Driver --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-block btn-sm">Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Driver</th>
                        <th>Vehicle</th>
                        <th>Parcels</th>
                        <th>Total Weight</th>
                        <th>Revenue</th>
                        <th>Destination</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($driverReports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $report->driver->name ?? 'N/A' }}</td>
                            <td>{{ $report->vehicle->plate_no ?? 'N/A' }}</td>
                            <td>{{ $report->parcels_count }}</td>
                            <td>{{ $report->total_weight }} kg</td>
                            <td>KES {{ number_format($report->revenue, 2) }}</td>
                            <td>{{ $report->destination }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
