@extends('layouts.custom')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Office Performance Report</h5>
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex align-items-center ms-auto">
            <a href="/office_performance_report/generate" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
                <i class="fas fa-download fa text-white"></i> Generate Report
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="dataTable">
                <thead class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Office Name</th>
                        <th style="text-align: center;">Total Shipments</th>
                        <th style="text-align: center;">Total Weight (Kg)</th>
                        <th style="text-align: right;">Total Revenue (Ksh)</th>
                        <th style="text-align: right;">Avg. Revenue/Shipment (Ksh)</th>
                        <!-- <th>Payment mix</th> -->
                        <th>Premium parcels (Fragile/Priority)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Office Name</th>
                        <th style="text-align: center;">Total Shipments</th>
                        <th style="text-align: center;">Total Weight (Kg)</th>
                        <th style="text-align: right;">Total Revenue (Ksh)</th>
                        <th style="text-align: right;">Avg. Revenue/Shipment (Ksh)</th>
                        <!-- <th>Payment mix</th> -->
                        <th>Premium parcels (Fragile/Priority)</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody class="text-primary">
                    @forelse ($offices as $office)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $office->name }}</td>
                            <td style="text-align: center;">{{ $office->total_shipments }}</td>
                            <td style="text-align: center;">{{ $office->total_weight }}</td>
                            <td style="text-align: right;">{{ number_format($office->total_revenue, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($office->avg_revenue_per_shipment, 2) }}</td>
                            <!-- <td>
                                @foreach ($office->payment_mix as $mode => $percent)
                                    {{ $mode }}: {{ $percent }}%<br>
                                @endforeach
                            </td> -->
                            <td>{{ $office->premium_services }}</td>
                            <td>
                                <a href="{{ route('reports.office.detail', $office->id) }}" 
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

