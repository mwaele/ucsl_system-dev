<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-primary">Parcel Collection Report</h6>
        <a href="{{ route('reports.export.collection') }}" class="btn btn-sm btn-danger">
            <i class="fas fa-download"></i> Export PDF
        </a>
    </div>

    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-5">
                    <select name="rider_id" class="form-control">
                        <option value="">-- Select Rider --</option>
                        @foreach($riders as $rider)
                            <option value="{{ $rider->id }}" {{ request('rider_id') == $rider->id ? 'selected' : '' }}>
                                {{ $rider->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
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
                        <th>Rider</th>
                        <th>Sender</th>
                        <th>Items</th>
                        <th>Collected At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($collectionReports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $report->requestId }}</td>
                            <td>{{ $report->rider->name ?? 'N/A' }}</td>
                            <td>{{ $report->client->name ?? 'N/A' }}</td>
                            <td>{{ $report->items_count }}</td>
                            <td>{{ $report->collected_at ? $report->collected_at->format('Y-m-d H:i') : 'Pending' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
