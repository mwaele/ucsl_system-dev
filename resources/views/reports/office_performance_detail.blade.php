@extends('layouts.custom')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Office Performance Detail – {{ $office->name }}</h5>

            <div class="d-flex align-items-center">
                <div class="mb-3 mr-3">
                    <label class="form-label text-primary">Filter by Payment Mode</label>
                    <select id="paymentFilter" class="form-control" style="width: 200px;">
                        <option value="">All</option>
                        <option value="Invoice">Invoice</option>
                        <option value="M-Pesa">M-Pesa</option>
                        <option value="Cash">Cash</option>
                    </select>
                </div>
                
                <a href="{{ route('reports.office_performance_detail_pdf', $office->id) }}" 
                    class="btn btn-sm btn-danger shadow-sm mr-3">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
                <a href="{{ route('reports.client_performance') }}" 
                    class="btn btn-sm btn-secondary shadow-sm mr-3">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="dataTable">
                <thead class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Request ID</th>
                        <th>Consignment No</th>
                        <th>Waybill No</th>
                        <th>Receiver</th>
                        <th>Weight (Kg)</th>
                        <th>Revenue (KES)</th>
                        <th>Payment Mode</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tfoot class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Request ID</th>
                        <th>Consignment No</th> 6km
                        <th>Waybill No</th>
                        <th>Receiver</th>
                        <th>Weight (Kg)</th>
                        <th>Revenue (KES)</th>
                        <th>Payment Mode</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </tfoot>
                <tbody class="text-primary">
                    @forelse($shipments as $shipment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $shipment->requestId }}</td>
                            <td>{{ $shipment->consignment_no }}</td>
                            <td>{{ $shipment->waybill_no }}</td>
                            <td>{{ $shipment->receiver_name }} ({{ $shipment->receiver_phone }})</td>
                            <td>{{ $shipment->total_weight }}</td>
                            <td>Ksh {{ number_format($shipment->actual_total_cost, 2) }}</td>
                            <td>{{ $shipment->payment_mode }}</td>
                            <td>{{ ucfirst($shipment->status) }}</td>
                            <td>{{ \Carbon\Carbon::parse($shipment->created_at)->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No shipments found for this office</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
