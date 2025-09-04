<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-primary">Rider Performance Report</h6>
        <a href="{{ route('reports.export.rider') }}" class="btn btn-sm btn-danger">
            <i class="fas fa-download"></i> Export PDF
        </a>
    </div>

    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <input type="month" name="month" class="form-control" value="{{ request('month') }}">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-block btn-sm">Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Rider</th>
                        <th>Parcels Collected</th>
                        <th>Total Weight</th>
                        <th>Revenue Generated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riderReports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $report->rider_name }}</td>
                            <td>{{ $report->parcels_count }}</td>
                            <td>{{ $report->total_weight }} kg</td>
                            <td>KES {{ number_format($report->revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
