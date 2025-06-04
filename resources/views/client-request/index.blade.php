@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">All Client Requests</h6>

                <div class="d-flex align-items-center">
                    <!-- Counter positioned just before the search input -->
                    <span class="text-primary counter mr-2"></span>

                    <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                        data-target="#createClientRequest">
                        <i class="fas fa-plus fa-sm text-white"></i> Create Client Request
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
                                <h5 class="modal-title text-white" id="exampleModalLabel">Create Client Request</h5>
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
                                                id="collectionLocation">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="parcelDetails" class="form-label fw-medium text-primary">Parcel
                                            Details</label>
                                        <textarea class="form-control" id="parcelDetails" name="parcelDetails" rows="3"
                                            placeholder="Fill in the description of goods."></textarea>
                                    </div>

                                    <h6 class="text-muted text-primary">Fill in the Rider details.</h6>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="userId" class="form-label text-primary">Rider</label>
                                            <select class="form-control" id="userId" name="userId">
                                                <option value="">Select Rider</option>
                                                @foreach ($drivers as $driver)
                                                    <option value="{{ $driver->id }}">{{ $driver->name }}
                                                        ({{ $driver->station }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="vehicle" class="form-label text-primary">Vehicle</label>
                                            <input type="text" id="vehicle" class="form-control" name="vehicle_display"
                                                placeholder="Select rider to populate" readonly>
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
                <table class="table table-bordered results display" id="ucsl-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Pick-up Location</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Description of goods</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <tr class="table-warning no-result text-center" style="display: none;">
                            <td colspan="10">
                                <i class="fa fa-warning text-danger"></i> No result found.
                            </td>
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
                            <th>Description of goods</th>
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
                                <td> {{ $request->parcelDetails }} </td>
                                <td>
                                    <p
                                        class="badge
                                @if ($request->status == 'pending collection') bg-secondary
                                @elseif ($request->status == 'collected')
                                    bg-warning
                                @elseif ($request->status == 'verified')
                                    bg-primary @endif
                                fs-5 text-white">
                                        {{ \Illuminate\Support\Str::title($request->status) }}
                                    </p>
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
                                                                value="{{ $request->vehicle->regNo }}" readonly>
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

                                    
                                        <button class="btn btn-sm btn-danger mr-1" title="Delete Client Request"
                                            data-toggle="modal"
                                            data-target="#deleteClientRequest-{{ $request->requestId }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                   

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
                                        <button class="btn btn-sm btn-info mr-1" title="Verify Collected Parcel"
                                            data-toggle="modal" data-rider="{{ $request->user->name }}"
                                            data-target="#verifyCollectedParcel-{{ $request->requestId }}">
                                            <i class="fas fa-clipboard-check"></i>
                                        </button>
                                    @endif

                                    <!-- Verify Collected Parcel Modal -->
                                    <div class="modal fade" id="verifyCollectedParcel-{{ $request->requestId }}"
                                        tabindex="-1"
                                        aria-labelledby="verifyCollectedParcelModalLabel-{{ $request->requestId }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <form action="{{ route('shipment-collections.update', $request->requestId) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header bg-gradient-info">
                                                        <h5 class="modal-title text-white"
                                                            id="verifyCollectedParcelModalLabel-{{ $request->requestId }}">
                                                            Verify Details of Collected Parcel
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="form-group col-md-3">
                                                                <label class="text-primary">Request ID</label>
                                                                <input type="text" name="requestId"
                                                                    class="form-control"
                                                                    value="{{ $request->requestId }}" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label class="text-primary">Rider</label>
                                                                <input type="text" name="userId" id="rider"
                                                                    class="form-control"
                                                                    value="{{ $request->user->name }}" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label class="text-primary">Vehicle</label>
                                                                <input type="text" class="form-control"
                                                                    name="vehicleDisplay"
                                                                    value="{{ $request->vehicle->regNo }}" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                                <label class="text-primary">Date Requested</label>
                                                                <input type="datetime-local" name="dateRequested"
                                                                    class="form-control"
                                                                    value="{{ \Carbon\Carbon::parse($request->dateRequested)->format('Y-m-d\TH:i') }}"
                                                                    readonly>
                                                            </div>
                                                        </div>

                                                        @if ($request->shipmentCollection && $request->shipmentCollection->items->count())
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Item No.</th>
                                                                        <th>Item Name</th>
                                                                        <th>Package No</th>
                                                                        <th>Weight (Kg)</th>
                                                                        <th>Length (cm)</th>
                                                                        <th>Width (cm)</th>
                                                                        <th>Height (cm)</th>
                                                                        <th>Volume (cm<sup>3</sup>)</th>
                                                                        <th>Remarks</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($request->shipmentCollection->items as $index => $item)
                                                                        <tr>
                                                                            <td>
                                                                                {{ $index + 1 }}
                                                                                <input type="hidden"
                                                                                    name="items[{{ $index }}][id]"
                                                                                    value="{{ $item->id }}">
                                                                            </td>
                                                                            <td><input type="text"
                                                                                    name="items[{{ $index }}][item_name]"
                                                                                    class="form-control"
                                                                                    value="{{ $item->item_name }}"
                                                                                    required></td>
                                                                            <td><input type="number"
                                                                                    name="items[{{ $index }}][packages_no]"
                                                                                    class="form-control packages"
                                                                                    value="{{ $item->packages_no }}"
                                                                                    required></td>
                                                                            <td><input type="number" step="0.01"
                                                                                    name="items[{{ $index }}][weight]"
                                                                                    class="form-control"
                                                                                    value="{{ $item->weight }}" required>
                                                                            </td>
                                                                            <td><input type="number"
                                                                                    name="items[{{ $index }}][length]"
                                                                                    class="form-control"
                                                                                    value="{{ $item->length }}"></td>
                                                                            <td><input type="number"
                                                                                    name="items[{{ $index }}][width]"
                                                                                    class="form-control"
                                                                                    value="{{ $item->width }}"></td>
                                                                            <td><input type="number"
                                                                                    name="items[{{ $index }}][height]"
                                                                                    class="form-control"
                                                                                    value="{{ $item->height }}"></td>
                                                                            <td>
                                                                                {{ $item->length * $item->width * $item->height }}
                                                                                <input type="hidden"
                                                                                    name="items[{{ $index }}][volume]"
                                                                                    value="{{ $item->length * $item->width * $item->height }}">
                                                                            </td>
                                                                            <td><input type="text"
                                                                                    name="items[{{ $index }}][remarks]"
                                                                                    class="form-control"
                                                                                    value="{{ $item->remarks }}"></td>
                                                                        </tr>

                                                                        <!-- Sub Items Table -->
                                                                        <tr>
                                                                            <td colspan="9">
                                                                                <table
                                                                                    class="table table-sm table-bordered mt-2">
                                                                                    <thead class="thead-light">
                                                                                        <tr>
                                                                                            <th>Sub Item Name</th>
                                                                                            <th>Quantity</th>
                                                                                            <th>Weight (Kg)</th>
                                                                                            <th>Remarks</th>
                                                                                            <th>Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody
                                                                                        id="sub-items-{{ $index }}">
                                                                                        <!-- JS will populate sub-items here -->
                                                                                    </tbody>
                                                                                </table>

                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-outline-primary"
                                                                                    onclick="addSubItem({{ $index }})">
                                                                                    + Add Sub Item
                                                                                </button>
                                                                                @push('scripts')
                                                                                    <script>
                                                                                        function addSubItem(parentIndex) {
                                                                                            const container = document.getElementById(`sub-items-${parentIndex}`);

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
                                                                                @endpush
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                            <div class="form-row">
                                                                <div class="form-group col-md-2">
                                                                    <label class="text-dark"><small>Cost *</small></label>
                                                                    <input type="number" class="form-control cost basecost"
                                                                        name="cost" id="basecost"
                                                                        value="{{ $request->shipmentCollection->cost }}"
                                                                        readonly>
                                                                </div>
                                                                <input type="hidden" name="base_cost" value="">

                                                                <div class="form-group col-md-2">
                                                                    <label class="text-dark"><small>Tax (16%)
                                                                            *</small></label>
                                                                    <input type="number" class="form-control"
                                                                        name="vat"
                                                                        value="{{ $request->shipmentCollection->vat }}"
                                                                        readonly>
                                                                </div>

                                                                <div class="form-group col-md-2">
                                                                    <label class="text-dark"><small>Total Cost
                                                                            *</small></label>
                                                                    <input type="number" class="form-control"
                                                                        name="total_cost"
                                                                        value="{{ $request->shipmentCollection->total_cost }}"
                                                                        readonly>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <p class="text-muted">No items found for this request.</p>
                                                        @endif
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Submit
                                                            Verification</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                    @if ($request->status === 'collected')
                                        <button class="btn btn-sm btn-warning mr-1" title="View Client Request">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif

                                    @if ($request->status === 'verified')
                                        <button class="btn btn-sm btn-success mr-1" title="Generate waybill"
                                            data-toggle="modal" data-target="">
                                            <i class="fas fa-file-invoice"></i>
                                        </button>
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

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        // Initial cost calculation bindings
                        function calculateCost() {
                            const row = this.closest('tr');
                            const packages = parseFloat(row.querySelector('[name*="[packages_no]"]')?.value || 0);
                            const weight = parseFloat(row.querySelector('[name*="[weight]"]')?.value || 0);
                            const costInput = row.querySelector('[name*="[cost]"]');
                            const basecost = parseFloat(document.getElementById('basecost')?.value || 0);

                            const cost = basecost * packages * weight;
                            if (costInput) {
                                costInput.value = cost.toFixed(2);
                            }
                        }

                        // Bind cost calculation listeners to initial inputs
                        function bindCostListeners(context = document) {
                            context.querySelectorAll('.packages, [name^="items"][name$="[weight]"]').forEach(input => {
                                input.addEventListener('input', calculateCost);
                            });
                        }

                        // Trigger initial cost calculation
                        document.querySelectorAll('.packages').forEach(input => {
                            input.dispatchEvent(new Event('input'));
                        });

                        // Bind to existing inputs
                        bindCostListeners();

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
                    document.addEventListener("DOMContentLoaded", function() {
                        const searchInput = document.getElementById("tableSearch");
                        const tableRows = document.querySelectorAll(".results tbody tr");
                        const counter = document.querySelector(".counter");
                        const noResult = document.querySelector(".no-result");

                        if (searchInput) {
                            searchInput.addEventListener("input", function() {
                                const searchTerm = searchInput.value.toLowerCase().trim();
                                const terms = searchTerm.split(" ");
                                let matchCount = 0;

                                tableRows.forEach(row => {
                                    const rowText = row.textContent.toLowerCase().replace(/\s+/g, " ");
                                    const matched = terms.every(term => rowText.includes(term));

                                    if (matched) {
                                        row.style.display = "";
                                        matchCount++;
                                    } else {
                                        row.style.display = "none";
                                    }
                                });

                                if (counter) {
                                    counter.textContent = `${matchCount} item${matchCount !== 1 ? 's' : ''}`;
                                }

                                if (noResult) {
                                    noResult.style.display = matchCount === 0 ? "block" : "none";
                                }
                            });
                        }
                    });
                </script>

                {{-- <script>
                    document.addEventListener("DOMContentLoaded", function() {

                        const basecost = parseFloat(document.getElementById('basecost').value || 0);

                        console.log(basecost)

                        // Add event listeners to all package and weight fields
                        document.querySelectorAll('.packages, [name^="items"][name$="[weight]"]').forEach(input => {
                            input.addEventListener('input', calculateCost);
                        });

                        function calculateCost() {
                            const row = this.closest('tr');
                            const packages = parseFloat(row.querySelector('[name*="[packages_no]"]').value) || 0;
                            const weight = parseFloat(row.querySelector('[name*="[weight]"]').value) || 0;
                            const costInput = row.querySelector('[name*="[cost]"]');

                            const cost = basecost * packages * weight;
                            costInput.value = cost.toFixed(2);
                        }

                        // Trigger initial cost calculation on page load
                        document.querySelectorAll('.packages').forEach(input => input.dispatchEvent(new Event('input')));
                    });


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

                        // Update sub-count so future rows are correctly indexed
                        this.setAttribute('data-sub-count', subCount + 1);
                    });
                    });
                </script>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const searchInput = document.getElementById("tableSearch");
                        const tableRows = document.querySelectorAll(".results tbody tr");
                        const counter = document.querySelector(".counter"); // optional
                        const noResult = document.querySelector(".no-result"); // optional

                        searchInput.addEventListener("input", function() {
                            const searchTerm = searchInput.value.toLowerCase().trim();
                            let matchCount = 0;

                            tableRows.forEach(row => {
                                const rowText = row.textContent.toLowerCase().replace(/\s+/g, " ");
                                const terms = searchTerm.split(" ");
                                const matched = terms.every(term => rowText.includes(term));

                                if (matched) {
                                    row.style.display = "";
                                    row.setAttribute("visible", "true");
                                    matchCount++;
                                } else {
                                    row.style.display = "none";
                                    row.setAttribute("visible", "false");
                                }
                            });

                            if (counter) {
                                counter.textContent = matchCount + " item" + (matchCount !== 1 ? "s" : "");
                            }

                            if (noResult) {
                                noResult.style.display = matchCount === 0 ? "block" : "none";
                            }
                        });
                    });
                </script> --}}
            </div>
        </div>
    </div>
@endsection
