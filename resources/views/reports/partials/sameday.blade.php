<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-primary">Sameday & Overnight Report</h6>
        <a href="" class="btn btn-sm btn-danger">
            <i class="fas fa-download"></i> Export PDF
        </a>
    </div>

    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-5">
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-5">
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block btn-sm">Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Request ID</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Destination</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($samedayReports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $report->requestId }}</td>
                            <td>{{ $report->client->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($report->type) }}</td>
                            <td>{{ $report->destination }}</td>
                            <td>{{ ucfirst($report->status) }}</td>
                            <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
