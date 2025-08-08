@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap" style="font-size: 14px;">
                <!-- Left: Title -->
                <span class="m-0 font-weight-bold text-success">All Client Requests</span>

                <!-- Right: Button -->
                <div>
                    <a href="{{ $exportPdfUrl }}" class="btn btn-danger mr-1" title="Download PDF">
                        <i class="fas fa-download"></i>
                    </a>

                    <button type="button" class="btn btn-sm btn-primary shadow-sm rounded p-2" data-toggle="modal"
                        data-target="#createClientRequest">
                        <span class="mt-1 mb-1"><i class="fas fa-plus fa-sm text-white"></i> Create Client
                            Request</span>
                    </button>
                </div>
            </div>



            <!-- Modal -->
            <form action="{{ route('clientRequests.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="createClientRequest" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for wider layout -->
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary">
                                <h3 class="modal-title text-white" id="exampleModalLabel">Create Client Request</h3>
                                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <h6 class="text-muted text-primary">Fill in the client details.</h6>

                                    <div class="row mb-3">
                                        <div class="col-md-6">

                                            <label for="clientId" class="form-label text-primary">Client</label>
                                            <select class="form-control" id="clientId" name="clientId">
                                                <option value="">Select Client</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}
                                                        ({{ $client->accountNo }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="collectionLocation" class="form-label text-primary">Pickup
                                                Location</label>
                                            <input type="text" class="form-control" name="collectionLocation"
                                                id="collectionLocation" autocomplete="off">
                                            <div id="locationSuggestions" class="list-group bg-white position-absolute w-80"
                                                style="background-color: white;z-index: 1000;"></div>

                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">

                                            <label for="clientCategories" class="form-label text-primary">Client
                                                Categories</label>
                                            <!-- Client's Categories -->
                                            <select class="form-control" id="clientCategories" name="category_id">
                                                <option value="">Select Client Categories</option>
                                            </select>

                                        </div>
                                        <div class="col-md-6">
                                            <label for="subCategories" class="form-label text-primary">Client
                                                Services </label>
                                            <!-- Services -->
                                            <select name="sub_category_id" class="form-control" required>
                                                <option value="">-- Select Service Level --</option>
                                                @foreach ($sub_categories as $sub_category)
                                                    <option value="{{ $sub_category->id }}">
                                                        {{ $sub_category->sub_category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="parcelDetails" class="form-label fw-medium text-primary">Parcel
                                            Details</label>
                                        <textarea class="form-control" id="parcelDetails" name="parcelDetails" rows="3"
                                            placeholder="Fill in the description of goods."></textarea>
                                    </div>

                                    <h6 class="text-muted text-primary"> Rider Details.</h6>
                                    <div class="row mb-2 bg-success">
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="riderOption"
                                                    id="currentLocation" value="currentLocation">
                                                <label class="form-check-label" for="allRiders"> Pickup
                                                    Location</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="riderOption"
                                                    id="unallocatedRiders" value="unallocated">
                                                <label class="form-check-label" for="unallocatedRiders">Unallocated
                                                    Riders</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="riderOption"
                                                    id="allRiders" value="all">
                                                <label class="form-check-label" for="allRiders">All Riders</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="userId" class="form-label text-primary">Rider</label>
                                            <select class="form-control" id="userId" name="userId">
                                                <option value="">Select Rider</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-4 mb-3">
                                            <label for="vehicle" class="form-label text-primary">Vehicle</label>
                                            <input type="text" id="vehicle" class="form-control"
                                                name="vehicle_display" placeholder="Select rider to populate" readonly>
                                            <input type="hidden" id="vehicleId" name="vehicleId">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="requestId" class="form-label text-primary">Request ID</label>
                                            <input type="text" value="{{ $request_id }}" name="requestId"
                                                class="form-control" id="request_id" readonly>
                                        </div>
                                        <script>
                                            const vehicleMap = {
                                                @foreach ($vehicles as $vehicle)
                                                    "{{ $vehicle->user_id }}": {
                                                        id: "{{ $vehicle->id }}",
                                                        regNo: "{{ $vehicle->regNo }}",
                                                        status: "{{ $vehicle->status }}"
                                                    },
                                                @endforeach
                                            };

                                            document.addEventListener('DOMContentLoaded', function() {
                                                const userSelect = document.getElementById('userId');
                                                const vehicleInput = document.getElementById('vehicle');
                                                const vehicleIdInput = document.getElementById('vehicleId');

                                                userSelect.addEventListener('change', function() {
                                                    const selectedUserId = this.value;
                                                    const vehicle = vehicleMap[selectedUserId];

                                                    if (vehicle) {
                                                        vehicleInput.value = `${vehicle.regNo} (${vehicle.status})`;
                                                        vehicleIdInput.value = vehicle.id;
                                                    } else {
                                                        vehicleInput.value = '';
                                                        vehicleIdInput.value = '';
                                                    }
                                                });
                                            });
                                        </script>

                                        <div class="col-md-4 mb-3">
                                            <label for="datetime" class="text-primary">Date of Request</label>
                                            <div class="input-group">
                                                <input type="text" name="dateRequested" id="datetime"
                                                    class="form-control" placeholder="Select date & time">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="calendar-trigger"
                                                        style="cursor: pointer;">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <script>
                                                document.getElementById('calendar-trigger').addEventListener('click', function() {
                                                    document.getElementById('datetime').focus();
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-primary table-bordered table-striped table-hover" id="ucsl-table" width="100%"
                    cellspacing="0" style="font-size: 14px;">
                    <thead>
                        <tr class="text-success">
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Pick-up Location</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Service Level</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Pick-up Location</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Service Level</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($client_requests as $request)
                            <tr>
                                <td> {{ $loop->iteration }}. </td>
                                <td> {{ $request->requestId }} </td>
                                <td> {{ $request->client->name }} </td>
                                <td> {{ $request->collectionLocation }} </td>
                                <td> {{ \Carbon\Carbon::parse($request->dateRequested)->format('F j, Y \a\t g:i A') }}
                                </td>
                                <td> {{ $request->user->name ?? '—' }} </td>
                                <td> {{ $request->vehicle->regNo ?? '—' }} </td>
                                <td> {{ $request->serviceLevel->sub_category_name }} </td>
                                <td>
                                    <span
                                        class="badge p-2
                                @if ($request->status == 'pending collection') bg-secondary
                                @elseif ($request->status == 'collected')
                                    bg-warning
                                @elseif ($request->status == 'verified')
                                    bg-primary @endif
                                fs-5 text-white">
                                        {{ \Illuminate\Support\Str::title($request->status) }}
                                    </span>
                                </td>
                                <td class="d-flex pl-2">
                                    @if ($request->status === 'pending collection')
                                        <button class="btn btn-sm btn-primary mr-1" title="Edit Client Request"
                                            data-toggle="modal"
                                            data-target="#editClientRequest-{{ $request->requestId }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    <!-- Edit Client Request Modal -->
                                    <div class="modal fade" id="editClientRequest-{{ $request->requestId }}"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="editClientRequestModalLabel-{{ $request->requestId }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <form action="{{ route('clientRequests.update', $request->requestId) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header bg-gradient-primary">
                                                        <h5 class="modal-title text-white"
                                                            id="editClientRequestModalLabel-{{ $request->requestId }}">
                                                            Edit Client Request</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Form Fields -->
                                                        <div class="form-group">
                                                            <label class="text-primary">Request ID</label>
                                                            <input type="text" name="requestId" class="form-control"
                                                                value="{{ $request->requestId }}" readonly>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Client</label>
                                                            <select name="clientId" class="form-control">
                                                                @foreach ($clients as $client)
                                                                    <option value="{{ $client->id }}"
                                                                        {{ $client->id == $request->clientId ? 'selected' : '' }}>
                                                                        {{ $client->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Pick-up Location</label>
                                                            <input type="text" name="collectionLocation"
                                                                class="form-control"
                                                                value="{{ $request->collectionLocation }}">
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Date Requested</label>
                                                            <div class="input-group">
                                                                <input type="datetime-local" name="dateRequested"
                                                                    id="datetime" class="form-control"
                                                                    value="{{ \Carbon\Carbon::parse($request->dateRequested)->format('F j, Y \a\t g:iA') }}">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"
                                                                        style="cursor: pointer;"
                                                                        onclick="document.getElementById('datetime').focus()">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Rider</label>
                                                            <select name="userId" class="form-control rider-select"
                                                                data-modal="{{ $request->requestId }}">
                                                                <option value="">Select Rider</option>
                                                                @foreach ($drivers as $driver)
                                                                    @php
                                                                        $assignedVehicle = $vehicles->firstWhere(
                                                                            'user_id',
                                                                            $driver->id,
                                                                        );
                                                                    @endphp
                                                                    <option value="{{ $driver->id }}"
                                                                        data-vehicle="{{ $assignedVehicle ? $assignedVehicle->regNo : '' }}"
                                                                        {{ $driver->id == $request->userId ? 'selected' : '' }}>
                                                                        {{ $driver->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-primary">Vehicle</label>
                                                            <input type="text" class="form-control vehicle-input"
                                                                name="vehicleDisplay"
                                                                value="{{ $request->vehicle->regNo ?? '—' }}" readonly>
                                                            <input type="hidden" name="vehicleId"
                                                                class="vehicle-id-hidden"
                                                                value="{{ $request->vehicleId }}">
                                                        </div>

                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                const selects = document.querySelectorAll('.rider-select');

                                                                selects.forEach(select => {
                                                                    select.addEventListener('change', function() {
                                                                        const modalId = this.dataset.modal;
                                                                        const selectedOption = this.options[this.selectedIndex];
                                                                        const vehicleRegNo = selectedOption.dataset.vehicle || '';

                                                                        const modal = document.getElementById('editClientRequest-' + modalId);
                                                                        const vehicleInput = modal.querySelector('.vehicle-input');
                                                                        const hiddenVehicleId = modal.querySelector('.vehicle-id-hidden');

                                                                        vehicleInput.value = vehicleRegNo;

                                                                        const allVehicles = @json($vehicles);

                                                                        allVehicles.forEach(vehicle => {
                                                                            if (vehicle.regNo === vehicleRegNo) {
                                                                                hiddenVehicleId.value = vehicle.id;
                                                                            }
                                                                        });
                                                                    });
                                                                });
                                                            });
                                                        </script>

                                                        <div class="form-group">
                                                            <label class="text-primary">Description of Goods</label>
                                                            <textarea name="parcelDetails" class="form-control">{{ $request->parcelDetails }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    @if ($request->status === 'pending collection')
                                        <button class="btn btn-sm btn-danger mr-1" title="Delete Client Request"
                                            data-toggle="modal"
                                            data-target="#deleteClientRequest-{{ $request->requestId }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif

                                    <!-- Delete Modal-->
                                    <div class="modal fade" id="deleteClientRequest-{{ $request->requestId }}"
                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $request->requestId }}?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <form
                                                        action =" {{ route('clientRequests.destroy', $request->requestId) }}"
                                                        method = "POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Delete" value="DELETE">YES DELETE <i
                                                                class="fas fa-trash"></i> </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($request->status === 'collected')
                                        {{-- <button class="btn btn-sm btn-info mr-1" title="Verify Collected Parcel"
                                            data-toggle="modal" data-rider="{{ $request->user->name }}"
                                            data-target="#verifyCollectedParcel-{{ $request->requestId }}">
                                            <i class="fas fa-clipboard-check"></i>
                                        </button> --}}
                                        <button class="btn btn-info btn-sm verify-btn mr-1"
                                            data-id="{{ $request->shipmentCollection->id }}"
                                            data-request-id="{{ $request->requestId }}"
                                            data-rider="{{ $request->user->name }}"
                                            data-vehicle="{{ $request->vehicle ?? '—' }}"
                                            data-date-requested="{{ \Carbon\Carbon::parse($request->dateRequested)->format('Y-m-d\TH:i') }}"
                                            data-cost="{{ $request->shipmentCollection->cost }}"
                                            data-total-cost="{{ $request->shipmentCollection->total_cost }}"
                                            data-vat="{{ $request->shipmentCollection->vat }}"
                                            data-base-cost="{{ $request->shipmentCollection->base_cost }}">
                                            Verify
                                        </button>
                                    @endif

                                    @if ($request->status === 'collected')
                                        <button class="btn btn-sm btn-warning mr-1" title="View Client Request">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif

                                    @if ($request->status === 'verified')
                                        <button class="btn btn-sm btn-success mr-1" title="Generate Waybill"
                                            data-toggle="modal" data-target="#waybillModal{{ $request->requestId }}">
                                            <i class="fas fa-file-invoice"></i>
                                        </button>

                                        <div class="modal fade" id="waybillModal{{ $request->requestId }}"
                                            tabindex="-1" role="dialog" aria-labelledby="waybillLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document" style="max-width: 850px;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-primary">Waybill Preview</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body"
                                                        style="max-height: 80vh; overflow-y: auto; background: #f9f9f9;">
                                                        <iframe src="{{ route('waybill.preview', $request->requestId) }}"
                                                            width="100%" height="500" frameborder="0"></iframe>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <a href="{{ route('waybill.generate', $request->requestId) }}"
                                                            target="_blank" class="btn btn-primary">
                                                            Generate
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    @if ($request->status === 'verified')
                                        <button class="btn btn-sm btn-primary mr-1" title="Dispatch parcel"
                                            data-toggle="modal" data-target="">
                                            <i class="fas fa-truck"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Parcel Collection Details Verification Modal -->
                <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title text-white"><strong>Parcel Collection Details Verification</strong>
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                            </div>
                            <div class="modal-body" id="modalItemsBody">
                                Loading...
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {

                        //
                        $(document).on('click', '.verify-btn', function() {
                            const shipment_id = $(this).data('id');
                            const vehicle_reg_no = $(this).data('vehicle');
                            const rider = $(this).data('rider');
                            const date_requested = $(this).data('date-requested');
                            const request_id = $(this).data('request-id');
                            const cost = $(this).data('cost');
                            const total_cost = $(this).data('total-cost');
                            const vat = $(this).data('vat');
                            const base_cost = $(this).data('base-cost');
                            $.ajax({
                                url: '/shipments/' + shipment_id + '/items',
                                method: 'GET',
                                success: function(response) {
                                    let headerInfo = `
                                    <form id="shipmentForm">
                                                @csrf
                                                @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label class="text-primary">Request ID</label>
                                            <input type="text" name="requestId" class="form-control" id="requestId" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="text-primary">Rider</label>
                                            <input type="text" name="userId" id="riderName" class="form-control"  readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="text-primary">Vehicle</label>
                                            <input type="text" class="form-control" name="vehicleDisplay" id="vehicleRegNo" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="text-primary">Date Requested</label>
                                            <input type="datetime-local" name="dateRequested" class="form-control" id="dateRequested" readonly>
                                        </div>
                                    </div>
                                `;

                                    let itemsHtml =
                                        '<div class="table-responsive"><table class="table table-bordered" id="shipmentTable">';
                                    response.items.forEach((item, index) => {
                                        const volume = item.length * item.width * item.height;

                                        itemsHtml += `<thead> <tr><th> Item No. </th> <th> Item Name </th> <th> Package No </th> <th> Weight(Kg) </th> <th> Length(cm) </th> <th> Width(cm) </th> <th> Height(cm) </th> <th> Volume(cm <sup> 3 </sup>)</th><th> Remarks </th> </tr> </thead>
                                    <tr><td>${index + 1}<input type="hidden" name="items[${index}][id]" value="${item.id}"></td><td><input type="text" name="items[${index}][item_name]" class="form-control" value="${item.item_name}" required></td><td><input type="number" name="items[${index}][packages]" class="form-control packages" value="${item.packages_no}" required></td><td><input type="number" step="0.01" name="items[${index}][weight]" class="form-control weight" value="${item.weight}" required></td><td><input type="number" name="items[${index}][length]" class="form-control length" value="${item.length}"></td><td><input type="number" name="items[${index}][width]" class="form-control width" value="${item.width}"></td><td><input type="number" name="items[${index}][height]" class="form-control height" value="${item.height}"></td><td>${volume}<input type="hidden" name="items[${index}][volume]" class="volume" value="${volume}"></td><td><input type="text" name="items[${index}][remarks]" class="form-control" value="${item.remarks ?? ''}"></td></tr>


                                    <tr>
                                        <td colspan="9">
                                            <table class="table table-sm table-bordered mt-2">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Sub Item Name</th>
                                                        <th>Quantity</th>
                                                        <th>Weight (Kg)</th>
                                                        <th>Remarks</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="sub_items-${index}">
                                                    <!-- Sub-items will be appended here -->
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSubItems(${index})">+ Add Sub Item </button>
                                        </td>
                                    </tr>
                                `;
                                    });
                                    itemsHtml += `
                                        </tbody>
                                    </table>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <label class="text-dark"><small>Cost *</small></label>
                                            <input type="number" class="form-control cost" name="cost" id="cost" value="" readonly>
                                        </div>

                                        <input type="hidden" name="base_cost" id="baseCost" value="">

                                        <div class="form-group col-md-2">
                                            <label class="text-dark"><small>Tax (16%)*</small></label>
                                            <input type="number" class="form-control" name="vat" id="vat" readonly>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="text-dark"><small>Total Cost*</small></label>
                                            <input type="number" class="form-control" name="total_cost" id="totalCost" value="" readonly>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="text-dark"><small>Billing Party</small></label>
                                            <select name="billing_party" class="form-control">
                                                <option value="" selected>-- Select --</option>
                                                <option value="Sender">Sender</option>
                                                <option value="Receiver">Receiver</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="text-dark"><small>Payment Mode</small></label>
                                            <select name="payment_mode" class="form-control">
                                                <option value="" selected>-- Select --</option>
                                                <option value="M-Pesa">M-Pesa</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Invoice">Invoice</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="text-dark"><small>Reference</small></label>
                                            <input type="text" name="reference" class="form-control" placeholder="e.g. MPESA123XYZ">
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Submit Verification</button>
                                    </div></form>
                                    `;


                                    // ✅ Correct usage
                                    $('#modalItemsBody').html(headerInfo + itemsHtml);
                                    document.getElementById('requestId').value = request_id;
                                    document.getElementById('totalCost').value = total_cost;
                                    document.getElementById('cost').value = cost;
                                    document.getElementById('riderName').value = rider;
                                    document.getElementById('vehicleRegNo').value = vehicle_reg_no;
                                    document.getElementById('dateRequested').value = date_requested;
                                    document.getElementById('vat').value = vat;
                                    document.getElementById('baseCost').value = base_cost;
                                    $('#itemsModal').modal('show');
                                },
                                error: function() {
                                    $('#modalItemsBody').html('<p>Error loading items.</p>');
                                    $('#itemsModal').modal('show');
                                }
                            });

                        });

                        // Total weight calculation and cost update
                        function recalculateCosts() {
                            let totalWeight = 0;


                            $('#shipmentTable tbody tr').each(function() {
                                const row = $(this);
                                const weight = parseFloat(row.find('.weight').val()) || 0;
                                const volume = parseFloat(row.find('.volume').val()) || 0;
                                console.log('volume', volume);
                                const packages = parseFloat(row.find('.packages').val()) || 1;
                                totalWeight += weight * packages;
                            });

                            const baseCost = parseFloat($('input[name="base_cost"]').val()) || 0;
                            let cost = baseCost;

                            if (totalWeight > 25) {
                                const extraWeight = totalWeight - 25;
                                cost += extraWeight * 50;
                            }

                            $('input[name="cost"]').val(cost.toFixed(2));

                            const vat = cost * 0.16;
                            $('input[name="vat"]').val(vat.toFixed(2));
                            $('input[name="total_cost"]').val((cost + vat).toFixed(2));
                        }

                        // Watch for changes in volume dimensions
                        $(document).on('input', '.length, .width, .height',
                            function() {
                                const row = $(this).closest('tr');
                                calculateVolume(row);
                            });

                        // Watch for changes in weight or packages
                        $(document).on('input', '.weight, .packages', function() {
                            recalculateCosts();
                        });

                        // Handle dynamic sub-item row addition
                        document.querySelectorAll('.add-sub-item-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                const itemIndex = this.getAttribute('data-item-index');
                                let subCount = parseInt(this.getAttribute('data-sub-count'), 10);
                                const tbody = document.querySelector(`#sub-items-body-${itemIndex}`);

                                const newRow = document.createElement('tr');
                                newRow.innerHTML = `
                                    <td>
                                        <input type="text" name="items[${itemIndex}][sub_items][${subCount}][name]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="number" name="items[${itemIndex}][sub_items][${subCount}][quantity]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[${itemIndex}][sub_items][${subCount}][weight]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="number" name="items[${itemIndex}][sub_items][${subCount}][length]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="number" name="items[${itemIndex}][sub_items][${subCount}][width]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="number" name="items[${itemIndex}][sub_items][${subCount}][height]" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="items[${itemIndex}][sub_items][${subCount}][remarks]" class="form-control">
                                    </td>
                                `;

                                tbody.appendChild(newRow);

                                // Update sub-count for future additions
                                this.setAttribute('data-sub-count', subCount + 1);

                                // Bind cost listeners for the new row if needed
                                bindCostListeners(newRow);
                            });
                        });
                    });
                </script>

                <script>
                    function addSubItems(parentIndex) {

                        const container = document.getElementById(`sub_items-${parentIndex}`);

                        const subItemCount = container.children.length;

                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td><input type="text" name="items[${parentIndex}][sub_items][${subItemCount}][name]" class="form-control" required></td>
                            <td><input type="number" name="items[${parentIndex}][sub_items][${subItemCount}][quantity]" class="form-control" required></td>
                            <td><input type="number" step="0.01" name="items[${parentIndex}][sub_items][${subItemCount}][weight]" class="form-control" required></td>
                            <td><input type="text" name="items[${parentIndex}][sub_items][${subItemCount}][remarks]" class="form-control"></td>
                            <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Remove</button></td>
                        `;

                        container.appendChild(row);
                    }
                </script>

            </div>
        </div>
    </div>
@endsection
