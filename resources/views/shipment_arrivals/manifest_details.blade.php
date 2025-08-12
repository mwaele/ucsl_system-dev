@extends('layouts.custom')

@section('content')
    <style>
        tr,
        th,
        td,
        {
        color: #14489f !important
        }

        .p {
            text-align: right;
            padding: 0;
            margin: 0;
        }
    </style>
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Parcel Receipts Details at <strong>
                        {{ Auth::user()->office->name }} </strong>
                    Dispatch Date:<strong> {{ $loading_sheet->dispatch_date ?? 'Pending Dispatch' }} </strong> Destination:
                    <strong> {{ $destination->destination ?? '' }} </strong>
                </h4>


                <a href="/shipment_arrivals_report_detail" id="generateReportBtn"
                    class="btn btn-warning btn-sm ml-md-2 mb-2 mb-md-0 shadow-sm">
                    <i class="fas fa-download fa-sm text-white"></i> Generate Report Based on Filtering
                </a>

                <a href="/shipment_arrivals_report_detail" id="generateReportBtn"
                    class="btn btn-primary btn-sm ml-md-2 mb-2 mb-md-0 shadow-sm">
                    <i class="fas fa-download fa-sm text-white"></i> Generate Storage Charges Report
                </a>

                <a href="/shipment_arrivals_report_uncollected/{{ $id }}/Collected" id="generateReportBtn"
                    class="btn btn-danger btn-sm ml-md-2 mb-2 mb-md-0 shadow-sm">
                    <i class="fas fa-download fa-sm text-white"></i> Generate Collected Parcels Report
                </a>


                <a href="/shipment_arrivals_report_uncollected/{{ $id }}/Uncollected" id="generateReportBtn"
                    class="btn btn-info btn-sm ml-md-2 mb-2 mb-md-0 shadow-sm">
                    <i class="fas fa-download fa-sm text-white"></i> Generate Uncollected Parcels Report
                </a>

                <a href="/shipment_arrivals_report_detail" id="generateReportBtn"
                    class="btn btn-primary btn-sm ml-md-2 mb-2 mb-md-0 shadow-sm">
                    <i class="fas fa-download fa-sm text-white"></i> Generate General Report
                </a>


            </div>
        </div>
        <div class="card-body">


            <div class=" mb-3 shadow-sm  bg-warning pt-2">
                <div class="form-row p-2">
                    <div class="col-md-4">
                        <!-- Type Filter -->
                        <select id="typeFilter" class="form-control ml-2 mb-2">
                            <option value="">Filter By Payment Mode </option>
                            <option value="M-Pesa">M-Pesa</option>
                            <option value="Cash">Cash</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Invoice">Invoice</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="statusFilter" class="form-control ml-2 mb-2">
                            <option value="">Filter By Status </option>
                            <option value="Pending">Pending</option>
                            <option value="Collected">Collected</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-white mt-1 pr-2 text-right">
                            <strong>Manifest Batch No. {{ str_pad($loading_sheet->batch_no, 4, '0', STR_PAD_LEFT) }}
                            </strong>
                        </h4>
                    </div>
                </div>






            </div>


            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="myTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>WAYBILL NO.</th>
                            <th>CLIENT</th>
                            <th>DESCRIPTION</th>
                            <th>DESTINATION</th>
                            <th>QTY</th>
                            <th>WEIGHT</th>
                            <th>AMOUNT</th>
                            <th>ACCOUNT</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>WAYBILL NO.</th>
                            <th>CLIENT</th>
                            <th>DESCRIPTION</th>
                            <th>DESTINATION</th>
                            <th>QTY</th>
                            <th>WEIGHT</th>
                            <th>AMOUNT</th>
                            <th>ACCOUNT</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>

                                <td>{{ $item->waybill_no }}</td>
                                <td>{{ $item->client_name }}</td>
                                <td>{{ $item->item_names }}</td>
                                <td>{{ $item->destination ?? '' }}</td>
                                <td>{{ $item->total_quantity }}</td>
                                <td>{{ $item->total_weight }}</td>
                                <td>{{ number_format($item->actual_total_cost, 2) }}</td>
                                <td>{{ $item->payment_mode }}</td>
                                <td>{{ $item->status ?? 'Pending' }}</td>
                                <td>
                                    @if ($item->status != 'arrived')
                                        <button class="btn btn-sm btn-info  verify-btn" data-id="{{ $item->id }}"
                                            data-waybill-no="{{ $item->waybill_no }}"
                                            data-client="{{ $item->client_name }}"
                                            data-item-name="{{ $item->item_names }}"
                                            data-weight="{{ $item->total_weight }}"
                                            data-request-id="{{ $item->requestId }}
                                        "
                                            data-vehicle="{{ $item->vehicle_reg_no ?? '' }}"data-rider="{{ $item->transported_by ?? '' }}"
                                            data-date-requested="{{ $item->dispatch_date }}"
                                            data-cost="{{ $item->actual_cost }}"
                                            data-total-cost="{{ $item->actual_total_cost }}"
                                            data-vat="{{ $item->actual_vat }}" data-base-cost="1000">Verify</button>
                                    @endif
                                    @if ($item->status == 'arrived')
                                        <span class="text-success">Verified</span>
                                    @endif
                                    {{-- <button class="btn btn-info btn-sm verify-btn mr-1"
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
                                    </button> --}}
                                </td>
                            </tr>
                        @endforeach

                        <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-success">
                                        <h4 class="modal-title text-white">Parcel Receipts Details at <strong>
                                                {{ Auth::user()->office->name }} </strong>
                                            Dispatch Date:<strong>
                                                {{ $loading_sheet->dispatch_date ?? 'Pending Dispatch' }} </strong>
                                            Destination:
                                            <strong> {{ $destination->destination ?? '' }} </strong>
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

                        <!-- Shipment Arrival Modal -->
                        {{-- <div class="modal fade" id="VerifyModal" tabindex="-1" aria-labelledby="VerifyModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h4 class="modal-title text-white" id="VerifyModalLabel">Parcel Verification at
                                            Destination Office ({{ Auth::user()->office->name }})</h4>
                                        <button type="button" class="btn btn-danger btn-close" data-dismiss="modal"
                                            aria-label="Close">X</button>
                                    </div>

                                    <form action="/allocate-vehicle" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label class="form-label text-dark">Waybill No.<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="waybill_no"
                                                        id="waybill_no" required>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="form-label text-dark">Item Name(s) <span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="item_name"
                                                        id="item_name" required>
                                                </div>
                                            </div>
                                            <input type="hidden" name="delivery_id" id="delivery_id">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label class="form-label text-dark">Client Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="client_name"
                                                        id="client">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="form-label text-dark">Quantity <span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control" name="quantity"
                                                        id="quantity">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <label class="form-label text-dark">Remarks<span
                                                            class="text-danger">*</span></label>
                                                    <textarea class="form-control" name="remarks" id="remarks"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer d-flex justify-content-between">
                                            <button type="submit" class="btn btn-success">Verify</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel
                                                X</button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> --}}
                        <!-- Spinner Overlay -->

                        <script>
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

                                        // ✅ Start form
                                        let formHtml = `
                <form id="shipmentReceiptForm">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="text-primary">Request ID</label>
                            <input type="text" name="requestId" class="form-control" value="${request_id}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="text-primary">Transporter</label>
                            <input type="text" name="userId" class="form-control" value="${rider}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="text-primary">Vehicle Reg No.</label>
                            <input type="text" class="form-control" name="vehicleDisplay" value="${vehicle_reg_no}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="text-primary">Date Dispatched</label>
                            <input type="datetime-local" name="dateRequested" class="form-control" value="${date_requested}" readonly>
                        </div>
                    </div>
            `;

                                        // ✅ Items table
                                        formHtml += `
                <div class="table-responsive">
                    <table class="table table-bordered" id="shipmentTable">
                        <thead>
                            <tr>
    <th class="text-primary">Item No.</th>
    <th class="text-primary">Item Name</th>
    <th class="text-primary">Package No</th>
    <th class="text-primary">Weight (Kg)</th>
    <th class="text-primary">Length (cm)</th>
    <th class="text-primary">Width (cm)</th>
    <th class="text-primary">Height (cm)</th>
    <th class="text-primary text-center">Vol. Wt. (Kgs)</th>
    <th class="text-primary">Tally Status</th> <!-- ✅ New column -->
    <th class="text-primary">Remarks</th>
</tr>

                        </thead>
                        <tbody>
            `;

                                        response.items.forEach((item, index) => {
                                            const volume = (item.length || 0) * (item.width || 0) * (item.height ||
                                                0);
                                            formHtml += `
                    <tr>
    <td>${index + 1}
        <input type="hidden" name="items[${index}][id]" value="${item.id}">
    </td>
    <td><input type="text" name="items[${index}][item_name]" class="form-control" value="${item.item_name}" required></td>

    <!-- Store original quantity for comparison -->
    <td>
        <input type="number" name="items[${index}][packages]" 
               class="form-control packages-input" 
               value="${item.packages_no}" 
               data-original="${item.packages_no}" required>
    </td>

    <!-- Store original weight for comparison -->
    <td>
        <input type="number" step="0.01" name="items[${index}][weight]" 
               class="form-control weight-input" 
               value="${item.weight}" 
               data-original="${item.weight}" required>
    </td>

    <td><input type="number" name="items[${index}][length]" class="form-control" value="${item.length}"></td>
    <td><input type="number" name="items[${index}][width]" class="form-control" value="${item.width}"></td>
    <td><input type="number" name="items[${index}][height]" class="form-control" value="${item.height}"></td>
    <td>${volume}<input type="hidden" name="items[${index}][volume]" value="${volume}"></td>

    <!-- Tally Status radios -->
    <td>
        <div class="form-check form-check-inline">
            <input class="form-check-input tally-radio" type="radio" 
                   name="items[${index}][tally_status]" value="tallied" 
                   ${item.tally_status === 'tallied' ? 'checked' : ''} required>
            <label class="form-check-label">Tallied</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input tally-radio" type="radio" 
                   name="items[${index}][tally_status]" value="not_tallied" 
                   ${item.tally_status === 'not_tallied' ? 'checked' : ''}>
            <label class="form-check-label">Not Tallied</label>
        </div>
    </td>

    <td><input type="text" name="items[${index}][remarks]" class="form-control" value="${item.remarks ?? ''}"></td>
</tr>

                `;
                                        });

                                        formHtml += `
                        </tbody>
                    </table>
                </div>
            `;

                                        // ✅ Cost & Payment details
                                        formHtml += `
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label class="text-primary"><small>Cost *</small></label>
                        <input type="number" class="form-control" name="cost" value="${cost}" readonly>
                    </div>

                    <input type="hidden" name="base_cost" value="${base_cost}">

                    <div class="form-group col-md-2">
                        <label class="text-primary"><small>Tax (16%)*</small></label>
                        <input type="number" class="form-control" name="vat" value="${vat}" readonly>
                    </div>

                    <div class="form-group col-md-2">
                        <label class="text-primary"><small>Total Cost*</small></label>
                        <input type="number" class="form-control" name="total_cost" value="${total_cost}" readonly>
                    </div>

                    <div class="form-group col-md-2">
                        <label class="text-primary"><small>Cost Difference</small></label>
                        <input type="text" name="cost_diff" class="form-control" >
                    </div>
                </div>
            `;

                                        // ✅ Footer & close form
                                        formHtml += `
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel X</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Submit Verification</button>
                </div>
                </form> <!-- ✅ Closing the form -->
            `;

                                        // Inject full form into modal body
                                        $('#modalItemsBody').html(formHtml);
                                        $('#itemsModal').modal('show');
                                    },
                                    error: function() {
                                        $('#modalItemsBody').html('<p>Error loading items.</p>');
                                        $('#itemsModal').modal('show');
                                    }
                                });
                            });
                        </script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {

                                const verifyModal = document.getElementById('VerifyModal');
                                . // Event delegation to handle dynamically loaded buttons
                                // document.addEventListener('click', function(event) {
                                //     if (event.target.classList.contains('verifyModal')) {

                                //         const button = event.relatedTarget;

                                //         const shipmentId = button.getAttribute('data-id');

                                //         console.log('shipment id', shipmentId);
                                //         const waybillNo = button.getAttribute('data-waybill-no');
                                //         const clientName = button.getAttribute('data-client');
                                //         const itemName = button.getAttribute('data-item-name');
                                //         const weight = button.getAttribute('data-weight');

                                //         console.log('waybillno: ' + waybillNo);

                                //         // Set the form fields using Vanilla JS or jQuery
                                //         document.getElementById('delivery_id').value = shipmentId;
                                //         document.getElementById('waybill_no').value = waybillNo;
                                //         document.getElementById('item_name').value = itemName;
                                //         document.getElementById('quantity').value = weight;

                                //         // jQuery example (if needed)
                                //         $('#client').val(clientName);
                                //     }
                                // });
                            });
                        </script>


                    </tbody>
                </table>
                <hr>
                <h2 class="text-primary text-right pt-2 pb-2"><strong>
                        <tr>
                            TOTAL: {{ number_format($totals->total_cost_sum, 2) }}</td>
                    </strong></h2>
                <hr>
            </div>
            <h4 class="text-success"><strong>DISPATCHER</strong></h4>
            <div class="row mt-2 p-2 shadow-sm mb-3">

                <div class="col-md-6">
                    <p>NAME: {{ $loading_sheet->dispatcher->name }}</p>
                </div>
                <div class="col-md-6">
                    <p>ID NUMBER: {{ $loading_sheet->dispatcher->id_no }}</p>
                </div>
                <div class="col-md-6">
                    <p>PHONE NUMBER: {{ $loading_sheet->dispatcher->phone_no }}</p>
                </div>
                <div class="col-md-6">
                    <p>SIGNATURE: <img class="img-fluid" style="height: 20px"
                            src="{{ asset('storage/' . $loading_sheet->dispatcher->signature) }}"
                            alt="Dispatcher Signature"></p>


                </div>
            </div>

            <h4 class="text-success"><strong>TRANSPORTER</strong></h4>
            <div class="row mt-2 p-2 shadow-sm mb-3">

                <div class="col-md-6">
                    <p>NAME: {{ $loading_sheet->transporter->name }}</p>
                </div>

                <div class="col-md-6">
                    <p>PHONE NUMBER: {{ $loading_sheet->transporter->phone_no }}</p>
                </div>
                <div class="col-md-6">
                    <p>REG. DETAILS: {{ $loading_sheet->transporter->reg_details }}</p>
                </div>
                <div class="col-md-6">
                    <p>SIGNATURE: <img class="img-fluid" style="height: 20px"
                            src="{{ asset('storage/' . $loading_sheet->transporter->signature) ?? '' }}"></p>
                    </p>


                </div>
            </div>
            <h4 class="text-success"><strong>Destination Front Office Receiver</strong></h4>
            <div class="row mt-2 p-2 shadow-sm mb-3">

                <div class="col-md-6">
                    <p>NAME:</p>
                </div>
                <div class="col-md-6">
                    <p>ID NUMBER:</p>
                </div>
                <div class="col-md-6">
                    <p>PHONE NUMBER:</p>
                </div>
                <div class="col-md-6">
                    <P>DATE:</P>
                </div>
                <div class="col-md-6">
                    <P>SIGNATURE:</P>
                </div>
            </div>
            @if ($loading_sheet->dispatch_date == '')
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <button class="btn btn-primary btn-lg px-5 py-3" id="dispatch_loading_sheet">
                            DISPATCH NOW
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- <!-- Spinner Overlay -->
    <div id="spinnerOverlay"
        style="
        display:none;
        position:absolute;
        top:0; left:0;
        width:100%; height:100%;
        background:rgba(255,255,255,0.7);
        z-index:9999;
        display:flex;
        align-items:center;
        justify-content:center;
      ">
        <img src="{{ asset('images/spinner.gif') }}" alt="Saving..." />
    </div> --}}
    <script>
        $(document).ready(function() {
            $(document).on('click', '.verifyModal', function() {
                const button = $(this); // jQuery object

                // Get data attributes
                const waybillNo = button.data('waybill-no');
                const deliveryId = button.data('id');
                const client = button.data('client');
                const itemName = button.data('item-name');
                const weight = button.data('weight');

                // Set values into modal inputs
                $('#waybill_no').val(waybillNo);
                $('#delivery_id').val(deliveryId);
                $('#client').val(client);
                $('#item_name').val(itemName);
                $('#quantity').val(weight); // assuming weight = quantity
            });
            let table = $('#myTable').DataTable({
                responsive: true,
                pageLength: 10,
            });

            function filterTable() {
                const type = $('#typeFilter').val();
                const status = $('#statusFilter').val();

                table.rows().every(function() {
                    const data = this.data();
                    const paymentType = data[7]; // Adjust index if needed
                    const shipmentStatus = data[8]; // Adjust this index as needed

                    const typeMatches = !type || paymentType === type;
                    const statusMatches = !status || shipmentStatus === status;

                    const show = typeMatches && statusMatches;
                    $(this.node()).toggle(show);
                });
            }

            function updateReportLink() {
                const typeValue = $('#typeFilter').val() || '';
                const statusValue = $('#statusFilter').val() || '';

                const pathSegments = window.location.pathname.split('/');
                const sheetId = pathSegments[pathSegments.length - 1];

                const url =
                    `/shipment_arrivals_report_detail?filter=type&value=${encodeURIComponent(typeValue)}&status=${encodeURIComponent(statusValue)}&sheet_id=${sheetId}`;
                $('#generateReportBtn').attr('href', url);
            }

            $('#typeFilter, #statusFilter').on('change', function() {
                filterTable();
                updateReportLink();
            });

            // Initial load
            filterTable();
            updateReportLink();

            // function checkMismatch() {
            //     let hasMismatch = false;

            //     $("tr").each(function() {
            //         const packagesInput = $(this).find(".packages-input");
            //         const weightInput = $(this).find(".weight-input");

            //         const originalPackages = parseFloat(packagesInput.data("original")) || 0;
            //         const enteredPackages = parseFloat(packagesInput.val()) || 0;

            //         const originalWeight = parseFloat(weightInput.data("original")) || 0;
            //         const enteredWeight = parseFloat(weightInput.val()) || 0;

            //         // Check mismatch
            //         if (originalPackages !== enteredPackages || originalWeight !== enteredWeight) {
            //             $(this).find('input[value="not_tallied"]').prop("checked", true);
            //             hasMismatch = true;
            //         } else {
            //             $(this).find('input[value="tallied"]').prop("checked", true);
            //         }
            //     });

            //     // Disable or enable submit
            //     $("#submitBtn").prop("disabled", hasMismatch);
            // }

            // // Run check on page load
            // checkMismatch();

            // // Recheck whenever quantity or weight changes
            // $(document).on("input", ".packages-input, .weight-input", function() {
            //     checkMismatch();
            // });


            // testing 
            function checkMismatch() {
                let hasMismatch = false;

                $("tr").each(function() {
                    const packagesInput = $(this).find(".packages-input");
                    const weightInput = $(this).find(".weight-input");

                    const originalPackages = parseFloat(packagesInput.data("original")) || 0;
                    const enteredPackages = parseFloat(packagesInput.val()) || 0;

                    const originalWeight = parseFloat(weightInput.data("original")) || 0;
                    const enteredWeight = parseFloat(weightInput.val()) || 0;

                    // Check mismatch
                    if (originalPackages !== enteredPackages || originalWeight !== enteredWeight) {
                        $(this).find('input[value="not_tallied"]').prop("checked", true);
                        hasMismatch = true;
                    } else {
                        $(this).find('input[value="tallied"]').prop("checked", true);
                    }
                });

                // Disable or enable submit
                $("#submitBtn").prop("disabled", hasMismatch);
            }

            // Run check on page load
            checkMismatch();

            // Recheck whenever quantity or weight changes
            $(document).on("input", ".packages-input, .weight-input", function() {
                checkMismatch();
            });

        });
    </script>

    {{-- <script>
        $(document).on('input', '.packages-input, .weight-input', function() {
            let $input = $(this);
            let originalValue = parseFloat($input.data('original'));
            let currentValue = parseFloat($input.val());

            // Find the radios for this row
            let $row = $input.closest('tr');
            let $talliedRadio = $row.find('input[value="tallied"]');
            let $notTalliedRadio = $row.find('input[value="not_tallied"]');

            if (!isNaN(currentValue) && currentValue !== originalValue) {
                // Auto-select Not Tallied
                $notTalliedRadio.prop('checked', true);
            } else {
                // Auto-select Tallied (only if weight & qty match original)
                let weightMatch = parseFloat($row.find('.weight-input').val()) === parseFloat($row.find(
                    '.weight-input').data('original'));
                let qtyMatch = parseFloat($row.find('.packages-input').val()) === parseFloat($row.find(
                    '.packages-input').data('original'));

                if (weightMatch && qtyMatch) {
                    $talliedRadio.prop('checked', true);
                }
            }
        });
    </script> --}}



    <script>
        document.getElementById('dispatch_loading_sheet').addEventListener('click', function() {
            const urlSegments = window.location.pathname.split('/');
            const loadingSheetId = urlSegments[urlSegments.length - 1]; // gets the last segment    


            fetch(`/loading-sheets/${loadingSheetId}/dispatch`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok.');
                    return response.json();
                })
                .then(data => {
                    alert('Dispatch updated successfully!');
                    window.location.href = response.redirect;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to dispatch.');
                });
        });
    </script>
    <script>
        // Handle form submission
        $(document).on('submit', '#shipmentReceiptForm', function(e) {
            e.preventDefault();

            $('#submitBtn').prop('disabled', true);
            $('#spinnerOverlay').show();
            $('#itemsModal').modal('hide');
            const form = $(this);
            const shipmentId = $('.verify-btn').data('id');
            const formData = form.serialize();

            $.ajax({
                url: `/shipment_arrival/${shipmentId}`,
                type: 'POST', // must match your Laravel route
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function(response) {
                    $('#itemsModal').modal('hide');
                    // Optionally reload table or update without full reload
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error verifying shipment');
                    console.error(xhr.responseText);
                },
                complete: function() {
                    $("#loadingSpinner").hide(); // Hide spinner when done
                }
            });
        });
    </script>
@endsection
