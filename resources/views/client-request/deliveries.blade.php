@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-danger">Overnight and Same Day Shipment Deliveries</h4>

                <!-- Right Side (Date Filter + Generate PDF) -->
                <div class="d-flex align-items-center ms-auto">
                    <!-- Date Range Filter -->
                    <div id="dateRangeFilter" class="d-flex flex-wrap align-items-center mr-4">
                        <h5 class="m-0 font-weight-bold text-primary mr-2">Filter by date:</h5>
                        <h5 class="m-0 font-weight-bold mr-2">Start</h5><input type="date" id="startDate" class="form-control me-2 mr-2" style="width: 150px;">
                        <h5 class="m-0 font-weight-bold mr-2">End</h5><input type="date" id="endDate" class="form-control me-2 mr-2" style="width: 150px;">
                        <button id="clearFilter" class="btn btn-secondary mr-2">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                    @if (Auth::user()->role === 'admin')
                        <button id="generateReport" class="btn btn-danger shadow-sm">
                            <i class="fas fa-download fa text-white"></i> Generate Report
                        </button>
                    @endif
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
                        initDateFilter("dataTable", 2, "/collections_report");
                    </script>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive d-none d-md-block">
                <table class="table text-primary table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Req ID</th>
                            <th>Date</th>
                            <th>Client Name</th>
                            <th>Service Level</th>
                            <th>Destination</th>
                            <th>Receiver</th>
                            <th>Waybill</th>
                            <th>Items</th>
                            <th>Weight</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collections as $collection)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $collection->requestId }}</td>
                                <td>{{ \Carbon\Carbon::parse($collection->dateRequested)->format('M d, Y') ?? null }}</td>
                                <td>{{ $collection->client->name ?? '' }}</td>
                                <td>{{ $collection->serviceLevel->sub_category_name }}</td>
                                <td>{{ $collection->shipmentCollection->resolved_destination->destination ?? null }}</td>
                                <td>{{ $collection->shipmentCollection->receiver_name }}</td>
                                <td>{{ $collection->shipmentCollection->waybill_no }}</td>
                                <td>{{ $collection->shipmentCollection?->items?->count() ?? '' }}</td>
                                <td>{{ $collection->shipmentCollection?->total_weight ?? '' }}kg</td>
                                <td>
                                    <span
                                        class="badge p-2 fs-6 text-white
                                        @if ($collection->status == 'pending collection') bg-secondary
                                        @elseif ($collection->status == 'collected') bg-warning
                                        @elseif ($collection->status == 'delivered') bg-success
                                        @elseif ($collection->status == 'delivery_failed') bg-danger
                                        @elseif ($collection->status == 'Delivery Rider Allocated') bg-info
                                        @else bg-dark @endif">
                                        {{ \Illuminate\Support\Str::title($collection->status) }}
                                    </span>

                                    @if ($collection->priority_level == 'high' && $collection->status !== 'delivered')
                                        <span class="badge p-2 mt-2 bg-danger fs-6 text-white">
                                            Deliver by
                                            {{ \Carbon\Carbon::parse($collection->deadline_date)->format('g:i A') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="d-flex flex-wrap gap-2">
                                    @if ($collection->status === 'pending collection')
                                        <button
                                            class="btn btn-warning btn-sm rounded-md flex items-center mr-1 gap-1 shadow-sm"
                                            data-toggle="modal" data-target="#collect-{{ $collection->id }}">
                                            <i class="fas fa-box"></i> Collect
                                        </button>
                                    @endif

                                    @if ($collection->status === 'collected')
                                        <button
                                            class="btn btn-primary btn-sm rounded-md flex items-center gap-1 mb-1 mr-1 shadow-sm"
                                            data-toggle="modal" data-target="#printModal-{{ $collection->id }}">
                                            <i class="fas fa-print"></i> Consignment
                                        </button>

                                        <!-- <button class="btn btn-info btn-sm rounded-md flex items-center gap-1 mb-1 ml-1 mr-1 shadow-sm"
                                                            data-toggle="modal" data-target="#waybillModal{{ $collection->requestId }}">
                                                            <i class="fas fa-file-invoice"></i> Waybill
                                                        </button> -->

                                        <button
                                            class="btn btn-info btn-sm rounded-md flex items-center gap-1 mb-1 mr-1 shadow-sm"
                                            data-toggle="modal" data-target="#handoverModal-{{ $collection->id }}">
                                            <i class="fas fa-exchange-alt"></i> Handover
                                        </button>
                                    @endif

                                    @if (
                                        (($collection->status === 'collected' || $collection->status === 'Delivery Rider Allocated') &&
                                            $collection->serviceLevel->sub_category_name === 'Same Day') ||
                                            ($collection->serviceLevel->sub_category_name === 'Overnight' && $collection->status != 'delivered'))
                                        <button
                                            class="btn btn-success btn-sm rounded-md flex items-center gap-1 mb-1 mr-1 shadow-sm"
                                            data-toggle="modal" data-target="#deliverParcel-{{ $collection->id }}">
                                            <i class="fas fa-box"></i> Deliver
                                        </button>
                                    @endif

                                    @if ($collection->status === 'delivered')
                                        <button
                                            class="btn btn-info btn-sm rounded-md flex items-center mb-1 mr-1 gap-1 shadow-sm"
                                            data-toggle="modal" data-target="#printGDNModal-{{ $collection->id }}">
                                            <i class="fas fa-print"></i> GRN
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="d-block d-md-none">
                @foreach ($collections as $collection)
                    <div class="bg-white mb-3 shadow rounded-xl p-4 border border-gray-200">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 text-primary fw-bold">{{ $loop->iteration }}. Req ID:
                                {{ $collection->requestId }}</h6>
                            <span
                                class="badge p-2 fs-5 text-white
                                @if ($collection->status == 'pending collection') bg-secondary
                                @elseif ($collection->status == 'collected') bg-warning
                                @elseif ($collection->status == 'delivered') bg-success
                                @elseif ($collection->status == 'delivery_failed') bg-danger
                                @elseif ($collection->status == 'Delivery Rider Allocated') bg-info
                                @else bg-dark @endif">
                                {{ \Illuminate\Support\Str::title($collection->status) }}
                            </span>
                        </div>

                        <p class="mb-1"><strong>Client:</strong> {{ $collection->client->name ?? '' }}</p>
                        <p class="mb-1"><strong>Service:</strong> {{ $collection->serviceLevel->sub_category_name }}</p>
                        <p class="mb-1"><strong>Destination:</strong> {{ $collection->shipmentCollection->resolved_destination->destination ?? '' }} </p>
                        <p class="mb-1"><strong>Receiver:</strong>
                            {{ $collection->shipmentCollection->receiver_name }}
                        </p>
                        <p class="mb-1"><strong>Waybill:</strong>
                            {{ $collection->shipmentCollection->waybill_no }}
                        </p>
                        <p class="mb-1"><strong>Date:</strong> {{ $collection->dateRequested }} </p>

                        @if ($collection->priority_level == 'high' && $collection->status !== 'delivered')
                            <p class="badge p-2 mt-2 bg-danger text-white">
                                Deliver by {{ \Carbon\Carbon::parse($collection->deadline_date)->format('g:i A') }}
                            </p>
                        @endif

                        <div class="flex flex-wrap gap-2">
                            @if ($collection->status === 'pending collection')
                                <button
                                    class="btn btn-warning btn-sm rounded-md flex items-center gap-1 shadow-sm w-full sm:w-auto"
                                    data-toggle="modal" data-target="#collect-{{ $collection->id }}">
                                    <i class="fas fa-box"></i> Collect
                                </button>
                            @endif

                            @if ($collection->status === 'collected')
                                <button
                                    class="btn btn-primary btn-sm rounded-md flex items-center gap-1 mb-1 shadow-sm w-full sm:w-auto"
                                    data-toggle="modal" data-target="#printModal-{{ $collection->id }}">
                                    <i class="fas fa-print"></i> Consignment
                                </button>

                                <!-- <button class="btn btn-info btn-sm rounded-md flex items-center gap-1 shadow-sm w-full sm:w-auto"
                                                    data-toggle="modal" data-target="#waybillModal{{ $collection->requestId }}">
                                                    <i class="fas fa-file-invoice"></i> Waybill
                                                </button> -->

                                <button
                                    class="btn btn-info btn-sm rounded-md flex items-center gap-1 shadow-sm w-full sm:w-auto"
                                    data-toggle="modal" data-target="#handoverModal-{{ $collection->id }}">
                                    <i class="fas fa-exchange-alt"></i> Handover
                                </button>
                            @endif

                            @if (
                                (($collection->status === 'collected' || $collection->status === 'Delivery Rider Allocated') &&
                                    $collection->serviceLevel->sub_category_name === 'Same Day') ||
                                    ($collection->serviceLevel->sub_category_name === 'Overnight' && $collection->status != 'delivered'))
                                <button
                                    class="btn btn-success btn-sm rounded-md flex items-center gap-1 shadow-sm w-full sm:w-auto"
                                    data-toggle="modal" data-target="#deliverParcel-{{ $collection->id }}">
                                    <i class="fas fa-box"></i> Deliver
                                </button>
                            @endif

                            @if ($collection->status === 'delivered')
                                <button
                                    class="btn btn-info btn-sm rounded-md flex items-center gap-1 shadow-sm w-full sm:w-auto"
                                    data-toggle="modal" data-target="#printGDNModal-{{ $collection->id }}">
                                    <i class="fas fa-print"></i> GRN
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @foreach ($collections as $collection)
                <!-- Modals for each collection can be included here as needed -->
                <!-- Collect Parcel Modal -->
                <div class="modal fade" id="collect-{{ $collection->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="collectionModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title text-white" id="exampleModalLabel">Collection
                                    of
                                    {{ $collection->parcelDetails }}. Request ID
                                    {{ $collection->requestId }}
                                    for
                                    {{ $collection->client->name ?? '' }}</h5>
                                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('shipment_collections.store') }}">
                                    @csrf

                                    <!-- Radio Buttons -->
                                    <div class="form-group mb-3">

                                        <div class="form-row shadow-sm">
                                            <div class="col-md-4">
                                                <label class="form-label text-primary text-primary">Sender
                                                    Type
                                                </label><br>
                                                <div class="form-check form-check-inline">
                                                    <input type="hidden" name="cid" id="cid"
                                                        value="{{ $collection->client->id }}">
                                                    <input type="hidden" id="service_type"
                                                        value="{{ $collection->serviceLevel->sub_category_name }}">
                                                    <input type="hidden" name="rqid"
                                                        value="{{ $collection->requestId }}">
                                                    <input class="form-check-input clientRadio" type="radio"
                                                        name="sender_type" id="clientRadio" value="client">
                                                    <label class="form-check-label" for="clientRadio">Client</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input agentRadio" type="radio"
                                                        name="sender_type" value="agent" id='agentRadio'>
                                                    <label class="form-check-label" for="agentRadio">Agent</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class=" form-label text-primary text-primary pull-right">Service
                                                    Type:
                                                    <badge class="text-success" id="rates_status">
                                                        {{ $collection->serviceLevel->sub_category_name }}
                                                    </badge>
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class=" form-label text-primary text-primary pull-right">Special
                                                    Rate Status:
                                                    <badge class="text-success" id="rates_status">
                                                        {{ $collection->client->special_rates_status ?? 'off' }}
                                                    </badge>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sender Panel -->
                                    <div class="col-md-12">
                                        <div class="card shadow-sm mb-4" id="senderForm" style="display: none;">
                                            <div class="card-header bg-primary text-white">Sender
                                                Details</div>
                                            <!-- SENDER DETAILS -->
                                            <div class="card-body">
                                                <!-- Sender Details Form (Initially Hidden) -->
                                                <div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label class="form-label text-primary text-primary">Sender
                                                                Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="sender_name"
                                                                id="sender_name">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="form-label text-primary text-primary">Sender
                                                                Email
                                                            </label>
                                                            <input type="email" class="form-control" name="senderEmail"
                                                                id="senderEmail">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label class="form-label text-primary text-primary">ID
                                                                Number </label>
                                                            <input type="text" class="form-control"
                                                                name="sender_id_no" id="sender_id_no">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="form-label text-primary text-primary">Phone
                                                                </label>
                                                            <input type="text" class="form-control"
                                                                name="sender_contact" id="sender_contact">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label class="form-label text-primary text-primary">Town
                                                                <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="sender_town"
                                                                id="sender_town">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="form-label text-primary text-primary">Address
                                                                </label>
                                                            <input type="text" class="form-control"
                                                                name="sender_address" id="sender_address">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Receiver Panel -->
                                    <div class="col-md-12">
                                        <div class="card shadow-sm mb-4">
                                            <div class="card-header bg-primary text-white">Receiver
                                                Details</div>
                                            <!-- RECEIVER DETAILS -->
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label text-primary text-primary">Receiver
                                                            Name <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control"
                                                            name="receiverContactPerson" value="{{ $collection->shipmentCollection?->receiver_name }}" required>
                                                        <input type="hidden" name='client_id'
                                                            value="{{ $collection->client->id }}">
                                                        <input type="hidden" name="requestId"
                                                            value="{{ $collection->requestId }}">


                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="form-label text-primary text-primary">Receiver
                                                            Email
                                                        </label>
                                                        <input type="email" class="form-control" name="receiverEmail" 
                                                        value="{{ $collection->shipmentCollection?->receiver_email }}"
                                                            >
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label text-primary text-primary">ID
                                                            Number
                                                        </label>
                                                        <input type="text" class="form-control" name="receiverIdNo"
                                                        value="{{ $collection->shipmentCollection?->receiver_id_no }}">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label text-primary text-primary">Phone
                                                            Number
                                                        </label>
                                                        <input type="text" class="form-control" name="receiverPhone"
                                                        value="{{ $collection->shipmentCollection?->receiver_phone }}">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label text-primary text-primary">Address
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="receiverAddress"
                                                        value="{{ $collection->shipmentCollection?->receiver_address }}" required>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label text-primary text-primary">Town
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="receiverTown"
                                                        value="{{ $collection->shipmentCollection?->receiver_town }}"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($collection->client->special_rates_status)
                                        <!-- Origin & Destination -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label text-primary text-primary">Origins
                                                    <span class="text-danger">*</span> </label>
                                                <select name="origin_id" id="origin_id_special"
                                                    class="form-control origin-dropdown-special">
                                                    <option value="">Select</option>
                                                    @foreach ($offices as $office)
                                                        <option value="{{ $office->id }}">
                                                            {{ $office->name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label text-primary text-primary">Destination
                                                    <span class="text-danger">*</span> </label>
                                                <select name="destination"
                                                    class="form-control destination-dropdown-special">
                                                    <option value="">Select</option>
                                                    <option value="{{ $collection->shipmentCollection?->destination_id }}">
                                                        {{ $collection->shipmentCollection?->receiver_town }}
                                                    </option>
                                                </select>
                                            </div>
                                            <input type="hidden" 
                                            value="{{ $collection->shipmentCollection->destination_id }}"
                                            name='destination_id' id="destination_id_special">
                                        </div>
                                        {{-- <input type="hidden" name='destination' id="destination_id">

                                        <input type="hidden" name='origin_id' id="origin_id"> --}}
                                    @elseif($collection->serviceLevel->sub_category_name == 'Same Day')
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label text-primary text-primary">Origin
                                                    <span class="text-danger">*</span> </label>
                                                <select name="origin_id" id="origin_idxz"
                                                    class="form-control origin-dropdownxz" required>
                                                    <option value="">Select</option>
                                                    <option value="{{ $collection->office_id }}">
                                                        {{ $collection->office->name ?? '' }}</option>

                                                    {{-- @foreach ($offices as $office)
                                                        <option value="{{ $office->id }}">
                                                            {{ $office->name ?? "" }}</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label class="form-label text-primary text-primary">Destination

                                                    <span class="text-danger">*</span> </label>
                                                <select name="destination" class="form-control destination-dropdownxz">
                                                    <option value="">Select</option>
                                                    <option value="{{ $collection->rate_id }}">
                                                        {{ $collection->collectionLocation }}
                                                    </option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="destination_id"
                                                value="{{ $collection->rate_id }}">

                                        </div>
                                    @else
                                        <!-- Origin & Destination -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label text-primary text-primary">Origin
                                                    <span class="text-danger">*</span> </label>
                                                <select name="origin_id" id="origin_id"
                                                    class="form-control origin-dropdown" required>
                                                    <option value="">Select</option>
                                                    @foreach ($offices as $office)
                                                        <option value="{{ $office->id }}">
                                                            {{ $office->name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label text-primary text-primary">Destination
                                                    <span class="text-danger">*</span> </label>
                                                <select name="destination" class="form-control destination-dropdown">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                            <input type="hidden" class="destination_id" name='destination_id'
                                                id="destination_id">

                                        </div>
                                    @endif

                                    <input type="hidden" value="{{ $collection->client->special_rates_status }}"
                                        name='special_rate_state' id="special_rate_state">

                                    <!-- Shipment Info Table -->
                                    {{-- <div class="section-title"><b class="text-primary">Shipment Information</b></div> --}}
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered shipmentTable" id="shipmentTable">
                                            <thead class="thead-success">
                                                <tr class="text-primary">
                                                    <th>Item Name</th>
                                                    <th>Packages #</th>
                                                    <th>Weight (kg)</th>
                                                    <th>Length (cm)</th>
                                                    <th>Width (cm)</th>
                                                    <th>Height (cm)</th>
                                                    <th class="text-center">Volume Weight (Kg)</th>
                                                    <th>Act</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($collection->shipmentCollection?->items ?? [] as $item)
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control" name="item_name[]"
                                                                value="{{ $item->item_name }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0" class="form-control" name="packages[]"
                                                                value="{{ $item->packages_no }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0" class="form-control" name="weight[]"
                                                                value="{{ $item->weight }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0" class="form-control" name="length[]"
                                                                value="{{ $item->length }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0" class="form-control" name="width[]"
                                                                value="{{ $item->width }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" min="0" class="form-control" name="height[]"
                                                                value="{{ $item->height }}">
                                                        </td>
                                                        <td class="volume-display text-muted">
                                                            <input type="number" min="0" class="form-control" name="volume[]"
                                                                value="{{ $item->volume }}" readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm remove-row" title="Delete Row">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    {{-- Show one blank row if no records --}}
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="item_name[]"></td>
                                                        <td><input type="number" min="0" class="form-control" name="packages[]"></td>
                                                        <td><input type="number" min="0" class="form-control" name="weight[]"></td>
                                                        <td><input type="number" min="0" class="form-control" name="length[]"></td>
                                                        <td><input type="number" min="0" class="form-control" name="width[]"></td>
                                                        <td><input type="number" min="0" class="form-control" name="height[]"></td>
                                                        <td class="volume-display text-muted">
                                                            <input type="number" min="0" class="form-control" name="volume[]" readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm remove-row" title="Delete Row">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <button type="button" class="btn btn-primary mb-3 addRowBtn" id="addRowBtn">Add
                                        Row</button>

                                    <!-- Service Level -->
                                    <div class="section-title"></div>
                                    <div class="form-row">
                                        {{-- <div class="form-group col-md-6">
                                            <label class="form-label text-primary text-primary">Select Service <span
                                                    class="text-danger">*</span> </label>
                                            <select class="form-control" name="service" required>
                                                <option value="">-- Select Service --</option>
                                                <option value="standard">Standard</option>
                                                <option value="express">Express</option>
                                                <option value="overnight">Overnight</option>
                                            </select>
                                        </div> --}}
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label class="form-label text-primary text-primary">Total
                                                    Weight
                                                    (Kg)
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" min="0" class="form-control"
                                                    name="total_weight" value="{{ $collection->shipmentCollection?->total_weight }}" required readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label text-primary text-primary">Cost
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" min="0" class="form-control" name="cost" value="{{ $collection->shipmentCollection?->cost ?? $collection->rate}}" required readonly>
                                            </div>
                                            <input type="hidden" name="base_cost" value="">

                                            <div class="form-group col-md-3">
                                                <label class="form-label text-primary text-primary">Tax
                                                    (16%) <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" min="0" class="form-control" name="vat"
                                                    value="{{ $collection->shipmentCollection?->vat }}"
                                                    required readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label text-primary text-primary">Total
                                                    Cost
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" min="0" class="form-control"
                                                    name="total_cost" value="{{ $collection->shipmentCollection?->total_cost }}" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between p-0">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                                            aria-label="Close">Cancel</button>
                                        <button type="submit" class="btn btn-success text-white">Submit
                                            Collection</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipment Receipt Modal -->
                @if ($collection->shipmentCollection)
                    <div class="modal fade" id="printModal-{{ $collection->id }}" tabindex="-1"
                        aria-labelledby="printModalLabel-{{ $collection->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content" id="print-modal-{{ $collection->id }}">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="printModalLabel-{{ $collection->id }}">
                                        Shipment Receipt</h5>
                                    <button type="button" class="text-primary close" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="print-content-{{ $collection->id }}-consignment">
                                    <div id="receipt-{{ $collection->id }}"
                                        style="font-family: monospace; font-size: 13px; line-height: 1.2;">
                                        <div style="text-align: center;">
                                            <img loading="lazy" src="{{ asset('images/ucsl_logo_monochrome.png') }}" alt="Logo"
                                                style="height: 70px;">
                                        </div>
                                        <div style="text-align: center; font-size: 15px;">
                                            <strong>Parcel
                                                Consignment Note</strong>
                                        </div>
                                        <hr style="margin: 4px 0;">

                                        <div style="font-size: 14px;"><strong>Request ID:
                                                {{ $collection->requestId ?? 'N/A' }}</strong></div>
                                        <div style="font-size: 14px;"><strong>Consignment Note Number:
                                                {{ $collection->consignment_no ?? 'N/A' }}</strong>
                                        </div>
                                        <div>
                                            <strong>From:</strong>
                                            {{ $collection->shipmentCollection->office->name ?? '' }}
                                        </div>
                                        <div>
                                            <strong>To:</strong>
                                            {{ $collection->shipmentCollection->resolved_destination->destination ?? '' }}
                                        </div>
                                        <div><strong>Total Items:</strong>
                                            {{ $collection->shipmentCollection->items->count() }}</div>
                                        <div>
                                            <strong>Date:</strong> {{ now()->format('M d, Y \a\t h:i A') }}
                                        </div>

                                        <hr style="margin: 4px 0;">

                                        <div style="font-weight: bold;">Sender:</div>
                                        <div>Name: {{ $collection->shipmentCollection->sender_name }}
                                        </div>
                                        <div>KRA PIN:
                                            {{ $collection->shipmentCollection->client->kraPin }}
                                        </div>

                                        @php
                                            $phone = $collection->shipmentCollection->sender_contact;
                                            if (!empty($phone) && strlen($phone) > 6) {
                                                $maskedPhone =
                                                    substr($phone, 0, 3) .
                                                    str_repeat('*', strlen($phone) - 6) .
                                                    substr($phone, -3);
                                            } else {
                                                // If phone is too short, just show as is (or handle differently)
                                                $maskedPhone = $phone;
                                            }
                                        @endphp

                                        <div>Phone: {{ $maskedPhone }}</div>
                                        <div>Location:
                                            {{ $collection->shipmentCollection->sender_address }}</div>
                                        <div>Town: {{ $collection->shipmentCollection->sender_town }}
                                        </div>
                                        <hr style="margin: 4px 0;">

                                        <div style="font-weight: bold;">Receiver:</div>
                                        <div>Name: {{ $collection->shipmentCollection->receiver_name }}
                                        </div>

                                        @php
                                            $phone = $collection->shipmentCollection?->receiver_phone;

                                            if (empty($phone)) {
                                                $maskedPhone = 'N/A'; // or leave blank, or whatever you want
                                            } else {
                                                $len = strlen($phone);

                                                if ($len > 6) {
                                                    $maskedPhone =
                                                        substr($phone, 0, 3) .
                                                        str_repeat('*', $len - 6) .
                                                        substr($phone, -3);
                                                } else {
                                                    $maskedPhone =
                                                        str_repeat('*', max($len - 1, 0)) . substr($phone, -1);
                                                }
                                            }
                                        @endphp

                                        <div>Phone: {{ $maskedPhone }}</div>

                                        <div>Address:
                                            {{ $collection->shipmentCollection->receiver_address }}
                                        </div>
                                        <div>Town: {{ $collection->shipmentCollection->receiver_town }}
                                        </div>
                                        <hr style="margin: 4px 0;">

                                        <div style="font-weight: bold;">Parcel Details:</div>
                                        @if ($collection->shipmentCollection && $collection->shipmentCollection->items->count())
                                            @php
                                                $totalWeight = 0;
                                            @endphp
                                            <table style="width: 100%; border-collapse: collapse; margin-bottom: 4px;">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: left;">#</th>
                                                        <th style="text-align: left;">Desc.</th>
                                                        <th style="text-align: center;">Qty</th>
                                                        <th style="text-align: right;">Wt(kg)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($collection->shipmentCollection->items as $item)
                                                        @php
                                                            $totalWeight += $item->packages_no * $item->weight;
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

                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Total Weight:</strong>
                                                <span>{{ number_format($totalWeight, 2) }}
                                                    {{ $totalWeight > 1 ? 'Kgs' : 'Kg' }}</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Base Cost:</strong>
                                                <span>Ksh
                                                    {{ number_format($collection->shipmentCollection->cost, 2) }}</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>VAT:</strong>
                                                <span style="text-decoration: underline;"> Ksh
                                                    {{ number_format($collection->shipmentCollection->vat, 2) }}</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Total:</strong>
                                                <span> Ksh
                                                    {{ number_format($collection->shipmentCollection->total_cost, 2) }}</span>
                                            </div>
                                        @else
                                            <p>No shipment items found.</p>
                                        @endif

                                        {{-- Priority & Fragile Check --}}
                                        @if ($collection->priority_level === 'high' && $collection->fragile_item === 'yes')
                                            <div style="margin-top: 8px; color: red; font-weight: bold;">
                                                *** High Priority & Fragile Parcel ***
                                            </div>
                                        @elseif ($collection->priority_level === 'high')
                                            <div style="margin-top: 8px; color: red; font-weight: bold;">
                                                *** High Priority ***
                                            </div>
                                        @elseif ($collection->fragile_item === 'yes')
                                            <div style="margin-top: 8px; color: red; font-weight: bold;">
                                                *** Fragile Parcel ***
                                            </div>
                                        @endif

                                        {{-- Priority & Fragile Check --}}
                                        @if (
                                            $collection->shipmentCollection->priority_level === 'high' &&
                                                $collection->shipmentCollection->fragile_item === 'yes')
                                            <div style="margin-top: 8px; color: red; font-weight: bold;">
                                                *** High Priority & Fragile Parcel ***
                                            </div>
                                        @elseif ($collection->shipmentCollection->priority_level === 'high')
                                            <div style="margin-top: 8px; color: red; font-weight: bold;">
                                                *** High Priority ***
                                            </div>
                                        @elseif ($collection->shipmentCollection->fragile_item === 'yes')
                                            <div style="margin-top: 8px; color: red; font-weight: bold;">
                                                *** Fragile Parcel ***
                                            </div>
                                        @endif

                                        <hr style="margin: 6px 0;">
                                        <div style="text-align: left; font-size: 12px;">
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Collected By:</strong>
                                                <span> {{ $collection->user->name ?? '' }} </span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Contact:</strong>
                                                @php
                                                    $phone = $collection->user->phone_number ?? '';
                                                    if (!empty($phone) && strlen($phone) > 6) {
                                                        $maskedPhone =
                                                            substr($phone, 0, 3) .
                                                            str_repeat('*', strlen($phone) - 6) .
                                                            substr($phone, -3);
                                                    } else {
                                                        // If phone is too short, just show as is (or handle differently)
                                                        $maskedPhone = $phone;
                                                    }
                                                @endphp
                                                <span> {{ $maskedPhone }} </span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Vehicle REG:</strong>
                                                <span> {{ $collection->vehicle->regNo ?? '' }} </span>
                                            </div><br>
                                            <div class=" mt-0 pt-0 " style="text-align: center">
                                                <img loading="lazy" src="{{ asset('qrcodes') . '/' . $collection->requestId . '.svg' }}"
                                                    alt="Authorized QR Code"
                                                    style="width: 120px; height: auto; margin-top: 10px;">
                                            </div>
                                            <br>
                                            <hr style="margin: 6px 0;">
                                            {{-- These are provisional charges based on details provided by
                                        sender.<br><br> --}}
                                            <strong>TERMS & CONDITIONS</strong><br>
                                            Carriage of this shipment is subject to the terms and
                                            conditions. Visit www.ufanisicourier.co.ke/terms
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                        aria-label="Close">Close</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="printModalContent({{ $collection->id }}, 'consignment')">Print</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Deliver Modal -->
                @if ($collection->shipmentCollection)
                    <div class="modal fade" id="deliverParcel-{{ $collection->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="deliverParcel-{{ $collection->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title text-white" id="exampleModalLabel">Delivery
                                        of
                                        {{ $collection->parcelDetails }}. Request ID
                                        {{ $collection->requestId }}
                                        for
                                        {{ $collection->client->name ?? '' }}</h5>
                                    <button type="button" class="text-white close" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('my_deliveries.store') }}">
                                        @csrf

                                        <!-- Always submitted hidden fields -->
                                        <input type="hidden" name="grn_no" value="{{ $grn_no }}">
                                        <input type="hidden" name="shipment_collection_id"
                                            value="{{ $collection->shipmentCollection->id }}">
                                        <input type="hidden" name="client_id" value="{{ $collection->client->id }}">
                                        <input type="hidden" name="requestId" value="{{ $collection->requestId }}">
                                        <input type="hidden" name="delivery_location"
                                            value="{{ $collection->shipmentCollection->resolved_destination->destination ?? '' }}">

                                        @php
                                            $hasPayment = $collection->shipmentCollection->payment !== null;
                                            $balance = $hasPayment
                                                ? $collection->shipmentCollection->payment->balance
                                                : null;
                                        @endphp

                                        {{-- Payment Section (Only if unpaid or has balance) --}}
                                        @if (!$hasPayment || $balance > 0)
                                            <div class="mb-3">
                                                @if (!$hasPayment)
                                                    <span class="badge bg-danger text-white">
                                                        Unpaid – To pay Ksh.
                                                        {{ ($collection->shipmentCollection->actual_total_cost ?? 0) +
                                                            ($collection->shipmentCollection->last_mile_delivery_charges ?? 0) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-info text-white">
                                                        Paid:
                                                        {{ $collection->shipmentCollection->payment_mode }}
                                                    </span>
                                                    <span class="badge bg-primary text-white">
                                                        Ksh.
                                                        {{ number_format(optional($collection->payments)->amount ?? 0, 0) }}

                                                    </span>
                                                    <span class="badge bg-warning text-white">
                                                        Balance: Ksh. {{ number_format($balance, 0) }}
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Record Payment --}}
                                            <div class="card shadow-sm border-primary mb-3">
                                                <div class="card-header bg-primary text-white">
                                                    Record Payment
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        {{-- Payment Mode --}}
                                                        <div class="col-md-4"> 
                                                            <label for="payment_mode" class="text-primary">
                                                                <h6>Payment Mode</h6>
                                                            </label>

                                                            @php
                                                                $paymentMethod = old('payment_mode', optional($collection->shipmentCollection)->payment_mode);
                                                            @endphp

                                                            <select id="payment_mode" name="payment_mode" class="form-control" required>
                                                                <option value="" disabled {{ empty($paymentMethod) ? 'selected' : '' }}>
                                                                    -- Select Payment Mode --
                                                                </option>

                                                                <option value="M-Pesa"  {{ $paymentMethod === 'M-Pesa' ? 'selected' : '' }}>M-Pesa</option>
                                                                <option value="Cash"    {{ $paymentMethod === 'Cash' ? 'selected' : '' }}>Cash</option>
                                                                <option value="COD"     {{ $paymentMethod === 'COD' ? 'selected' : '' }}>COD</option>
                                                                <option value="Cheque"  {{ $paymentMethod === 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                                                <option value="Invoice" {{ $paymentMethod === 'Invoice' ? 'selected' : '' }}>Invoice</option>
                                                            </select>
                                                        </div>

                                                        {{-- Reference --}}
                                                        <div class="col-md-4">
                                                            <label for="reference" class="text-primary">
                                                                <h6>Payment Reference</h6>
                                                            </label>
                                                            <input type="text" id="reference" name="reference"
                                                                class="form-control text-uppercase"
                                                                {{-- placeholder="e.g. TH647CDTNA" maxlength="10"
                                                                pattern="[A-Z0-9]{10}"
                                                                title="Enter a 10-character M-Pesa code in capital letters with no spaces or special characters"
                                                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0,10)" --}}
                                                                value="{{ $collection->shipmentCollection->reference ?? '' }}"
                                                                required>
                                                        </div>

                                                        {{-- Amount --}}
                                                        <div class="col-md-4">
                                                            <label for="amount_paid" class="text-primary">
                                                                <h6>Amount</h6>
                                                            </label>
                                                            <input type="number" id="amount_paid" name="amount_paid"
                                                                class="form-control" placeholder="Enter amount paid"
                                                                value="{{ ($collection->shipmentCollection->actual_total_cost ?? 0) +
                                                                    ($collection->shipmentCollection->last_mile_delivery_charges ?? 0) }}"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Selection: Receiver or Agent -->
                                        <div class="form-group">
                                            <label class="text-primary">Select Delivery
                                                Type:</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input select_receiver" type="radio"
                                                    name="delivery_type" id="select_receiver" value="receiver">
                                                <label class="form-check-label" for="select_receiver">Receiver</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input select_agent" type="radio"
                                                    name="delivery_type" id="select_agent" value="agent">
                                                <label class="form-check-label" for="select_agent">Failed Delivery</label>
                                            </div>
                                        </div>

                                        @php
                                            $isApproved = $approvalStatuses[$collection->requestId] ?? false;
                                        @endphp

                                        <!-- Receiver Panel -->
                                        @if (!$isApproved)
                                            <div class="col-md-12 receiver_panel" id="receiver_panel"
                                                style="display: none;">
                                                <div class="card shadow-sm mb-4">
                                                    <div class="card-header bg-primary text-white">
                                                        Receiver Details</div>
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-primary">
                                                                    Receiver Name <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="text" class="form-control"
                                                                    name="receiver_name"
                                                                    value="{{ $collection->shipmentCollection->receiver_name ?? '' }}">
                                                                <input type="hidden" name="receiver_type"
                                                                    value="receiver">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-primary">
                                                                    Phone Number 
                                                                </label>
                                                                <input type="text" class="form-control"
                                                                    name="receiver_phone"
                                                                    value="{{ $collection->shipmentCollection->receiver_phone ?? '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-primary">
                                                                    ID Number
                                                                </label>
                                                                <input type="text" class="form-control"
                                                                    name="receiver_id_no" maxlength="8"
                                                                    value="{{ $collection->shipmentCollection->receiver_id_no ?? '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-info mt-3">
                                                Receiver details are disabled because the agent request
                                                has already been approved.
                                            </div>
                                        @endif

                                        @if ($isApproved)
                                            <!-- Agent pickup is approved -->
                                            <div class="col-md-12 agent_panel" id="agent_panel">
                                                <div class="card shadow-sm mb-4">
                                                    <div class="card-header bg-primary text-white">
                                                        Agent Details</div>
                                                    <div class="card-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-primary">Agent
                                                                    Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="agent_name"
                                                                    value="{{ $collection->shipmentCollection?->agent->agent_name ?? '' }}">
                                                                <input type="hidden" name="receiver_type"
                                                                    value="agent">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-primary">Agent
                                                                    Phone Number</label>
                                                                <input type="text" class="form-control"
                                                                    name="agent_phone"
                                                                    value="{{ $collection->shipmentCollection?->agent->agent_phone_no ?? '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-primary">Agent
                                                                    ID Number</label>
                                                                <input type="text" class="form-control"
                                                                    name="agent_id_no" maxlength="8"
                                                                    value="{{ $collection->shipmentCollection?->agent->agent_id_no ?? '' }}">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-primary">
                                                                    Remarks <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="text" class="form-control" name="remarks"
                                                                    value="{{ $collection->shipmentCollection?->agent->agent_reason ?? '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Agent Approval Request -->
                                            <div class="col-md-12 agent_request" id="agent_request"
                                                style="display: none;">
                                                <div class="card shadow-sm mb-4">
                                                    <div class="card-header bg-primary text-white">
                                                        Sent Communication to the Front Office</div>
                                                    <div class="card-body">
                                                        {{-- <p>Please request approval from the front office
                                                            for this agent to collect the delivery.</p> --}}
                                                        <div class="row g-2 mb-2">
                                                            <div class="col-md-12">
                                                                <label for="agent_name_{{ $collection->id }}"
                                                                    class="form-label">Reason for
                                                                    Failure <span class="text-danger">*</span></label>
                                                                <select name="reason" class="form-control"
                                                                    id="reason_{{ $collection->id }}">
                                                                    <option value="">-- Select --
                                                                    </option>
                                                                    @foreach ($failed_deliveries as $failed_delivery)
                                                                        <option value="{{ $failed_delivery->id }}">
                                                                            {{ $failed_delivery->reason }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                {{-- <input type="text"
                                                                    class="form-control"
                                                                    id="agent_name_{{ $collection->id }}"
                                                                    placeholder="Agent name"> --}}
                                                            </div>
                                                            {{-- <div class="col-md-4">
                                                                <label
                                                                    for="agent_id_{{ $collection->id }}"
                                                                    class="form-label">ID No.</label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    id="agent_id_{{ $collection->id }}"
                                                                    placeholder="ID number">
                                                            </div> --}}
                                                            {{-- <div class="col-md-4">
                                                                <label
                                                                    for="agent_phone_{{ $collection->id }}"
                                                                    class="form-label">Phone</label>
                                                                <input type="text"
                                                                    class="form-control"
                                                                    id="agent_phone_{{ $collection->id }}"
                                                                    placeholder="Phone number">
                                                            </div> --}}
                                                            <div class="col-md-12">
                                                                <label for="agent_reason_{{ $collection->id }}"
                                                                    class="form-label">Remarks <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    id="remarks_{{ $collection->id }}"
                                                                    placeholder="Reason">
                                                            </div>
                                                        </div>
                                                        <button type="button" id="approvalBtn-{{ $collection->id }}"
                                                            class="btn btn-warning"
                                                            onclick="submitApprovalRequest('{{ $collection->requestId }}', '{{ $collection->id }}', this)">
                                                            Submit
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Form Actions -->
                                        <div class="modal-footer d-flex p-0">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                aria-label="Close">Cancel</button>
                                            <button type="submit" class="btn btn-success text-white" disabled>
                                                Submit Collection
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- JavaScript for Dynamic Display -->
                                <script>
                                    // Bootstrap validation styling
                                    (() => {
                                        'use strict';
                                        const forms = document.querySelectorAll('.needs-validation');

                                        Array.from(forms).forEach(form => {
                                            form.addEventListener('submit', event => {
                                                const agentIdInput = form.querySelector('[name="agent_id"]');
                                                const idValue = parseInt(agentIdInput.value, 10);

                                                // Check numeric range
                                                if (idValue < 8999999 || idValue > 99999999) {
                                                    agentIdInput.classList.add('is-invalid');
                                                    event.preventDefault();
                                                    event.stopPropagation();
                                                    return;
                                                }

                                                if (!form.checkValidity()) {
                                                    event.preventDefault();
                                                    event.stopPropagation();
                                                }

                                                form.classList.add('was-validated');
                                            }, false);
                                        });
                                    })();

                                    function validateAgentId(input) {
                                        // Enforce numeric-only and max 8 characters
                                        input.value = input.value.replace(/\D/g, '').slice(0, 8);
                                    }
                                </script>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Loop through every modal
                                        document.querySelectorAll('.modal').forEach(function(modal) {
                                            const receiverRadio = modal.querySelector('.select_receiver');
                                            const agentRadio = modal.querySelector('.select_agent');
                                            const receiverPanel = modal.querySelector('.receiver_panel');
                                            const agentRequest = modal.querySelector('.agent_request');
                                            const agentPanel = modal.querySelector('.agent_panel');

                                            if (!receiverRadio || !agentRadio) return; // skip if not found

                                            function togglePanels() {
                                                if (receiverRadio.checked) {
                                                    receiverPanel.style.display = 'block';
                                                    if (agentRequest) agentRequest.style.display = 'none';
                                                    if (agentPanel) agentPanel.style.display = 'none';
                                                } else if (agentRadio.checked) {
                                                    receiverPanel.style.display = 'none';
                                                    if (agentRequest) agentRequest.style.display = 'block';
                                                    if (agentPanel) agentPanel.style.display = 'block';
                                                }
                                            }

                                            // Attach listeners for this modal only
                                            receiverRadio.addEventListener('change', togglePanels);
                                            agentRadio.addEventListener('change', togglePanels);
                                        });
                                    });
                                </script>

                                <script>
                                    function submitApprovalRequest(requestId, collectionId, btn) {
                                        const reason = document.getElementById(`reason_${collectionId}`).value.trim();
                                        const remarks = document.getElementById(`remarks_${collectionId}`).value.trim();

                                        // Validate input fields
                                        if (!reason || !remarks) {
                                            alert("Please fill in both Reason and Remarks before submitting.");
                                            return;
                                        }

                                        btn.disabled = true;
                                        btn.innerText = "Submitting...";

                                        fetch("{{ route('failed_delivery_alert') }}", {
                                                method: "POST",
                                                headers: {
                                                    "Content-Type": "application/json",
                                                    "X-CSRF-TOKEN": '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({
                                                    requestId,
                                                    reason,
                                                    remarks,
                                                })
                                            })
                                            .then(response => {
                                                if (!response.ok) throw new Error("Failed to send alert to the front office");
                                                return response.json();
                                            })
                                            .then(data => {
                                                $('#deliverParcel-' + collectionId).modal('hide');
                                                alert("Alert sent successfully to front office.");
                                            })
                                            .catch(error => {
                                                btn.disabled = false;
                                                btn.innerText = "Submit Request";
                                                alert("Error: " + error.message);
                                            });
                                    }
                                </script>


                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const isApproved = @json($approvalStatuses[$collection->requestId] ?? false);

                                        // Inside togglePanels
                                        if (agentRadio.checked) {
                                            receiverPanel.style.display = 'none';
                                            agentRequest.style.display = isApproved ? 'none' : 'block';
                                            if (agentPanel) agentPanel.style.display = isApproved ? 'block' : 'none';
                                        }
                                    });
                                </script>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const modal = document.getElementById('deliverParcel-{{ $collection->id }}');
                                        if (!modal) return;

                                        const receiverRadio = modal.querySelector('.select_receiver');
                                        const agentRadio = modal.querySelector('.select_agent');
                                        const receiverPanel = modal.querySelector('.receiver_panel');
                                        const agentPanel = modal.querySelector('.agent_panel');
                                        const submitBtn = modal.querySelector('button[type="submit"]');

                                        const receiverFields = [
                                            'input[name="receiver_name"]',
                                            'input[name="receiver_phone"]',
                                            'input[name="receiver_id_no"]'
                                        ];

                                        const agentFields = [
                                            'input[name="agent_name"]',
                                            'input[name="agent_phone"]',
                                            'input[name="agent_id_no"]',
                                            'input[name="remarks"]'
                                        ];

                                        function getValues(selectors) {
                                            return selectors.map(selector => {
                                                const el = modal.querySelector(selector);
                                                return el && el.offsetParent !== null ? el.value.trim() : ''; // only if visible
                                            });
                                        }

                                        function validateFields() {
                                            let isValid = false;

                                            if (receiverPanel && receiverPanel.style.display !== 'none') {
                                                const values = getValues(receiverFields);
                                                isValid = values.every(v => v !== '');
                                            }

                                            if (agentPanel && agentPanel.style.display !== 'none') {
                                                const values = getValues(agentFields);
                                                isValid = values.every(v => v !== '');
                                            }

                                            if (submitBtn) {
                                                submitBtn.disabled = !isValid;
                                            }
                                        }

                                        // Listen to all inputs in both panels
                                        [...receiverFields, ...agentFields].forEach(selector => {
                                            const input = modal.querySelector(selector);
                                            if (input) {
                                                input.addEventListener('input', validateFields);
                                            }
                                        });

                                        // Revalidate on delivery type change
                                        if (receiverRadio) receiverRadio.addEventListener('change', validateFields);
                                        if (agentRadio) agentRadio.addEventListener('change', validateFields);

                                        // Initial validation
                                        validateFields();
                                    });
                                </script>

                            </div>
                        </div>
                    </div>
                @endif

                <!-- GRN Modal -->
                @if ($collection->shipmentCollection)
                    <div class="modal fade" id="printGDNModal-{{ $collection->id }}" tabindex="-1"
                        aria-labelledby="printGDNModalLabel-{{ $collection->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content" id="print-modal-{{ $collection->id }}">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="printModalLabel-{{ $collection->id }}">
                                        Goods Received Note</h5>
                                    <button type="button" class="text-primary close" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="print-content-{{ $collection->id }}-grn">
                                    <div id="receipt-{{ $collection->id }}"
                                        style="font-family: monospace; font-size: 13px; line-height: 1.2;">
                                        <div style="text-align: center;">
                                            <img loading="lazy" src="{{ asset('images/ucsl_logo_monochrome.png') }}" alt="Logo"
                                                style="height: 70px;">
                                        </div>
                                        <div style="text-align: center; font-size: 15px;"><strong>Goods
                                                Received Note</strong></div>
                                        <hr style="margin: 4px 0;">

                                        <div style="font-size: 14px;"><strong>Request ID:
                                                {{ $collection->requestId ?? 'N/A' }}</strong></div>
                                        <div style="font-size: 14px;"><strong>Goods Received Note No:
                                                {{ $collection->shipmentCollection->grn_no ?? 'N/A' }}</strong>
                                        </div>
                                        <div>
                                            <strong>From:</strong>
                                            {{ $collection->shipmentCollection->office->name ?? '' }}
                                        </div>
                                        <div>
                                            <strong>To:</strong>
                                            {{ $collection->shipmentCollection->resolved_destination->destination ?? '' }}
                                        </div>
                                        <div><strong>Total Items:</strong>
                                            {{ $collection->shipmentCollection->items->count() }}
                                        </div>
                                        <div>
                                            <strong>Date:</strong> {{ now()->format('M d, Y \a\t h:i A') }}
                                        </div>

                                        <hr style="margin: 4px 0;">

                                        <div style="font-weight: bold;">Sender:</div>
                                        <div>Name: {{ $collection->shipmentCollection->sender_name }}
                                        </div>
                                        <div>KRA PIN:
                                            {{ $collection->shipmentCollection->client->kraPin }}
                                        </div>

                                        @php
                                            $phone = $collection->client->contact;

                                            if (empty($phone)) {
                                                // Handle null or empty phone
                                                $maskedPhone = 'N/A';
                                            } else {
                                                $len = strlen($phone);

                                                if ($len > 6) {
                                                    // Normal masking
                                                    $maskedPhone =
                                                        substr($phone, 0, 3) .
                                                        str_repeat('*', $len - 6) .
                                                        substr($phone, -3);
                                                } else {
                                                    // For short numbers, mask all except last character
                                                    $maskedPhone =
                                                        str_repeat('*', max($len - 1, 0)) . substr($phone, -1);
                                                }
                                            }
                                        @endphp

                                        <div>Phone: {{ $maskedPhone }}</div>
                                        <div>Location:
                                            {{ $collection->shipmentCollection->sender_address }}
                                        </div>
                                        <div>Town: {{ $collection->shipmentCollection->sender_town }}
                                        </div>
                                        <hr style="margin: 4px 0;">

                                        <div style="font-weight: bold;">Receiver:</div>
                                        <div>Name:
                                            {{ $collection->shipmentCollection->receiver_name }}
                                        </div>
                                        @php
                                            $phone = $collection->shipmentCollection?->receiver_phone;

                                            if (empty($phone)) {
                                                $maskedPhone = 'N/A'; // or leave blank, or whatever you want
                                            } else {
                                                $len = strlen($phone);

                                                if ($len > 6) {
                                                    $maskedPhone =
                                                        substr($phone, 0, 3) .
                                                        str_repeat('*', $len - 6) .
                                                        substr($phone, -3);
                                                } else {
                                                    $maskedPhone =
                                                        str_repeat('*', max($len - 1, 0)) . substr($phone, -1);
                                                }
                                            }
                                        @endphp

                                        <div>Phone: {{ $maskedPhone }}</div>

                                        <div>Address:
                                            {{ $collection->shipmentCollection->receiver_address }}
                                        </div>
                                        <div>Town:
                                            {{ $collection->shipmentCollection->receiver_town }}
                                        </div>
                                        <hr style="margin: 4px 0;">

                                        <div style="font-weight: bold;">Parcel Details:</div>
                                        @if ($collection->shipmentCollection && $collection->shipmentCollection->items->count())
                                            @php
                                                $totalWeight = 0;
                                            @endphp
                                            <table style="width: 100%; border-collapse: collapse; margin-bottom: 4px;">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: left;">#</th>
                                                        <th style="text-align: left;">Desc.</th>
                                                        <th style="text-align: center;">Qty</th>
                                                        <th style="text-align: right;">Wt(kg)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($collection->shipmentCollection->items as $item)
                                                        @php
                                                            $totalWeight += $item->packages_no * $item->weight;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $loop->iteration }}.</td>
                                                            <td>{{ $item->item_name }}</td>
                                                            <td style="text-align: center;">
                                                                {{ $item->packages_no }}</td>
                                                            <td style="text-align: right;">
                                                                {{ number_format($item->weight, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <hr style="margin: 4px 0;">

                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Total Weight:</strong>
                                                <span>{{ number_format($totalWeight, 2) }}
                                                    {{ $totalWeight > 1 ? 'Kgs' : 'Kg' }}</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Base Cost:</strong>
                                                <span>Ksh
                                                    {{ number_format($collection->shipmentCollection->cost, 2) }}</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>VAT:</strong>
                                                <span style="text-decoration: underline;"> Ksh
                                                    {{ number_format($collection->shipmentCollection->vat, 2) }}</span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Total:</strong>
                                                <span> Ksh
                                                    {{ number_format($collection->shipmentCollection->total_cost, 2) }}</span>
                                            </div>
                                        @else
                                            <p>No shipment items found.</p>
                                        @endif

                                        <hr style="margin: 6px 0;">
                                        <div style="text-align: left; font-size: 12px;">
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Delivered By:</strong>
                                                <span> {{ $collection->user->name ?? '' }} </span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Contact:</strong>
                                                @php
                                                    $phone = $collection->user->phone_number ?? '';
                                                    if (!empty($phone) && strlen($phone) > 6) {
                                                        $maskedPhone =
                                                            substr($phone, 0, 3) .
                                                            str_repeat('*', strlen($phone) - 6) .
                                                            substr($phone, -3);
                                                    } else {
                                                        // If phone is too short, just show as is (or handle differently)
                                                        $maskedPhone = $phone;
                                                    }
                                                @endphp
                                                <span> {{ $maskedPhone }} </span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Vehicle REG:</strong>
                                                <span> {{ $collection->vehicle->regNo ?? '' }} </span>
                                            </div>
                                            <hr style="margin: 6px 0;">

                                            <div style="margin-top: 6px; font-size: 12px; line-height: 1.5;">
                                                <table style="width: 100%; border-collapse: collapse;">
                                                    <tr>
                                                        <td style="width: 50%; padding: 5px; vertical-align: top;">
                                                            <strong>Receiver Name:</strong>
                                                            _____________
                                                        </td>
                                                        <td style="width: 50%; padding: 5px; vertical-align: top;">
                                                            <strong>Receiver ID #:</strong>
                                                            _____________
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 50%; padding: 5px; vertical-align: top;">
                                                            <strong>Signature:</strong>
                                                            _____________
                                                        <td style=" width: 50%; padding: 5px; vertical-align: top;">
                                                            <strong>Stamp:</strong>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <br>
                                            <div class=" mt-0 pt-0 " style="text-align: center">
                                                <img loading="lazy" src="{{ asset('qrcodes') . '/' . $collection->requestId . '.svg' }}"
                                                    alt="Authorized QR Code"
                                                    style="width: 120px; height: auto; margin-top: 10px;">
                                            </div>
                                            <br>

                                            <hr style="margin: 6px 0;">
                                            <strong>TERMS & CONDITIONS</strong><br>
                                            Carriage of this shipment is subject to the terms and
                                            conditions. Visit www.ufanisicourier.co.ke/terms
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                        aria-label="Close">Close</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="printModalContent({{ $collection->id }}, 'grn')">Print
                                        GRN</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Handover Modal -->
                @if ($collection->status === 'collected')
                    <div class="modal fade" id="handoverModal-{{ $collection->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="handoverModalLabel-{{ $collection->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title" id="handoverModalLabel-{{ $collection->id }}">
                                        Handover Shipment #{{ $collection->requestId }}
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <form action="{{ route('shipments.handover', $collection->requestId) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="mb-3">Please select the rider you want to handover
                                            this shipment to:</p>

                                        <div class="form-group">
                                            <label for="rider_id">Select Rider</label>
                                            <select name="rider_id" id="rider_id" class="form-control" required>
                                                <option value="">-- Choose Rider --</option>
                                                @foreach ($riders as $rider)
                                                    <option value="{{ $rider->id }}">
                                                        {{ $rider->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea name="remarks" id="remarks" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-info">Confirm
                                            Handover</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- JavaScript to toggle and populate form -->
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    // const clientRadio = document.getElementById('clientRadio');
                    // const agentRadio = document.getElementById('agentRadio');
                    // const senderForm = document.getElementById('senderForm');

                    // const cid = document.getElementById('cid').value;

                    // const sender_name = document.getElementById('sender_name');
                    // const sender_id_no = document.getElementById('sender_id_no');
                    // const sender_contact = document.getElementById('sender_contact');
                    // const sender_town = document.getElementById('sender_town');
                    // const sender_address = document.getElementById('sender_address');
                    // const senderEmail = document.getElementById('senderEmail');

                    // clientRadio.addEventListener('change', () => {
                    //     if (clientRadio.checked) {
                    //         senderForm.style.display = 'block';
                    //         //console.log('cid:', cid);

                    //         fetch('/clientData/' + cid) // Adjust this URL as needed
                    //             .then(response => {
                    //                 if (!response.ok) {
                    //                     throw new Error('Network response was not ok');
                    //                 }
                    //                 return response.json();
                    //             })
                    //             .then(client => {
                    //                 if (client && !client.message) { // Ensure it's not a 404 error response
                    //                     sender_name.value = client.name || '';
                    //                     sender_id_no.value = client.contact_person_id_no || '';
                    //                     sender_contact.value = client.contact || '';
                    //                     sender_town.value = client.city || '';
                    //                     sender_address.value = client.address || '';
                    //                     senderEmail.value = client.email || '';
                    //                 } else {
                    //                     alert('Client not found.');
                    //                 }
                    //             })
                    //             .catch(error => {
                    //                 console.error('Error fetching client data:', error);
                    //                 alert('Failed to fetch client data.');
                    //             });
                    //     }
                    // });

                    document.querySelectorAll('.modal-body').forEach(modalBody => {
                        const clientRadios = modalBody.querySelectorAll('.clientRadio');
                        const agentRadios = modalBody.querySelectorAll('.agentRadio');
                        const senderForm = modalBody.querySelector('#senderForm');
                        const cidInput = modalBody.querySelector('#cid');

                        // Handle client radios
                        clientRadios.forEach(radio => {
                            radio.addEventListener('change', () => {
                                if (radio.checked) {
                                    senderForm.style.display = 'block';

                                    const cid = cidInput ? cidInput.value : null;
                                    if (!cid) return;

                                    fetch('/clientData/' + cid)
                                        .then(response => {
                                            if (!response.ok) throw new Error(
                                                'Network response was not ok');
                                            return response.json();
                                        })
                                        .then(client => {
                                            if (client && !client.message) {
                                                senderForm.querySelector('#sender_name').value =
                                                    client.name || '';
                                                senderForm.querySelector('#sender_id_no')
                                                    .value = client.contact_person_id_no || '';
                                                senderForm.querySelector('#sender_contact')
                                                    .value = client.contact || '';
                                                senderForm.querySelector('#sender_town').value =
                                                    client.city || '';
                                                senderForm.querySelector('#sender_address')
                                                    .value = client.address || '';
                                                senderForm.querySelector('#senderEmail').value =
                                                    client.email || '';
                                            } else {
                                                alert('Client not found.');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error fetching client data:', error);
                                            alert('Failed to fetch client data.');
                                        });
                                }
                            });
                        });

                        // Handle agent radios
                        agentRadios.forEach(radio => {
                            radio.addEventListener('change', () => {
                                if (radio.checked) {
                                    // Hide sender form for agent
                                    //if (senderForm) senderForm.style.display = 'none';

                                    // Optional: clear sender fields if previously filled
                                    senderForm.querySelector('#sender_name').value = '';
                                    senderForm.querySelector('#sender_id_no').value = '';
                                    senderForm.querySelector('#sender_contact').value = '';
                                    senderForm.querySelector('#sender_town').value = '';
                                    senderForm.querySelector('#sender_address').value = '';
                                    senderForm.querySelector('#senderEmail').value = '';
                                }
                            });
                        });
                    });

                    function clearForm() {
                        sender_name.value = '';
                        sender_id_no.value = '';
                        sender_contact.value = '';
                        sender_town.value = '';
                        sender_address.value = '';
                        senderEmail.value = '';
                    }

                    // agentRadio.addEventListener('change', () => {
                    //     if (agentRadio.checked) {
                    //         senderForm.style.display = 'block';
                    //         clearForm(); // Allow fresh entry
                    //     }
                    // });
                    // get destinations

                    $(document).on('change', '.origin-dropdown-special', function() {
                        const originSelect2 = $(this);
                        const selectedOfficeId2 = originSelect2.val();
                        const modal = originSelect2.closest('.modal');
                        const destinationSelect2 = modal.find('.destination-dropdown-special');
                        const cid = modal.find('#cid').val();
                        const serviceType = modal.find('#service_type').val();
                        const destinationIdSpecialInput = modal.find('#destination_id_special')
                            .val(); // Clear previous destination ID

                        $('#origin_id').val(selectedOfficeId2);
                        destinationSelect2.html('<option value="">Select Destination</option>');

                        if (selectedOfficeId2) {
                            $.get('/special_rates/get_destinations/' + selectedOfficeId2 + '/' + cid + '/' + destinationIdSpecialInput)
                                .done(function(data) {
                                    data.forEach(function(item) {
                                        destinationSelect2.append(
                                            `<option data-id="${item.id}" value="${item.destination}">${item.destination}</option>`
                                        );
                                    });
                                })
                                .fail(function() {
                                    console.error("Failed to load destinations");
                                });
                        }
                    });

                    function recalculateCosts() {
                        let totalWeight = 0;
                        let totalVolume = 0;

                        $('#shipmentTable tbody tr').each(function() {
                            const row = $(this);
                            const weight = parseFloat(row.find('input[name="weight[]"]').val()) || 0;
                            const packages = parseFloat(row.find('input[name="packages[]"]').val()) || 1;
                            const volume = parseFloat(row.find('.volume').val()) || 1;
                            totalWeight += weight * packages;
                            totalVolume += volume;
                        });

                        $('input[name="total_weight"]').val(totalWeight.toFixed(2));

                        const baseCost = parseFloat($('input[name="base_cost"]').val()) || 0;
                        let cost = baseCost;
                        volumeWeight = totalVolume / 5000;

                        let baseWeight = 0;


                        if (totalWeight > volumeWeight) {
                            baseWeight = totalWeight;
                            //alert('weight' + baseWeight)
                        }
                        if (volumeWeight > totalWeight) {
                            baseWeight = volumeWeight;
                            //alert('volume weight' + baseWeight)
                            $('input[name="total_weight"]').val(baseWeight.toFixed(2));
                        }

                        if (baseWeight > 25) {
                            const extraWeight = baseWeight - 25;
                            cost += extraWeight * 50;
                        }

                        function extractVAT(costWithVAT) {
                            // Calculate raw VAT when total already includes VAT
                            const rawVat = (costWithVAT * 0.16) / 1.16;

                            const integerPart = Math.floor(rawVat);
                            const decimalPart = rawVat - integerPart;
                            let roundedDecimal = 0;

                            // Apply the same custom rounding rules
                            if (decimalPart <= 0.03) {
                                roundedDecimal = 0.00;
                            } else if (decimalPart > 0.03 && decimalPart <= 0.07) {
                                roundedDecimal = 0.05;
                            } else {
                                roundedDecimal = 0.10;
                            }

                            let result = integerPart + roundedDecimal;

                            // Handle carry-over if rounding pushes to next integer
                            if (result >= integerPart + 1) {
                                result = integerPart + 1.00;
                            }

                            // Return always formatted to 2 decimals, e.g., "69.00" or "69.05"
                            return result.toFixed(2);
                        }


                        const vat = extractVAT(cost);
                        $('input[name="cost"]').val((cost - vat).toFixed(2));
                        $('input[name="vat"]').val(vat);
                        $('input[name="total_cost"]').val((cost).toFixed(2));
                    }

                    // Trigger when destination changes
                    $(document).on('change', '.destination-dropdown-special', function() {
                        const cid = $('#cid').val();
                        const destinationId2 = $(this).val();
                        const selectedOption2 = $(this).find('option:selected');
                        const destination_id2 = selectedOption2.data('id');
                        $("#destination_id_special").val(destination_id2);
                        const modal = $(this).closest('form'); // Adjust if you're using modal or form wrapper
                        const originId2 = modal.find('.origin-dropdown-special').val();
                        $('#destination_id').val(destination_id2);
                        if (originId2 && destinationId2) {
                            $.get(`/get_cost/${originId2}/${destinationId2}/${cid}`)
                                .done(function(data) {
                                    const baseCost = parseFloat(data.cost);
                                    $('input[name="base_cost"]').val(baseCost);
                                    // recalculateCosts();
                                })
                                .fail(function() {
                                    console.error("Failed to fetch base cost");
                                    $('input[name="base_cost"]').val(0);
                                });
                        }
                    });

                    //  same day
                    $(document).on('change', '.destination-dropdownxz', function() {

                        const destinationId2 = $(this).val();

                        // const selectedOption2 = $(this).find('option:selected');
                        // const destination_id2 = selectedOption2.data('id');
                        // $("#destination_id_special").val(destination_id2);
                        const modal = $(this).closest('form'); // Adjust if you're using modal or form wrapper
                        const originId2 = modal.find('.origin-dropdownxz').val();
                        //$('#destination_id').val(destination_id2);
                        if (originId2 && destinationId2) {
                            //alert('ok');

                            $.get(`/get_cost/${originId2}/${destinationId2}`)
                                .done(function(data) {
                                    const baseCost = parseFloat(data.cost);
                                    $('input[name="base_cost"]').val(baseCost);
                                    recalculateCosts();
                                })
                                .fail(function() {
                                    console.error("Failed to fetch base cost");
                                    $('input[name="base_cost"]').val(0);
                                });
                        }
                    });
                });
            </script>
        </div>
    </div>
@endsection
