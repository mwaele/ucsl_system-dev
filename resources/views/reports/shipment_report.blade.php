@extends('layouts.custom')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Reports Dashboard</h5>
        </div>
    </div>

    <div class="card-body">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="reportTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="sameday-tab" data-toggle="tab" href="#sameday" role="tab">Sameday & Overnight</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="collection-tab" data-toggle="tab" href="#collection" role="tab">Parcel Collection</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="rider-tab" data-toggle="tab" href="#rider" role="tab">Rider Performance</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="driver-tab" data-toggle="tab" href="#driver" role="tab">Driver Shipment Analysis</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="cod-tab" data-toggle="tab" href="#cod" role="tab">CoD & Cash</a>
            </li>
            <!-- Continue adding tabs for all 10 reports -->
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="reportTabsContent">
            
            <!-- Sameday & Overnight -->
            <div class="tab-pane fade show active" id="sameday" role="tabpanel">
                
            </div>

            <!-- Parcel Collection -->
            <div class="tab-pane fade" id="collection" role="tabpanel">

            </div>

            <!-- Rider Performance -->
            <div class="tab-pane fade" id="rider" role="tabpanel">

            </div>

            <!-- Driver Shipment Analysis -->
            <div class="tab-pane fade" id="driver" role="tabpanel">

            </div>

            <!-- CoD & Cash -->
            <div class="tab-pane fade" id="cod" role="tabpanel">

            </div>

            <!-- Add more includes here -->
        </div>

        <div class="card-body">
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-5">
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>
                    <div class="col-md-5">
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block btn-sm">Filter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="text-success">
                        <tr> staff in charge
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Consigner</th>
                            <th>Consignee</th>
                            <th>Service level</th>
                            <th>Items</th>
                            <th>No. of packages</th>
                            <th>Assigned rider & truck</th>
                            <th>Route</th>
                            <th>Collection status</th>
                            <th>Processed by</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientRequests as $collection)
                            <tr>
                                <td> {{ $loop->iteration }}. </td>
                                <td> {{ $collection->requestId }} </td>
                                <td> {{ $collection->client->name ?? 'N/A' }} </td>
                                <td> {{ $collection->shipmentCollection->receiver_name ?? '' }} </td>
                                <td> {{ $collection->serviceLevel->sub_category_name }} </td>
                                <td> {{ $collection->shipmentCollection?->items?->count() ?? '' }} </td>
                                <td> {{ $collection->shipmentCollection->packages_no ?? '' }} </td>
                                <td> {{ $collection->user->name ?? '—' }} | {{ $collection->vehicle->regNo ?? '—' }} </td>
                                <td>{{ $collection->client->name ?? 'N/A' }}</td>
                                <td>{{ $collection->client->name ?? 'N/A' }}</td>
                                <td>{{ $collection->client->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No records found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
