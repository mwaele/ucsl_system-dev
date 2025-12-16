@extends('layouts.custom')

@section('content')
    <div class="card">

        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center"> </div>
            <div class="d-flex justify-content-between align-items-center">

                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#createParcelModal">
                    + Create Parcel
                </button>
                <h4 class="mb-0 text-warning"> <strong>Overnight - On-account Parcels</strong></h4>

                <!-- Right Side (Date Filter + Generate PDF) -->
                <div class="d-flex align-items-center ms-auto">
                    <!-- Date Range Filter -->
                    <div id="dateRangeFilter" class="d-flex flex-wrap align-items-center mr-4">
                        <h5 class="m-0 font-weight-bold text-primary mr-2">Filter by date:</h5>
                        <input type="date" id="startDate" class="form-control me-2 mr-2" style="width: 150px;">
                        <input type="date" id="endDate" class="form-control me-2 mr-2" style="width: 150px;">
                        <button id="clearFilter" class="btn btn-secondary mr-2">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>

                    <button id="generateReport" class="btn btn-danger shadow-sm">
                        <i class="fas fa-download fa text-white"></i> Generate Report
                    </button>

                    <script>
                        /**
                         * Reusable Date Filter + Report Generator
                         * @param {string} tableId - The ID of the table to filter
                         * @param {number} dateColIndex - Column index where the date is stored
                         * @param {string} reportUrl - The base URL for report generation
                         */
                        function initDateFilter(tableId, dateColIndex, reportUrl, startInputId = "startDate", endInputId = "endDate",
                            reportBtnId = "generateReport", clearBtnId = "clearFilter") {
                            const startInput = document.getElementById(startInputId);
                            const endInput = document.getElementById(endInputId);
                            const reportBtn = document.getElementById(reportBtnId);
                            const clearBtn = document.getElementById(clearBtnId);

                            function filterTable() {
                                let startDate = startInput.value;
                                let endDate = endInput.value;

                                let table = document.getElementById(tableId);
                                if (!table) return;

                                let rows = table.getElementsByTagName("tr");

                                for (let i = 1; i < rows.length; i++) { // skip header
                                    let dateCell = rows[i].getElementsByTagName("td")[dateColIndex];
                                    if (dateCell) {
                                        let rowDateStr = dateCell.getAttribute("data-date");
                                        let rowDate = rowDateStr ? new Date(rowDateStr) : new Date(dateCell.innerText);
                                        rowDate.setHours(0, 0, 0, 0);

                                        let showRow = true;

                                        if (startDate) {
                                            let from = new Date(startDate);
                                            from.setHours(0, 0, 0, 0);
                                            if (rowDate < from) showRow = false;
                                        }

                                        if (endDate) {
                                            let to = new Date(endDate);
                                            to.setHours(0, 0, 0, 0);
                                            if (rowDate > to) showRow = false;
                                        }

                                        rows[i].style.display = showRow ? "" : "none";
                                    }
                                }
                            }

                            function clearFilter() {
                                startInput.value = "";
                                endInput.value = "";

                                let table = document.getElementById(tableId);
                                if (!table) return;

                                let rows = table.getElementsByTagName("tr");
                                for (let i = 1; i < rows.length; i++) {
                                    rows[i].style.display = "";
                                }
                            }

                            startInput.addEventListener("change", filterTable);
                            endInput.addEventListener("change", filterTable);
                            clearBtn.addEventListener("click", clearFilter);

                            reportBtn.addEventListener("click", function() {
                                let startDate = startInput.value;
                                let endDate = endInput.value;
                                window.location.href = `${reportUrl}?start=${startDate}&end=${endDate}`;
                            });
                        }

                        // Example usage for "Overnight walk-in" page
                        initDateFilter("dataTable", 4, "/overnight_account_report");
                    </script>

                </div>
            </div>

            <!-- Create Parcel Modal -->
            <form action="{{ route('clientRequests.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="createParcelModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for wider layout -->
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">Create Overnight
                                    On-account Request</h5>
                                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <h6 class=" text-primary">Fill in the client details.</h6>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="clientId" class="form-label text-primary">Client</label>
                                            <select class="form-control selectpicker" data-live-search="true" id="clientId"
                                                name="clientId" required>
                                                <option value="">Select Client</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}"
                                                        data-tokens="{{ $client->kraPin }} {{ $client->accountNo }} {{ $client->name }}">
                                                        {{ $client->name }} -
                                                        @if (!empty($client->kraPin))
                                                            KRA PIN: {{ $client->kraPin }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="collectionLocation" class="form-label text-primary">Pickup
                                                Location</label>
                                            <input type="text" class="form-control" name="collectionLocation"
                                                id="collectionLocation" autocomplete="off" required>
                                            <div id="locationSuggestions" class="list-group bg-white position-absolute w-80"
                                                style="background-color: white;z-index: 1000;"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">

                                            <label for="clientCategories" class="form-label text-primary">Client
                                                Categories</label>
                                            <!-- Client's Categories -->
                                            <select class="form-control mt-1" id="clientCategories" name="category_id" required>
                                                <option value="">Select Client Categories</option>
                                            </select>

                                        </div>

                                        <div class="col-md-6">
                                            <label for="subCategories" class="form-label text-primary">Service Level
                                            </label>
                                            <!-- Readonly input to display the name -->
                                            <input type="text" class="form-control"
                                                value="{{ $sub_category->sub_category_name }}" readonly>

                                            <!-- Hidden input to store the ID -->
                                            <input type="hidden" name="sub_category_id" value="{{ $sub_category->id }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="parcelDetails" class="form-label fw-medium text-primary">Parcel
                                            Details</label>
                                        <textarea class="form-control" id="parcelDetails" name="parcelDetails" rows="3"
                                            placeholder="Fill in the description of goods." required></textarea>
                                    </div>

                                    <h6 class="text-muted text-primary"> Rider Details.</h6>
                                    <div class="row mb-2 bg-success">
                                        <div class="col-md-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="riderOption"
                                                    id="currentLocation" value="currentLocation">
                                                <label class="form-check-label" for="allRiders"> Pickup
                                                    Location</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="riderOption"
                                                    id="unallocatedRiders" value="unallocated">
                                                <label class="form-check-label" for="unallocatedRiders">Unallocated
                                                    Riders</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="riderOption"
                                                    id="allRiders" value="all">
                                                <label class="form-check-label" for="allRiders">All Riders</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="riderOption"
                                                    id="dedicatedRiders" value="dedicated">
                                                <label class="form-check-label" for="dedicatedRiders">Dedicated Riders</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="userId" class="form-label text-primary">Rider</label>
                                            <select class="form-control" id="userId" name="userId" required>
                                                <option value="">Select Rider</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-3 mb-3">
                                            <label for="vehicle" class="form-label text-primary">Vehicle</label>
                                            <input type="text" id="vehicle" class="form-control"
                                                name="vehicle_display" placeholder="Select rider to populate" required readonly>
                                            <input type="hidden" id="vehicleId" name="vehicleId">
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

                                        <div class="col-md-3 mb-3">
                                            <label for="datetime" class="text-primary">Date of Request</label>
                                            <div class="input-group">
                                                <input type="datetime-local" name="dateRequested" placeholder="Select date & time" 
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="mt-2 col-md-2">
                                            <h6 for="priority_level" class="text-primary">Priority Level</h6>
                                            <select class="form-control" name="priority_level" id="priority_level">
                                                <option value="normal" selected>Normal</option>
                                                <option value="high">High</option>
                                            </select>
                                        </div>

                                        <div class="mt-2 col-md-2" id="priority-deadline-group" style="display:none;">
                                            <h6 for="deadline_date" class="text-primary">Deadline
                                            </h6>
                                            <input type="datetime-local" class="form-control" name="deadline_date"
                                                id="deadline_date">
                                        </div>

                                        <!-- Inline confirmation (hidden by default) -->
                                        <div class="mt-2 col-md-2" id="priority-confirm" style="display:none;">
                                            <p class="text-white bg-danger p-2">High priority selected. Do extra
                                                charges
                                                apply?</p>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                id="priorityYesBtn">Yes</button>
                                            <button type="button" class="btn btn-sm btn-success"
                                                id="priorityNoBtn">No</button>
                                        </div>

                                        <!-- Extra charge input (hidden by default) -->
                                        <div class="mt-2 col-md-2" id="priority-extra-charge-group"
                                            style="display:none;">
                                            <h6 for="priority_extra_charge" class="text-primary">Priority Extra
                                                Charge
                                            </h6>
                                            <input type="number" class="form-control" name="priority_extra_charge"
                                                id="priority_extra_charge" placeholder="Enter extra amount">
                                        </div>

                                        <div class="mt-2 col-md-2">
                                            <h6 for="fragile" class="text-primary">Fragile?</h6>
                                            <select class="form-control" name="fragile" id="fragile">
                                                <option value="no" selected>No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>

                                        <!-- Inline Confirmation (hidden by default) -->
                                        <div class="mt-2 col-md-2" id="fragile-confirm" style="display:none;">
                                            <p class="text-white bg-danger p-2">This item is fragile. Do you want
                                                to
                                                add
                                                extra
                                                charges?</p>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                id="fragileYesBtn">Yes</button>
                                            <button type="button" class="btn btn-sm btn-success"
                                                id="fragileNoBtn">No</button>
                                        </div>

                                        <!-- Fragile Charge Input -->
                                        <div class="mt-2 col-md-2" id="fragile-charge-group" style="display:none;">
                                            <h6 for="fragile_charge" class="text-primary">Fragile Extra Charge
                                            </h6>
                                            <input type="number" class="form-control" name="fragile_charge"
                                                id="fragile_charge" placeholder="Enter extra amount">
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer d-flex justify-content-between align-items-center ">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Close
                                    X</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-primary table-bordered table-striped table-hover" id="dataTable"
                        width="100%" cellspacing="0" style="font-size: 14px;">
                        <thead>
                            <tr class="text-success">
                                <th>#</th>
                                <th>Request ID</th>
                                <th>Client</th>
                                <th>Pick-up Location</th>
                                <th>Date Requested</th>
                                <th>Rider</th>
                                <th>Vehicle</th>
                                <th>Desc.</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientRequests as $request)
                                <tr>
                                    <td> {{ $loop->iteration }}. </td>
                                    <td> {{ $request->requestId }} </td>
                                    <td> {{ $request->client->name }} </td>
                                    <td> {{ $request->collectionLocation }} </td>
                                    <td data-date="{{ $request->dateRequested }}">
                                        {{ \Carbon\Carbon::parse($request->dateRequested)->format('M d, Y') ?? null }}
                                    </td>
                                    <td> {{ $request->user->name ?? '—' }} </td>
                                    <td> {{ $request->vehicle->regNo ?? '—' }} </td>
                                    <td> {{ $request->parcelDetails }} </td>
                                    <td>
                                        <span
                                            class="badge p-2
                                            @if ($request->status === 'pending collection' || $request->status === 'Pending-Collection') bg-secondary
                                            @elseif ($request->status === 'collected') bg-warning
                                            @elseif ($request->status === 'verified') bg-primary
                                            @else bg-dark @endif
                                            fs-5 text-white">
                                            {{ \Illuminate\Support\Str::title($request->status) }}
                                        </span>
                                    </td>
                                    <td class="d-flex pl-2">
                                        @if ($request->status === 'pending collection')
                                            <button class="btn btn-sm btn-primary mr-1" title="Edit Client Request"
                                                data-toggle="modal"
                                                data-target="#editClientRequest-{{ $request->requestId }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        @endif
                                        <!-- Edit Client Request Modal -->
                                        <div class="modal fade" id="editClientRequest-{{ $request->requestId }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="editClientRequestModalLabel-{{ $request->requestId }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <form action="{{ route('clientRequests.update', $request->requestId) }}"
                                                    method="POST" enctype="multipart/form-data">
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
                                                                <input type="text" name="requestId"
                                                                    class="form-control"
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
                                                                    value="{{ $request->vehicle->regNo ?? '—' }}"
                                                                    readonly>
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
                                                        <div
                                                            class="modal-footer d-flex justify-content-between align-items-center">
                                                            <button type="button" class="btn btn-warning"
                                                                data-dismiss="modal">Cancel X</button>
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
                                                <i class="fas fa-trash"></i> Delete
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
                                                        <div
                                                            class="modal-footer d-flex justify-content-between align-items-center">
                                                            <button type="button" class="btn btn-sm btn-warning"
                                                                data-dismiss="modal">Cancel X</button>
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
                                        @endif

                                        @if ($request->shipmentCollection?->payment_mode === 'Invoice')
                                            <a href="{{ route('generate-invoice', $request->shipmentCollection->id) }}">
                                                <button class="btn btn-sm btn-info mr-1">
                                                    Preview Invoice
                                                </button>
                                            </a>
                                        @endif
                                        @if ($request->shipmentCollection?->payment_mode === 'M-Pesa')
                                            <button class="btn btn-sm btn-primary mr-1" title="Print collection"
                                                data-toggle="modal" data-target="#printModal-{{ $request->id }}">
                                                Preview Receipt <i class="fas fa-print"></i>
                                            </button>
                                            <div class="modal fade" id="printModal-{{ $request->id }}" tabindex="-1"
                                                aria-labelledby="printModalLabel-{{ $request->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content" id="print-modal-{{ $request->id }}">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="printModalLabel-{{ $request->id }}">
                                                                Shipment Receipt</h5>
                                                            <button type="button" class="text-primary close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body" id="print-content-{{ $request->id }}">
                                                            <div id="receipt-{{ $request->id }}"
                                                                style="font-family: monospace; font-size: 13px; line-height: 1.2;">
                                                                <div style="text-align: center;">
                                                                    <img src="{{ asset('images/ucsl_logo_monochrome.png') }}" alt="Logo"
                                                                        style="height: 70px;">
                                                                </div>
                                                                <div style="text-align: center; font-size: 15px;">
                                                                    <strong>Parcel Receipt</strong>
                                                                </div>
                                                                <hr style="margin: 4px 0;">

                                                                <div style="font-size: 14px;"><strong>Request ID:
                                                                        {{ $request->requestId ?? 'N/A' }}</strong></div>
                                                                <div style="font-size: 14px;"><strong>Consignment Number:
                                                                        {{ $request->consignment_no ?? 'N/A' }}</strong>
                                                                </div>
                                                                <div>
                                                                    <strong>From:</strong>
                                                                    {{ Auth::user()->office->name }}
                                                                    <strong style="margin-left: 10px;">To:</strong>
                                                                    {{ $request->shipmentCollection->destination->destination ?? '' }}
                                                                </div>
                                                                <div><strong>Total Items:</strong>
                                                                    {{ $request->shipmentCollection->items->count() }}
                                                                </div>
                                                                <div>
                                                                    <strong>Date:</strong> {{ now()->format('F j, Y') }}
                                                                    <strong style="margin-left: 10px;">Time:</strong>
                                                                    {{ now()->format('g:i A') }}
                                                                </div>

                                                                <hr style="margin: 4px 0;">

                                                                <div style="font-weight: bold;">Sender:</div>
                                                                <div>Name: {{ $request->shipmentCollection->sender_name }}
                                                                </div>
                                                                <div>KRA PIN:
                                                                    {{ $request->shipmentCollection->client->kraPin }}
                                                                </div>
                                                                @php
                                                                    $phone =
                                                                        $request->shipmentCollection->sender_contact;
                                                                    $maskedPhone =
                                                                        substr($phone, 0, 3) .
                                                                        str_repeat('*', strlen($phone) - 6) .
                                                                        substr($phone, -3);
                                                                @endphp

                                                                <div>Phone: {{ $maskedPhone }}</div>
                                                                <div>Location:
                                                                    {{ $request->shipmentCollection->sender_address }}
                                                                </div>
                                                                <div>Town: {{ $request->shipmentCollection->sender_town }}
                                                                </div>
                                                                <hr style="margin: 4px 0;">

                                                                <div style="font-weight: bold;">Receiver:</div>
                                                                <div>Name:
                                                                    {{ $request->shipmentCollection->receiver_name }}
                                                                </div>
                                                                @php
                                                                    $phone =
                                                                        $request->shipmentCollection->receiver_phone;
                                                                    $maskedPhone =
                                                                        substr($phone, 0, 3) .
                                                                        str_repeat('*', strlen($phone) - 6) .
                                                                        substr($phone, -3);
                                                                @endphp

                                                                <div>Phone: {{ $maskedPhone }}</div>

                                                                <div>Address:
                                                                    {{ $request->shipmentCollection->receiver_address }}
                                                                </div>
                                                                <div>Town:
                                                                    {{ $request->shipmentCollection->receiver_town }}
                                                                </div>
                                                                <hr style="margin: 4px 0;">

                                                                <div style="font-weight: bold;">Parcel Details:</div>
                                                                @if ($request->shipmentCollection && $request->shipmentCollection->items->count())
                                                                    @php
                                                                        $totalWeight = 0;
                                                                    @endphp
                                                                    <table
                                                                        style="width: 100%; border-collapse: collapse; margin-bottom: 4px;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="text-align: left;">#</th>
                                                                                <th style="text-align: left;">Desc.</th>
                                                                                <th style="text-align: center;">Qty</th>
                                                                                <th style="text-align: right;">Wt(kg)</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($request->shipmentCollection->items as $item)
                                                                                @php
                                                                                    $totalWeight +=
                                                                                        $item->packages_no *
                                                                                        $item->weight;
                                                                                @endphp
                                                                                <tr>
                                                                                    <td>{{ $loop->iteration }}.</td>
                                                                                    <td>{{ $item->item_name }}</td>
                                                                                    <td style="text-align: center;">
                                                                                        {{ $item->packages_no }}</td>
                                                                                    <td style="text-align: right;">
                                                                                        {{ $item->weight }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <hr style="margin: 4px 0;">

                                                                    <div
                                                                        style="display: flex; justify-content: space-between;">
                                                                        <strong>Total Weight:</strong>
                                                                        <span>{{ number_format($totalWeight, 2) }}
                                                                            {{ $totalWeight > 1 ? 'Kgs' : 'Kg' }}</span>
                                                                    </div>
                                                                    <div
                                                                        style="display: flex; justify-content: space-between;">
                                                                        <strong>Base Cost:</strong>
                                                                        <span>Ksh
                                                                            {{ number_format($request->shipmentCollection->cost, 2) }}</span>
                                                                    </div>
                                                                    <div
                                                                        style="display: flex; justify-content: space-between;">
                                                                        <strong>VAT:</strong>
                                                                        <span> Ksh
                                                                            {{ number_format($request->shipmentCollection->vat, 2) }}</span>
                                                                    </div>
                                                                    <div
                                                                        style="display: flex; justify-content: space-between;">
                                                                        <strong>Total:</strong>
                                                                        <span> Ksh
                                                                            {{ number_format($request->shipmentCollection->total_cost, 2) }}</span>
                                                                    </div>
                                                                @else
                                                                    <p>No shipment items found.</p>
                                                                @endif

                                                                {{-- Priority & Fragile Check --}}
                                                                @if ($request->priority_level === 'high' && $request->fragile_item === 'yes')
                                                                    <div
                                                                        style="margin-top: 8px; color: red; font-weight: bold;">
                                                                        *** High Priority & Fragile Parcel ***
                                                                    </div>
                                                                @elseif ($request->priority_level === 'high')
                                                                    <div
                                                                        style="margin-top: 8px; color: red; font-weight: bold;">
                                                                        *** High Priority ***
                                                                    </div>
                                                                @elseif ($request->fragile_item === 'yes')
                                                                    <div
                                                                        style="margin-top: 8px; color: red; font-weight: bold;">
                                                                        *** Fragile Parcel ***
                                                                    </div>
                                                                @endif

                                                                <hr style="margin: 6px 0;">
                                                                <div style="text-align: left; font-size: 12px;">
                                                                    Thank you for shipping with us.<br><br>
                                                                    <strong>TERMS & CONDITIONS</strong><br>
                                                                    Carriage of this shipment is subject to the terms and
                                                                    conditions overleaf.
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal" aria-label="Close">Close</button>
                                                            <button type="button" class="btn btn-primary"
                                                                onclick="printModalContent({{ $request->id }})">Print</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($request->status === 'received_at_front_office')
                                            {{-- <button class="btn btn-sm btn-info mr-1" title="Verify Collected Parcel"
                                                    data-toggle="modal" data-rider="{{ $request->user->name }}"
                                                    data-target="#verifyCollectedParcel-{{ $request->requestId }}">
                                                    <i class="fas fa-clipboard-check"></i>
                                                </button> --}}
                                            <button class="btn btn-info btn-sm verify-btn mr-1"
                                                data-id="{{ $request->shipmentCollection->id }}"
                                                data-request-id="{{ $request->requestId }}"
                                                data-rider="{{ $request->user->name }}"
                                                data-vehicle="{{ $request->vehicle->regNo ?? '—' }}"
                                                data-date-requested="{{ \Carbon\Carbon::parse($request->dateRequested)->format('Y-m-d\TH:i') }}"
                                                data-cost="{{ $request->shipmentCollection->cost }}"
                                                data-total-cost="{{ $request->shipmentCollection->total_cost }}"
                                                data-vat="{{ $request->shipmentCollection->vat }}"
                                                data-base-cost="{{ $request->shipmentCollection->base_cost }}">
                                                Verify
                                            </button>
                                        @endif

                                        @if ($request->status === 'collected')
                                            <!-- Receive Collection Button -->
                                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#receiveCollectionModal-{{ $request->id }}">
                                                <i class="fas fa-hand-holding-usd"></i> Receive Collection
                                            </button>

                                            <!-- Receive CollectionModal -->
                                            <div class="modal fade" id="receiveCollectionModal-{{ $request->id }}" tabindex="-1" role="dialog"
                                                aria-labelledby="receiveCollectionLabel-{{ $request->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title" id="receiveCollectionLabel-{{ $request->id }}">
                                                                Receive Rider Collection #{{ $request->requestId }}
                                                            </h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <form action="{{ route('requests.receiveCollection', $request->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="remarks-{{ $request->id }}">Remarks</label>
                                                                    <textarea name="remarks" id="remarks-{{ $request->id }}" class="form-control"
                                                                            rows="3" placeholder="Enter remarks..."></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-success">Confirm Receipt</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif


                                        @if ($request->status === 'verified')
                                            <button class="btn btn-sm btn-success mr-1" title="Generate Waybill"
                                                data-toggle="modal" data-target="#waybillModal{{ $request->requestId }}">
                                                <i class="fas fa-file-invoice"></i> Generate Waybill
                                            </button>

                                            <div class="modal fade" id="waybillModal{{ $request->requestId }}"
                                                tabindex="-1" role="dialog" aria-labelledby="waybillLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-xl" role="document"
                                                    style="max-width: 850px;">
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
                                                            <iframe
                                                                src="{{ route('waybill.preview', $request->requestId) }}"
                                                                width="100%" height="500" frameborder="0"></iframe>
                                                        </div>
                                                        <div
                                                            class="modal-footer d-flex justify-content-between align-items-center">
                                                            <button type="button" class="btn btn-warning"
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


                                        {{-- @if ($request->status === 'verified')
                                            <button class="btn btn-sm btn-primary mr-1" title="Dispatch parcel"
                                                data-toggle="modal" data-target="">
                                                <i class="fas fa-truck"></i>
                                            </button>
                                        @endif --}}

                                        @if ($request->status === 'Pending-Collection')
                                            <button class="btn btn-sm btn-primary" title="Delivery" data-toggle="modal"
                                                data-target="#allocateRider-{{ $request->id }}">
                                                Allocate Rider <i class="fas fa-van"></i> <i class="fas fa-arrow-up"></i>
                                            </button>
                                        @endif
                                        {{-- Allocate Rider Modal --}}

                                        <div class="modal fade" id="allocateRider-{{ $request->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="allocateRiderLabel-{{ $request->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-md" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title">
                                                            Allocate Rider to collect Parcel(Request
                                                            ID:
                                                            {{ $request->requestId }})
                                                        </h5>
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <div class="modal-body">
                                                        {{-- Issue Form --}}
                                                        <form method="POST" id="allocateRider"
                                                            action="{{ route('client_request.update_rider', $request->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="row">
                                                                {{-- Rider Selection --}}
                                                                <div class="col-md-12">
                                                                    <label class="text-primary">
                                                                        <h6>Rider Details</h6>
                                                                    </label>
                                                                </div>

                                                                {{-- <div class="col-md-4">
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input currentLocation"
                                                                            type="radio" name="riderOption"
                                                                            value="currentLocation">
                                                                        <label class="form-check-label"
                                                                            for="currentLocation">Pickup Location</label>
                                                                    </div>
                                                                </div> --}}
                                                                <div class="col-md-6">
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input unallocatedRiders"
                                                                            type="radio" name="riderOption"
                                                                            value="unallocated">
                                                                        <label class="form-check-label"
                                                                            for="unallocatedRiders">Unallocated
                                                                            Riders</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input allRiders"
                                                                            type="radio" name="riderOption"
                                                                            value="all">
                                                                        <label class="form-check-label"
                                                                            for="allRiders">All
                                                                            Riders</label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12 mb-3 mt-2">
                                                                    <label for="userId"
                                                                        class="form-label text-primary">Rider</label>
                                                                    <select class="form-control userId" name="userId"
                                                                        required>
                                                                        <option value="">Select Rider</option>
                                                                    </select>
                                                                    <div id="riderInfo" class="text-muted small mt-1"
                                                                        style="display:none;">
                                                                        Please select either <strong>Unallocated
                                                                            Riders</strong> or <strong>All Riders</strong>
                                                                        first.
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-8 mb-3">
                                                                    <label for="vehicle"
                                                                        class="form-label text-primary">Vehicle</label>
                                                                    <input type="text" class="form-control vehicle"
                                                                        name="vehicle_display"
                                                                        placeholder="Select rider to populate" readonly>
                                                                    <input type="hidden" class="vehicleId"
                                                                        name="vehicleId">
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="modal-footer d-flex justify-content-between mt-2 shadow-sm">
                                                                <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">Cancel X</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Submit</button>
                                                            </div>
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

                    <!-- Parcel Collection Details Verification Modal -->
                    <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-success">
                                    <h4 class="modal-title text-white"><strong>Overnight - On-account Parcels
                                            Verification</strong>
                                    </h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">x</button>
                                </div>
                                <div class="modal-body" id="modalItemsBody">
                                    Loading...
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                    document.addEventListener("DOMContentLoaded", function() {

                        // Handle Verify Button Click
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
                                                    <input type="text" name="userId" id="riderName" class="form-control" readonly>
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

                                    let itemsHtml = `
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="shipmentTable">
                                    `;

                                    response.items.forEach((item, index) => {
                                        const volume = item.length * item.width * item.height;

                                        itemsHtml += `
                                            <thead>
                                                <tr>
                                                    <th class="text-primary">Item No.</th>
                                                    <th class="text-primary">Item Name</th>
                                                    <th class="text-primary">Package No</th>
                                                    <th class="text-primary">Weight (Kg)</th>
                                                    <th class="text-primary">Length (cm)</th>
                                                    <th class="text-primary">Width (cm)</th>
                                                    <th class="text-primary">Height (cm)</th>
                                                    <th class="text-primary">Volume (Kg)</th>
                                                    <th class="text-primary">Remarks</th>
                                                </tr>
                                            </thead>

                                            <tr>
                                                <td>${index + 1}
                                                    <input type="hidden" name="items[${index}][id]" value="${item.id}">
                                                </td>
                                                <td><input type="text" name="items[${index}][item_name]" class="form-control" value="${item.item_name}" required></td>
                                                <td><input type="number" name="items[${index}][packages]" class="form-control packages" value="${item.packages_no}" required></td>
                                                <td><input type="number" step="0.01" name="items[${index}][weight]" class="form-control weight" value="${item.weight}" required></td>
                                                <td><input type="number" name="items[${index}][length]" class="form-control length" value="${item.length}"></td>
                                                <td><input type="number" name="items[${index}][width]" class="form-control width" value="${item.width}"></td>
                                                <td><input type="number" name="items[${index}][height]" class="form-control height" value="${item.height}"></td>
                                                <td>${volume}
                                                    <input type="hidden" name="items[${index}][volume]" class="volume" value="${volume}">
                                                </td>
                                                <td><input type="text" name="items[${index}][remarks]" class="form-control" value="${item.remarks ?? ''}"></td>
                                            </tr>

                                            <tr>
                                                <td colspan="9">
                                                    <table class="table table-sm table-bordered mt-2">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th class="text-warning">Sub Item Name</th>
                                                                <th class="text-warning">Quantity</th>
                                                                <th class="text-warning">Weight (Kg)</th>
                                                                <th class="text-warning">Remarks</th>
                                                                <th class="text-warning">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="sub_items-${index}">
                                                            <!-- Sub-items will be appended here -->
                                                        </tbody>
                                                    </table>
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSubItems(${index})">+ Add Sub Item</button>
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
                                                <label class="text-primary"><small>Cost *</small></label>
                                                <input type="number" class="form-control cost" name="cost" id="cost" readonly>
                                            </div>

                                            <input type="hidden" name="base_cost" id="baseCost" value="">

                                            <div class="form-group col-md-2">
                                                <label class="text-primary"><small>Tax (16%)*</small></label>
                                                <input type="number" class="form-control" name="vat" id="vat" readonly>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label class="text-primary"><small>Total Cost*</small></label>
                                                <input type="number" class="form-control" name="total_cost" id="totalCost" readonly>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label class="text-primary"><small>Billing Party</small></label>
                                                <select name="billing_party" class="form-control">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="Sender">Sender</option>
                                                    <option value="Receiver">Receiver</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label class="text-primary"><small>Payment Mode</small></label>
                                                <select name="payment_mode" id="payment_mode" class="form-control payment_mode">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="M-Pesa">M-Pesa</option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="COD">COD</option>
                                                    <option value="Cheque">Cheque</option>
                                                    <option value="Invoice">Invoice</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label class="text-primary"><small>Reference</small></label>
                                                <input type="text" id="reference" name="reference" class="form-control text-uppercase"
                                                    placeholder="e.g. TH647CDTNA" maxlength="10"
                                                    pattern="[A-Z0-9]{10}"
                                                    title="Enter a 10-character M-Pesa code in capital letters with no spaces or special characters"
                                                    oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0,10)">
                                            </div>
                                        </div>

                                        <div class="modal-footer d-flex justify-content-between align-items-center">
                                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel X</button>
                                            <button type="submit" class="btn btn-primary" id="submitVerificationBtn">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                <span class="btn-text">Submit Verification</span>
                                            </button>
                                        </div>
                                    </form>
                                    `;

                                    // Inject HTML into modal
                                    $('#modalItemsBody').html(headerInfo + itemsHtml);

                                    // Fill modal data
                                    $('#requestId').val(request_id);
                                    $('#totalCost').val(total_cost);
                                    $('#cost').val(cost);
                                    $('#riderName').val(rider);
                                    $('#vehicleRegNo').val(vehicle_reg_no);
                                    $('#dateRequested').val(date_requested);
                                    $('#vat').val(vat);
                                    $('#baseCost').val(base_cost);

                                    // Show modal
                                    $('#itemsModal').modal('show');
                                },
                                error: function() {
                                    $('#modalItemsBody').html('<p>Error loading items.</p>');
                                    $('#itemsModal').modal('show');
                                }
                            });
                        });

                        // Recalculate total cost dynamically
                        function recalculateCosts() {
                            let totalWeight = 0;
                            let totalVolume = 0;

                            $('#shipmentTable tbody tr').each(function() {
                                const row = $(this);
                                const weight = parseFloat(row.find('.weight').val()) || 0;
                                const packages = parseFloat(row.find('.packages').val()) || 1;
                                const volume = parseFloat(row.find('.volume').val()) || 1;

                                totalWeight += weight * packages;
                                totalVolume += volume;
                            });

                            const baseCost = parseFloat($('input[name="base_cost"]').val()) || 0;
                            let cost = baseCost;
                            const volumeWeight = totalVolume / 5000;

                            let baseWeight = Math.max(totalWeight, volumeWeight);

                            if (baseWeight > 25) {
                                const extraWeight = baseWeight - 25;
                                cost += extraWeight * 50;
                            }

                            const vat = cost * 0.16;
                            $('input[name="cost"]').val(cost.toFixed(2));
                            $('input[name="vat"]').val(vat.toFixed(2));
                            $('input[name="total_cost"]').val((cost + vat).toFixed(2));
                        }

                        // Watch for changes in volume dimensions
                        $(document).on('input', '.length, .width, .height', function() {
                            const row = $(this).closest('tr');
                            calculateVolume(row);
                        });

                        // Watch for weight or package changes
                        $(document).on('input', '.weight, .packages', function() {
                            recalculateCosts();
                        });

                        // Add Sub-item Rows Dynamically
                        document.querySelectorAll('.add-sub-item-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                const itemIndex = this.getAttribute('data-item-index');
                                let subCount = parseInt(this.getAttribute('data-sub-count'), 10);
                                const tbody = document.querySelector(`#sub-items-body-${itemIndex}`);

                                const newRow = document.createElement('tr');
                                newRow.innerHTML = `
                                    <td><input type="text" name="items[${itemIndex}][sub_items][${subCount}][name]" class="form-control"></td>
                                    <td><input type="number" name="items[${itemIndex}][sub_items][${subCount}][quantity]" class="form-control"></td>
                                    <td><input type="number" step="0.01" name="items[${itemIndex}][sub_items][${subCount}][weight]" class="form-control"></td>
                                    <td><input type="number" name="items[${itemIndex}][sub_items][${subCount}][length]" class="form-control"></td>
                                    <td><input type="number" name="items[${itemIndex}][sub_items][${subCount}][width]" class="form-control"></td>
                                    <td><input type="number" name="items[${itemIndex}][sub_items][${subCount}][height]" class="form-control"></td>
                                    <td><input type="text" name="items[${itemIndex}][sub_items][${subCount}][remarks]" class="form-control"></td>
                                `;

                                tbody.appendChild(newRow);
                                this.setAttribute('data-sub-count', subCount + 1);
                                bindCostListeners(newRow);
                            });
                        });

                        // Handle Payment Mode Change
                        $(document).ready(function() {
                            // Simulate onchange after 10 seconds if user is still on page
                            setTimeout(function() {
                                if ($('#payment_mode').length && $('#payment_mode').val() === 'Invoice') {
                                    $('#payment_mode').trigger('change');
                                }
                            }, 10000); // 10,000 ms = 10 seconds

                            $('#payment_mode').on('change', function() {
                                const mode = $(this).val();

                                if (mode === 'Invoice') {
                                    $.ajax({
                                        url: '{{ route('get.latest.invoice.no') }}',
                                        type: 'GET',
                                        success: function(data) {
                                            $('#reference').val(data.invoice_no);
                                        },
                                        error: function() {
                                            alert('Unable to fetch invoice number.');
                                        }
                                    });
                                } else {
                                    $('#reference').val(''); // clear for other modes
                                }
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
