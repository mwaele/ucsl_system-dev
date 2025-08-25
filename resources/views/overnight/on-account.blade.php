@extends('layouts.custom')

@section('content')
    <div class="card">

        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">

                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#createParcelModal">
                    + Create Parcel
                </button>
                <h4 class="mb-0 text-warning"> <strong>Overnight - On-account Parcels</strong></h4>

                <!-- Date Range Filter -->
                <div id="dateRangeFilter" class="d-flex flex-wrap justify-content-center mt-2">
                    <input type="date" id="startDate" class="form-control ml-2 mb-2" style="width: 200px;">
                    <input type="date" id="endDate" class="form-control ml-2 mb-2" style="width: 200px;">
                    <button id="clearFilter" class="btn btn-secondary ml-2 mb-2">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </div>

                <!-- Generate PDF -->
                <div class="d-flex gap-2 ms-auto">
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
                    function initDateFilter(tableId, dateColIndex, reportUrl, startInputId = "startDate", endInputId = "endDate", reportBtnId = "generateReport", clearBtnId = "clearFilter") {
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

                        reportBtn.addEventListener("click", function () {
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
                            <button type="button" class="text-white close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <h6 class=" text-primary">Fill in the client details.</h6>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="clientId" class="form-label text-primary">Client</label>
                                        <select class="form-control selectpicker" data-live-search="true"
                                            id="clientId" name="clientId">
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
                                            id="collectionLocation" autocomplete="off">
                                        <div id="locationSuggestions"
                                            class="list-group bg-white position-absolute w-80"
                                            style="background-color: white;z-index: 1000;"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">

                                        <label for="clientCategories" class="form-label text-primary">Client
                                            Categories</label>
                                        <!-- Client's Categories -->
                                        <select class="form-control mt-1" id="clientCategories"
                                            name="category_id">
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
                                        <input type="hidden" name="sub_category_id"
                                            value="{{ $sub_category->id }}">
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

                                    <div class="col-md-3 mb-3">
                                        <label for="vehicle" class="form-label text-primary">Vehicle</label>
                                        <input type="text" id="vehicle" class="form-control"
                                            name="vehicle_display" placeholder="Select rider to populate"
                                            readonly>
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

                                    <div class="col-md-3 mb-3">
                                        <label for="priority_level" class="form-label text-primary">Priority
                                            Level</label>
                                        <select class="form-control" name="priority_level"
                                            id="priority_level">
                                            <option value="normal" selected>Normal</option>
                                            <option value="high">High</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3" id="priority-deadline-group"
                                        style="display: none;">
                                        <label for="deadline_date" class="form-label text-primary">
                                            Deadline (If High Priority)
                                        </label>
                                        <input type="datetime-local" class="form-control"
                                            name="deadline_date" id="deadline_date">
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
                <table class="table text-primary table-bordered table-striped table-hover" id="dataTable" width="100%"
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
                                    {{ \Carbon\Carbon::parse($request->dateRequested)->format('F j, Y \a\t g:i A') }}
                                </td>
                                <td> {{ $request->user->name ?? '—' }} </td>
                                <td> {{ $request->vehicle->regNo ?? '—' }} </td>
                                <td> {{ $request->parcelDetails }} </td>
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
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif

                                    @if ($request->shipmentCollection?->payment_mode === 'Invoice')
                                        <a href="{{ route('generate-invoice', $request->id - 1) }}">
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
                                                        <h5 class="modal-title" id="printModalLabel-{{ $request->id }}">
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
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="194" height="71" viewBox="0 0 194 71">
                                                                    <image
                                                                        xlink:href="data:image/png;base64,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"
                                                                        x="0" y="0" width="194" height="71" />
                                                                </svg>
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
                                                                {{ $request->shipmentCollection->items->count() }}</div>
                                                            <div>
                                                                <strong>Date:</strong> {{ now()->format('F j, Y') }}
                                                                <strong style="margin-left: 10px;">Time:</strong>
                                                                {{ now()->format('g:i A') }}
                                                            </div>

                                                            <hr style="margin: 4px 0;">

                                                            <div style="font-weight: bold;">Sender:</div>
                                                            <div>Name: {{ $request->shipmentCollection->sender_name }}
                                                            </div>
                                                            @php
                                                                $phone = $request->shipmentCollection->sender_contact;
                                                                $maskedPhone =
                                                                    substr($phone, 0, 3) .
                                                                    str_repeat('*', strlen($phone) - 6) .
                                                                    substr($phone, -3);
                                                            @endphp

                                                            <div>Phone: {{ $maskedPhone }}</div>
                                                            <div>Location:
                                                                {{ $request->shipmentCollection->sender_address }}</div>
                                                            <div>Town: {{ $request->shipmentCollection->sender_town }}
                                                            </div>
                                                            <hr style="margin: 4px 0;">

                                                            <div style="font-weight: bold;">Receiver:</div>
                                                            <div>Name: {{ $request->shipmentCollection->receiver_name }}
                                                            </div>
                                                            @php
                                                                $phone = $request->shipmentCollection->receiver_phone;
                                                                $maskedPhone =
                                                                    substr($phone, 0, 3) .
                                                                    str_repeat('*', strlen($phone) - 6) .
                                                                    substr($phone, -3);
                                                            @endphp

                                                            <div>Phone: {{ $maskedPhone }}</div>

                                                            <div>Address:
                                                                {{ $request->shipmentCollection->receiver_address }}
                                                            </div>
                                                            <div>Town: {{ $request->shipmentCollection->receiver_town }}
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
                                                                                    $item->packages_no * $item->weight;
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

                                                            <hr style="margin: 6px 0;">
                                                            <div style="text-align: left; font-size: 12px;">


                                                                <div
                                                                    style="display: flex; justify-content: space-between;">

                                                                    <span> {{ $request->vehicle->regNo ?? '' }} </span>
                                                                </div><br>
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
                                        <button class="btn btn-sm btn-warning mr-1" title="View Client Request">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif

                                    @if ($request->status === 'verified')
                                        <button class="btn btn-sm btn-success mr-1" title="Generate Waybill"
                                            data-toggle="modal" data-target="#waybillModal{{ $request->requestId }}">
                                            <i class="fas fa-file-invoice"></i> Generate Waybill
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
                            //alert();
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

                                        itemsHtml += `<thead> <tr><th class="text-primary"> Item No. </th> <th class="text-primary"> Item Name </th> <th class="text-primary"> Package No </th> <th class="text-primary"> Weight(Kg) </th> <th class="text-primary"> Length(cm) </th> <th class="text-primary"> Width(cm) </th> <th class="text-primary"> Height(cm) </th> <th class="text-primary"> Volume(Kg)</th><th class="text-primary"> Remarks </th> </tr> </thead>
                                    <tr><td>${index + 1}<input type="hidden" name="items[${index}][id]" value="${item.id}"></td><td><input type="text" name="items[${index}][item_name]" class="form-control" value="${item.item_name}" required></td><td><input type="number" name="items[${index}][packages]" class="form-control packages" value="${item.packages_no}" required></td><td><input type="number" step="0.01" name="items[${index}][weight]" class="form-control weight" value="${item.weight}" required></td><td><input type="number" name="items[${index}][length]" class="form-control length" value="${item.length}"></td><td><input type="number" name="items[${index}][width]" class="form-control width" value="${item.width}"></td><td><input type="number" name="items[${index}][height]" class="form-control height" value="${item.height}"></td><td>${volume}<input type="hidden" name="items[${index}][volume]" class="volume" value="${volume}"></td><td><input type="text" name="items[${index}][remarks]" class="form-control" value="${item.remarks ?? ''}"></td></tr>


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
                                            <label class="text-primary"><small>Cost *</small></label>
                                            <input type="number" class="form-control cost" name="cost" id="cost" value="" readonly>
                                        </div>

                                        <input type="hidden" name="base_cost" id="baseCost" value="">

                                        <!-- Fragile Goods Checkbox & Cost -->
                                       <!-- <div class="form-group col-md-2">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="fragileCheck">
                                                <label class="form-check-label" for="fragileCheck"><small>Fragile Goods?</small></label>
                                            </div>
                                            <input type="number" class="form-control mt-2" id="fragileCost" placeholder="Fragile Cost" disabled>
                                        </div>-->

                                        <div class="form-group col-md-2">
                                            <label class="text-primary"><small>Tax (16%)*</small></label>
                                            <input type="number" class="form-control" name="vat" id="vat" readonly>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="text-primary"><small>Total Cost*</small></label>
                                            <input type="number" class="form-control" name="total_cost" id="totalCost" value="" readonly>
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
                                            <select name="payment_mode" id="payment_mode"  class="form-control payment_mode">
                                                <option value="" selected>-- Select --</option>
                                                <option value="M-Pesa">M-Pesa</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Invoice">Invoice</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="text-primary"><small>Reference</small></label>
                                            <input type="text" name="reference" id="reference" class="form-control reference" placeholder="e.g. MPESA123XYZ">
                                        </div>
                                    </div>


                                    <div class="modal-footer d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel X</button>
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
                            volumeWeight = totalVolume / 5000;

                            let baseWeight = 0;


                            if (totalWeight > volumeWeight) {
                                baseWeight = totalWeight;
                            }
                            if (volumeWeight > totalWeight) {
                                baseWeight = volumeWeight;
                            }
                            //alert('total volume weight ' + volumeWeight);
                            alert('total weight ' + totalWeight);
                            if (baseWeight > 25) {
                                alert('base weight used ' + baseWeight)
                                const extraWeight = baseWeight - 25;
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
                        $(document).on('change', '.payment_mode', function() {
                            //alert('you clicked me!');
                            const mode = $(this).val();

                            if (mode === 'Invoice') {
                                $.ajax({
                                    url: '{{ route('get.latest.invoice.no') }}',
                                    type: 'GET',
                                    success: function(data) {
                                        $('.reference').val(data
                                            .invoice_no);
                                    },
                                    error: function() {
                                        alert(
                                            'Unable to fetch invoice number.');
                                    }
                                });
                            } else {
                                $('.reference').val(''); // clear for other modes
                            }
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
