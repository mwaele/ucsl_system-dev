@extends('layouts.custom')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Client Detail Report - {{ $client->name }}</h5>
            <a href="{{ route('reports.office_performance') }}" 
            class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Client Summary -->
        <div class="mb-4">
            <h6 class="text-primary">Summary</h6>
            <ul>
                <li><strong>Total Shipments:</strong> {{ $totalShipments }}</li>
                <li><strong>Total Weight:</strong> {{ $totalWeight }} Kg</li>
                <li><strong>Total Revenue:</strong> Ksh. {{ number_format($totalRevenue, 2) }}</li>
                <li><strong>Avg Revenue/Shipment:</strong> Ksh. {{ number_format($avgRevenue, 2) }}</li>
                <li>
                    <strong>Payment Mix:</strong><br>
                    @forelse($paymentCounts as $mode => $percent)
                        {{ $mode }}: {{ $percent }}% <br>
                    @empty
                        <span>No payments recorded</span>
                    @endforelse
                </li>
            </ul>
        </div>

        <!-- Shipments Table -->
        <div class="table-responsive">
            <h6 class="text-primary">Shipment History</h6>
            <table class="table table-bordered table-striped table-hover">
                <thead class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Request ID</th>
                        <th>Date</th>
                        <th>Items (Qty)</th>
                        <th>Total Weight (Kg)</th>
                        <th>Revenue</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="text-primary">
                    @forelse($client->shipmentCollections as $shipment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $shipment->requestId }}</td>
                            <td>{{ $shipment->created_at->format('d M Y') }}</td>
                            <td>{{ $shipment->items->count() }}</td>
                            <td>{{ $shipment->items->sum('weight') }}</td>
                            <td style="text-align: right;">{{ number_format($shipment->payments->sum('amount'), 2) }}</td>
                            <td>{{ ucfirst($shipment->status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No shipments found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
