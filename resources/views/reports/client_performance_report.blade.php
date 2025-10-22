@extends('layouts.custom')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Client Performance Report</h5>
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex align-items-center ms-auto">
            <a href="/client_performance_report/generate" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
                <i class="fas fa-download fa text-white"></i> Generate Report
            </a>

        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="dataTable">
                <thead class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Client Name</th>
                        <th>Shipments (#)</th>
                        <th>Total Weight (Kg)</th>
                        <th>Revenue (Ksh)</th>
                        <th>Avg. Revenue/Shipment (Ksh)</th>
                        <!-- <th>Payment mix</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Client Name</th>
                        <th>Shipments (#)</th>
                        <th>Total Weight (Kg)</th>
                        <th>Revenue (Ksh)</th>
                        <th>Avg. Revenue/Shipment (Ksh)</th>
                        <!-- <th>Payment mix</th> -->
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody class="text-primary">
                    @forelse ($clients as $client)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $client->name }}</td>
                            <td style="text-align: center;">{{ $client->items_count }}</td>
                            <td style="text-align: center;">{{ $client->total_weight ?? 0 }}</td>
                            <td style="text-align: right;">{{ number_format($client->total_revenue ?? 0, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($client->avg_revenue_per_shipment ?? 0, 2)}}</td>
                            <!-- <td>
                                @foreach($client->payment_mix as $mode => $percentage)
                                    {{ $mode }}: {{ $percentage }} <br>
                                @endforeach
                            </td> -->
                            <td>
                                <a href="{{ route('reports.client.detail', $client->id) }}" 
                                class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="13" class="text-center">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

