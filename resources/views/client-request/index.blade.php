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

                    <div class="input-group input-group-sm mr-2" style="width: 250px;">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Search client requests...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#createClientRequest">
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
                                            <label for="collectionLocation" class="form-label text-primary">Pickup Location</label>
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
                                                    <option value="{{ $driver->id }}">{{ $driver->name }} ({{ $driver->station }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="vehicle" class="form-label text-primary">Vehicle</label>
                                            <input type="text" id="vehicle" class="form-control" name="vehicle_display" placeholder="Select rider to populate" readonly>
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

                                            document.addEventListener('DOMContentLoaded', function () {
                                                const userSelect = document.getElementById('userId');
                                                const vehicleInput = document.getElementById('vehicle');
                                                const vehicleIdInput = document.getElementById('vehicleId');

                                                userSelect.addEventListener('change', function () {
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
                                                <input type="text" name="dateRequested" id="datetime" class="form-control" placeholder="Select date & time">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="calendar-trigger" style="cursor: pointer;">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <script>
                                                document.getElementById('calendar-trigger').addEventListener('click', function () {
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
                <table class="table table-bordered results" id="dataTable" width="100%" cellspacing="0">
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
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $request->requestId }} </td>
                                <td> {{ $request->client->name }} </td>
                                <td> {{ $request->collectionLocation }} </td>
                                <td> {{ $request->dateRequested }} </td>
                                <td> {{ $request->user->name ?? '—' }} </td>
                                <td> {{ $request->vehicle->regNo ?? '—' }} </td>
                                <td> {{ $request->parcelDetails }} </td>
                                <td> <p class="badge
                                            @if ($request->status == 'pending collection')
                                                bg-secondary
                                            @elseif ($request->status == 'collected')
                                                bg-warning
                                            @elseif ($request->status == 'received')
                                                bg-primary
                                            @endif
                                            fs-5 text-white"
                                           >
                                        {{ \Illuminate\Support\Str::title($request->status) }}
                                    </p>
                                </td>
                                <td class="d-flex pl-4">
                                    <button class="btn btn-sm btn-info mr-1" title="Edit Client Request" data-toggle="modal" data-target="#editClientRequest-{{ $request->requestId }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Edit Client Request Modal -->
                                    <div class="modal fade" id="editClientRequest-{{ $request->requestId }}" tabindex="-1" role="dialog" aria-labelledby="editClientRequestModalLabel-{{ $request->requestId }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <form action="{{ route('clientRequests.update', $request->requestId) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                <div class="modal-header bg-gradient-primary">
                                                    <h5 class="modal-title text-white" id="editClientRequestModalLabel-{{ $request->requestId }}">Edit Client Request</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form Fields -->
                                                    <div class="form-group">
                                                        <label class="text-primary">Request ID</label>
                                                        <input type="text" name="requestId" class="form-control" value="{{ $request->requestId }}" readonly>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="text-primary">Client</label>
                                                        <select name="clientId" class="form-control">
                                                            @foreach($clients as $client)
                                                                <option value="{{ $client->id }}" {{ $client->id == $request->clientId ? 'selected' : '' }}>{{ $client->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="text-primary">Pick-up Location</label>
                                                        <input type="text" name="collectionLocation" class="form-control" value="{{ $request->collectionLocation }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="text-primary">Date Requested</label>
                                                        <div class="input-group">
                                                            <input 
                                                                type="datetime-local" 
                                                                name="dateRequested" 
                                                                id="datetime" 
                                                                class="form-control" 
                                                                value="{{ \Carbon\Carbon::parse($request->dateRequested)->format('Y-m-d\TH:i') }}"
                                                            >
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" style="cursor: pointer;" onclick="document.getElementById('datetime').focus()">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="text-primary">Rider</label>
                                                        <select name="userId" class="form-control rider-select" data-modal="{{ $request->requestId }}">
                                                            <option value="">Select Rider</option>
                                                            @foreach($drivers as $driver)
                                                                @php
                                                                    $assignedVehicle = $vehicles->firstWhere('user_id', $driver->id);
                                                                @endphp
                                                                <option 
                                                                    value="{{ $driver->id }}" 
                                                                    data-vehicle="{{ $assignedVehicle ? $assignedVehicle->regNo : '' }}" 
                                                                    {{ $driver->id == $request->userId ? 'selected' : '' }}>
                                                                    {{ $driver->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="text-primary">Vehicle</label>
                                                        <input type="text" class="form-control vehicle-input" name="vehicleDisplay" value="{{ $request->vehicle->regNo }}" readonly>
                                                        <input type="hidden" name="vehicleId" class="vehicle-id-hidden" value="{{ $request->vehicleId }}">
                                                    </div>

                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function () {
                                                            const selects = document.querySelectorAll('.rider-select');

                                                            selects.forEach(select => {
                                                                select.addEventListener('change', function () {
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
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <button class="btn btn-sm btn-warning mr-1" title="View Client Request">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if ($request->status === 'collected')
                                        <button class="btn btn-sm btn-primary mr-1" 
                                                title="Verify Collected Parcel" 
                                                data-toggle="modal" 
                                                data-target="#verifyCollectedParcel-{{ $request->requestId }}"> 
                                            <i class="fas fa-clipboard-check"></i>
                                        </button>
                                    @endif
                                    
                                    <!-- Verify Collected Parcel Modal -->
                                    <div class="modal fade" id="verifyCollectedParcel-{{ $request->requestId }}" tabindex="-1" role="dialog" aria-labelledby="verifyCollectedParcelModalLabel-{{ $request->requestId }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <form action="{{ route('clientRequests.update', $request->requestId) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                <div class="modal-header bg-gradient-info">
                                                    <h5 class="modal-title text-white" id="verifyCollectedParcelModalLabel-{{ $request->requestId }}">Verify Details of Collected Parcel </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form Fields -->
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <label class="text-primary">Request ID</label>
                                                            <input type="text" name="requestId" class="form-control" value="{{ $request->requestId }}" readonly>
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <label class="text-primary">Rider</label>
                                                            <input type="text" name="userId" class="form-control" value="{{ $driver->name }}" readonly>
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <label class="text-primary">Vehicle</label>
                                                            <input type="text" class="form-control vehicle-input" name="vehicleDisplay" value="{{ $request->vehicle->regNo }}" readonly>
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <label class="text-primary">Date Requested</label>
                                                            <input type="datetime-local" name="dateRequested" id="datetime" class="form-control" 
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
                                                                    <th>Quantity</th>
                                                                    <th>Weight</th>
                                                                    <th>Length</th>
                                                                    <th>Width</th>
                                                                    <th>Height</th>
                                                                    <th>Volume</th>
                                                                    <th>Cost</th>
                                                                    <th>Remarks</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($request->shipmentCollection->items as $index => $item)
                                                                    <tr>
                                                                        <td>{{ $index + 1 }}</td>
                                                                        <td><input type="text" name="items[{{ $index }}][item_name]" value="{{ $item->item_name }}" class="form-control" required></td>
                                                                        <td><input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="form-control" required></td>
                                                                        <td><input type="number" step="0.01" name="items[{{ $index }}][weight]" value="{{ $item->weight }}" class="form-control" required></td>
                                                                        <td><input type="number" name="items[{{ $index }}][length]" value="{{ $item->length }}" class="form-control" required></td>
                                                                        <td><input type="number" name="items[{{ $index }}][width]" value="{{ $item->width }}" class="form-control" required></td>
                                                                        <td><input type="number" name="items[{{ $index }}][height]" value="{{ $item->height }}" class="form-control" required></td>
                                                                        <td>
                                                                            {{ $item->length * $item->width * $item->height }}
                                                                            <input type="hidden" name="items[{{ $index }}][volume]" value="{{ $item->length * $item->width * $item->height }}">
                                                                        </td>
                                                                        <td><input type="number" step="0.01" name="items[{{ $index }}][cost]" value="{{ $item->cost }}" class="form-control"></td>
                                                                        <td><input type="text" name="items[{{ $index }}][remarks]" value="{{ $item->remarks }}" class="form-control"></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        <p class="text-muted">No items found for this request.</p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Submit Verification</button>
                                                </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <button class="btn btn-sm btn-danger mr-1" title="Delete Client Request"  data-toggle="modal" data-target="#deleteClientRequest-{{ $request->requestId }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <!-- Delete Modal-->
                                    <div class="modal fade" id="deleteClientRequest-{{ $request->requestId }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                            value="DELETE">YES DELETE <i class="fas fa-trash"></i> </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach          
                    </tbody>
                </table>
                <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const searchInput = document.getElementById("tableSearch");
                    const tableRows = document.querySelectorAll(".results tbody tr");
                    const counter = document.querySelector(".counter"); // optional
                    const noResult = document.querySelector(".no-result"); // optional

                    searchInput.addEventListener("input", function () {
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
                </script>
            </div>
        </div>
    </div>
@endsection
