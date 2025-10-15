@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap" style="font-size: 14px;">
                <!-- Left: Title -->
                <span class="m-0 font-weight-bold text-success">

                    @if ($status)
                        <h4>
                            Showing shipment results for:
                            <strong>{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $status)) }}</strong>
                        </h4>
                    @endif
                    @php
                        $delayed = request()->get('delayed'); // or request('delayed')
                    @endphp
                    @if ($delayed)
                        <h4>
                            Showing Delayed Shipments (More than 2 hours)
                        </h4>
                    @endif
                </span>

                <!-- Right: Button -->
                <div>
                    @if ($status)
                        <a href="{{ $exportPdfUrl }}" class="btn btn-danger mr-1" title="Download PDF">
                            <i class="fas fa-download"></i>
                        </a>
                    @endif
                    @if ($delayed)
                        <a href="{{ route('delivery-metrics.export.pdf', array_merge(request()->query())) }}"
                            class="btn btn-danger mr-1" title="Download PDF">
                            <i class="fas fa-download"></i> Delayed PDF
                        </a>
                    @endif


                    <!-- <button type="button" class="btn btn-sm btn-primary shadow-sm rounded p-2" data-toggle="modal"
                                                        data-target="#createClientRequest">
                                                        <span class="mt-1 mb-1"><i class="fas fa-plus fa-sm text-white"></i> Create Client
                                                            Request</span>
                                                    </button> -->
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table text-primary table-bordered table-striped table-hover" id="dataTable" width="100%"
                    cellspacing="0" style="font-size: 14px;">
                    <thead>
                        <tr class="text-success">
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Service Level</th>
                            <th>Client Type</th>
                            <th>Route</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Status</th>
                            {{-- Conditionally add Remarks column if status = delivery_failed --}}
                            @if (isset($status) && strtolower($status) === 'delivery_failed')
                                <th>Reason</th>
                            @endif
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Service Level</th>
                            <th>Client Type</th>
                            <th>Route</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Status</th>
                            @if (isset($status) && strtolower($status) === 'delivery_failed')
                                <th>Reason</th>
                            @endif
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($shipments as $shipment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $shipment->requestId }}</td>
                                <td>{{ $shipment->clientRequestById->client->name ?? 'N/A' }}</td>
                                <td>{{ $shipment->clientRequestById->serviceLevel->sub_category_name ?? 'N/A' }}</td>
                                <td>{{ \Illuminate\Support\Str::title($shipment->clientRequestById->client->type ?? '—') }}
                                </td>
                                <td>
                                    From: {{ $shipment->sender_address ?? '' }}, {{ $shipment->sender_town ?? '' }}<br>
                                    To: {{ $shipment->receiver_address ?? '' }}, {{ $shipment->receiver_town ?? '' }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($shipment->created_at)->format('F j, Y') }}</td>
                                <td>{{ $shipment->collectedBy->name ?? '—' }}</td>
                                <td>{{ $shipment->clientRequestById->vehicle->regNo ?? '—' }}</td>
                                <td>
                                    <span
                                        class="badge p-2 
                                        @if ($shipment->status == 'arrived') bg-secondary
                                        @elseif (in_array($shipment->status, ['Delivery Rider Allocated', 'delivery_rider_allocated'])) bg-warning
                                        @elseif ($shipment->status == 'parcel_delivered') bg-success
                                        @elseif ($shipment->status == 'delivery_failed') bg-danger
                                        @else bg-dark @endif text-white">
                                        {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $shipment->status)) }}
                                    </span>
                                </td>

                                {{-- Only show remarks if viewing delivery_failed shipments --}}
                                @if (isset($status) && strtolower($status) === 'delivery_failed')
                                    <td>{{ $shipment->delivery_failure_remarks ?? '—' }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
