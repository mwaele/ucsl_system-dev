@extends('layouts.custom')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Dispatch Summaries Reports</h5>
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex align-items-center ms-auto">
            <a href="/dispatch_summary_report/generate" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
                <i class="fas fa-download fa text-white"></i> Generate Report
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="dataTable">
                <thead class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Office</th>
                        <th style="text-align: center;">Total Dispatches</th>
                        <th style="text-align: right;">Total Shipments</th>
                        <th style="text-align: right;">Total Weight (kg)</th>
                        <th style="text-align: right;">Total Revenue (Ksh)</th>
                        <th style="text-align: center;">Avg Revenue/Shipment (Ksh)</th>
                        <!-- <th>Payment Mix</th> -->
                        <th style="text-align: right;">Premium Services</th>
                        <!-- <th>Destination Breakdown</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Office</th>
                        <th style="text-align: center;">Total Dispatches</th>
                        <th style="text-align: right;">Total Shipments</th>
                        <th style="text-align: right;">Total Weight (kg)</th>
                        <th style="text-align: right;">Total Revenue (Ksh)</th>
                        <th style="text-align: center;">Avg Revenue/Shipment (Ksh)</th>
                        <!-- <th>Payment Mix</th> -->
                        <th style="text-align: right;">Premium Services</th>
                        <!-- <th>Destination Breakdown</th> -->
                         <th>Action</th>
                    </tr>
                </tfoot>
                <tbody class="text-primary">
                    @forelse ($offices as $office)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $office->name }}</td>
                            <td style="text-align: center;">{{ $office->total_dispatches }}</td>
                            <td style="text-align: right;">{{ $office->total_shipments }}</td>
                            <td style="text-align: right;">{{ number_format($office->total_weight, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($office->total_revenue, 2) }}</td>
                            <td style="text-align: center;">{{ number_format($office->avg_revenue_per_shipment, 2) }}</td>
                            <!-- <td>
                                @foreach($office->payment_mix as $mode => $percentage)
                                    {{ $mode ?? 'N/A' }}: {{ $percentage }}% <br>
                                @endforeach
                            </td> -->
                            <td style="text-align: right;">{{ $office->premium_services }}</td>
                            <!-- <td>
                                <ul class="mb-0">
                                    @foreach($office->destination_breakdown as $dest)
                                        <li>{{ $dest['office'] }}: {{ $dest['shipments'] }} (KES {{ number_format($dest['revenue'], 2) }})</li>
                                    @endforeach
                                </ul>
                            </td> -->
                            <td>
                                <a href="{{ route('reports.dispatch-summary.detail', $office->id) }}" 
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

