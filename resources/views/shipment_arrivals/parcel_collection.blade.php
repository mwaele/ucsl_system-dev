{{-- resources/views/shipment_arrivals/parcel_collection.blade.php --}}
@extends('layouts.custom')

@section('content')
<div class="card">

    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Parcel Collection</h5>

            <div class="d-flex gap-2 ms-auto">
                <a href="{{ url('/parcel-collection-report') }}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
                    <i class="fas fa-download fa text-white"></i> Generate Report
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-primary table-bordered table-striped table-hover" id="ucsl-table" width="100%"
                cellspacing="0" style="font-size: 14px;">
                <thead>
                    <tr class="text-success">
                        <th>#</th>
                        <th>Request ID</th>
                        <th>Date Received</th>
                        <th>Verified By</th>
                        <th>Total Cost</th>
                        <th>Status</th>
                        <th>Driver Name</th>
                        <th>Vehicle Reg No</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shipmentArrivals as $index => $arrival)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $arrival->requestId }}</td>
                            <td>{{ $arrival->date_received }}</td>
                            <td>{{ $arrival->verifiedBy->name ?? null }}</td>
                            <td>Ksh. {{ number_format($arrival->total_cost) }}</td>
                            <td>
                                <span class="badge badge-{{ strtolower($arrival->status) === 'delivered' ? 'success' : 'warning' }}">
                                    {{ ucfirst($arrival->status) }}
                                </span>
                            </td>
                            <td>{{ $arrival->driver_name }}</td>
                            <td>{{ $arrival->vehicle_reg_no }}</td>
                            <td>
                                @if(in_array(strtolower($arrival->payment_mode), ['cash-on-delivery', 'on account']))
                                    <button class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#paymentModal-{{ $arrival->id }}">
                                        Record Payment
                                    </button>
                                @else
                                    <span class="badge badge-info">Paid</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
