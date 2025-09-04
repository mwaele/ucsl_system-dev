<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-primary">CoD & Cash Reports</h6>
        <a href="{{ route('reports.export.cod') }}" class="btn btn-sm btn-danger">
            <i class="fas fa-download"></i> Export PDF
        </a>
    </div>

    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-5">
                    <select name="payment_mode" class="form-control">
                        <option value="">-- Select Mode --</option>
                        <option value="COD" {{ request('payment_mode') == 'COD' ? 'selected' : '' }}>Cash on Delivery</option>
                        <option value="Cash" {{ request('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
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
                        <th>Client</th>
                        <th>Mode</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Collected By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($codReports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $report->requestId }}</td>
                            <td>{{ $report->client->name ?? 'N/A' }}</td>
                            <td>{{ $report->payment_mode }}</td>
                            <td>KES {{ number_format($report->amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $report->status == 'settled' ? 'success' : 'warning' }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                            <td>{{ $report->collectedBy->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
