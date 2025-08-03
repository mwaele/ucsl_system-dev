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
                                <td>{{ number_format($item->total_cost, 2) }}</td>
                                <td>{{ $item->payment_mode }}</td>
                                <td>{{ $item->status ?? 'Pending' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info verifyModal" data-toggle="modal"
                                        data-id="{{ $item->waybill_no }}" data-waybill-no="{{ $item->waybill_no }}"
                                        data-client="{{ $item->client_name }}" data-item-name="{{ $item->item_names }}"
                                        data-weight="{{ $item->total_weight }}" data-target="#VerifyModal">Verify</button>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Shipment Arrival Modal -->
                        <div class="modal fade" id="VerifyModal" tabindex="-1" aria-labelledby="VerifyModalLabel"
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
                        </div>

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
        });
    </script>



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
@endsection
