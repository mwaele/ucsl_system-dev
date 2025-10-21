@extends('layouts.custom')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-success mb-0">Dispatch Summary – {{ $office->name }}</h5>
            <a href="{{ route('reports.dispatch_summary') }}" 
                class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- 🟢 Row: Office Summary + Payment Mix -->
        <div class="row mb-4 text-md">
            <!-- 🏢 Office Summary -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        Office Summary
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0" id="dataTable">
                            <tbody>
                                <tr>
                                    <th>Total Dispatches:</th>
                                    <td>{{ $office->total_dispatches ?? $office->clientRequests->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Total Shipments:</th>
                                    <td>{{ $office->total_shipments }}</td>
                                </tr>
                                <tr>
                                    <th>Total Weight (kg):</th>
                                    <td>{{ number_format($office->total_weight, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Total Revenue (Ksh):</th>
                                    <td>{{ number_format($office->total_revenue, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Avg Revenue/Shipment (Ksh):</th>
                                    <td>{{ number_format($office->avg_revenue_per_shipment, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Premium Services:</th>
                                    <td>{{ $office->premium_services }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 💳 Payment Mix -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        Payment Modes
                    </div>
                    <div class="card-body">
                        @if($office->payment_mix->isNotEmpty())
                            <table class="table table-sm table-striped table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Payment Mode</th>
                                        <th style="text-align: right;">Share (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($office->payment_mix as $mode => $percentage)
                                        <tr>
                                            <td>{{ $mode ?? 'N/A' }}</td>
                                            <td style="text-align: right;">{{ $percentage }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted mb-0">No payment data available for this period.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 🚚 Destination Breakdown -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <span>Destination Breakdown</span>
                <small class="text-white-100">Grouped by Destination Office</small>
            </div>
            <div class="card-body">
                @if($office->destination_breakdown->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="vertical-scroll">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Destination Office</th>
                                    <th style="text-align: right;">Total Shipments</th>
                                    <th style="text-align: right;">Total Weight (kg)</th>
                                    <th style="text-align: right;">Total Revenue (Ksh)</th>
                                    <th style="text-align: right;">Avg Revenue/Shipment (Ksh)</th>
                                    <th style="text-align: center;">Premium Shipments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($office->destination_breakdown as $index => $dest)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $dest['office'] }}</td>
                                        <td style="text-align: right;">{{ $dest['shipments'] }}</td>
                                        <td style="text-align: right;">
                                            {{ number_format($dest['weight'] ?? 0, 2) }}
                                        </td>
                                        <td style="text-align: right;">
                                            {{ number_format($dest['revenue'], 2) }}
                                        </td>
                                        <td style="text-align: right;">
                                            {{ number_format(($dest['revenue'] / max(1, $dest['shipments'])), 2) }}
                                        </td>
                                        <td style="text-align: center;">
                                            {{ $dest['premium'] ?? 0 }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No destination breakdown available for this period.</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

