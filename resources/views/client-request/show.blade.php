@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-danger">UCSL Shipment Collections</h4>

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
                        $('#origin_id').val(selectedOfficeId2);
                        destinationSelect2.html('<option value="">Select Destination</option>');

                        if (selectedOfficeId2) {
                            $.get('/get_destinations/' + selectedOfficeId2 + '/' + cid)
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

                        $('#shipmentTable tbody tr').each(function() {
                            const row = $(this);
                            const weight = parseFloat(row.find('input[name="weight[]"]').val()) || 0;
                            const packages = parseFloat(row.find('input[name="packages[]"]').val()) || 1;
                            totalWeight += weight * packages;
                        });

                        $('input[name="total_weight"]').val(totalWeight.toFixed(2));

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

                    // Trigger when destination changes
                    $(document).on('change', '.destination-dropdown-special', function() {

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
                                    recalculateCosts();
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

        <div class="card-body">

            {{-- 🖥️ Desktop / Tablet View --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table table-bordered align-middle text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Req ID</th>
                            <th>Client Name</th>
                            <th>Service Level</th>
                            <th>Telephone Number</th>
                            <th>Pickup Location</th>
                            <th>Parcel Details</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collections as $collection)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $collection->requestId }}</td>
                                <td>{{ $collection->client->name ?? '' }}</td>
                                <td>{{ $collection->serviceLevel->sub_category_name }}</td>
                                <td>{{ $collection->client->contactPersonPhone }}</td>
                                <td>{{ $collection->collectionLocation }}</td>
                                <td>{{ $collection->parcelDetails }}</td>

                                <td>
                                    @php
                                        $statusColor = match ($collection->status) {
                                            'pending collection' => 'bg-secondary',
                                            'collected' => 'bg-warning',
                                            'verified' => 'bg-info',
                                            'delivered' => 'bg-success',
                                            'collection_failed' => 'bg-danger',
                                            'delivery_failed' => 'bg-danger',
                                            default => 'bg-dark',
                                        };
                                    @endphp

                                    <span class="badge p-2 fs-5 text-white {{ $statusColor }}">
                                        {{ \Illuminate\Support\Str::title($collection->status) }}
                                    </span>

                                    @if ($collection->priority_level == 'high' && $collection->status !== 'delivered')
                                        <span class="badge p-2 mt-2 bg-danger text-white">
                                            Deliver by
                                            {{ \Carbon\Carbon::parse($collection->deadline_date)->format('g:i A') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="d-flex flex-wrap gap-2">
                                    {{-- Pending Collection --}}
                                    @if ($collection->status === 'pending collection' || $collection->status === 'collection_failed')
                                        <button class="btn btn-warning btn-sm d-flex align-items-center gap-1 ml-1 mb-1 mr-1"
                                            data-toggle="modal" title="Collect parcels"
                                            data-target="#collect-{{ $collection->id }}">
                                            <i class="fas fa-box mr-1"></i> Collect
                                        </button>
                                        <button class="btn btn-danger btn-sm d-flex align-items-center gap-1 ml-1 mb-1 mr-1"
                                            title="Failed Collection" data-toggle="modal"
                                            data-target="#failedCollectionModal-{{ $collection->id }}">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> <span>Failed Collection</span>
                                        </button>
                                        <!-- Handover to rider Button -->
                                        <button class="btn btn-info btn-sm mr-1 ml-1 mb-1 d-flex align-items-center gap-1"
                                            title="Handover to Rider" data-toggle="modal"
                                            data-target="#handoverModal-{{ $collection->id }}">
                                            <i class="fas fa-exchange-alt mr-1"></i> <span> Handover</span>
                                        </button>
                                    @endif

                                    {{-- Collected --}}
                                    @if ($collection->status === 'collected')
                                        <!-- Consignment Button -->
                                        <button class="btn btn-primary btn-sm ml-1 mr-1 mb-1 d-flex align-items-center gap-1"
                                            title="Print Consignment Note" data-toggle="modal"
                                            data-target="#printModal-{{ $collection->id }}">
                                            <i class="fas fa-file-alt mr-1"></i> <span>Consignment</span>
                                        </button>



                                        <!-- Release Collection Button -->
                                        <!-- <button
                                            class="btn btn-warning btn-sm ml-1 mr-1 mb-1 d-flex align-items-center gap-1"
                                            data-toggle="modal" title="Release Collection"
                                            data-target="#releaseCollectionModal-{{ $collection->id }}">
                                            Release Collection <i class="fas fa-arrow-circle-right ml-1"></i>
                                        </button> -->

                                        <!-- <button class="btn btn-warning btn-sm mr-1 mb-1 d-flex align-items-center gap-1 text-white"
                                                                                                                title="Generate Waybill"
                                                                                                                data-toggle="modal" data-target="#waybillModal{{ $collection->requestId }}">
                                                                                                                <i class="fas fa-file-invoice"></i> <span>Waybill</span>
                                                                                                            </button> -->
                                    @endif

                                    {{-- Verified / Delivered --}}
                                    @if (in_array($collection->status, ['verified', 'delivered']))
                                        <button class="btn btn-primary btn-sm ml-1 mr-1 d-flex align-items-center gap-1"
                                            title="Print Receipt" data-toggle="modal"
                                            data-target="#printModal-{{ $collection->id }}">
                                            <i class="fas fa-print mr-1"></i> <span>Receipt</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Req ID</th>
                            <th>Client Name</th>
                            <th>Service Level</th>
                            <th>Telephone Number</th>
                            <th>Pickup Location</th>
                            <th>Parcel Details</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- 📱 Mobile View --}}
            <div class="d-md-none">
                @foreach ($collections as $collection)
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 text-primary fw-bold">{{ $loop->iteration }}. Req ID:
                                    {{ $collection->requestId }}</h6>
                                @php
                                    $statusColor = match ($collection->status) {
                                        'pending collection' => 'bg-secondary',
                                        'collected' => 'bg-warning',
                                        'verified' => 'bg-info',
                                        'delivered' => 'bg-success',
                                        'collection_failed' => 'bg-danger',
                                        'delivery_failed' => 'bg-danger',
                                        default => 'bg-dark',
                                    };
                                @endphp

                                <span class="badge p-2 fs-5 text-white {{ $statusColor }}">
                                    {{ \Illuminate\Support\Str::title($collection->status) }}
                                </span>
                            </div>

                            <p class="mb-1"><strong>Client:</strong> {{ $collection->client->name ?? '' }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $collection->client->contactPersonPhone }}</p>
                            <p class="mb-1"><strong>Pickup:</strong> {{ $collection->collectionLocation }}</p>
                            <p class="mb-1"><strong>Service:</strong> {{ $collection->serviceLevel->sub_category_name }}
                            </p>
                            <p class="mb-1"><strong>Parcel:</strong> {{ $collection->parcelDetails }}</p>

                            @if ($collection->priority_level == 'high' && $collection->status !== 'delivered')
                                <span class="badge bg-danger text-white d-inline-block mb-2">
                                    Deliver by {{ \Carbon\Carbon::parse($collection->deadline_date)->format('g:i A') }}
                                </span>
                            @endif

                            <div class="mt-3 d-flex flex-wrap gap-2">
                                {{-- Mobile Buttons (same logic, just smaller) --}}
                                @if ($collection->status === 'pending collection')
                                    <button
                                        class="btn btn-warning btn-sm d-flex mb-1 mr-1 align-items-center gap-1 w-50 justify-content-center"
                                        data-toggle="modal" title="Collect parcels"
                                        data-target="#collect-{{ $collection->id }}">
                                        <i class="fas fa-box mr-1"></i> <span>Collect</span>
                                    </button>
                                    <button
                                        class="btn btn-danger btn-sm d-flex mb-1 mr-1 ml-1 align-items-center gap-1 justify-content-center"
                                        data-toggle="modal" title="Failed Collection"
                                        data-target="#failedCollectionModal-{{ $collection->id }}">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> <span>Mark as Failed</span>
                                    </button>
                                @endif

                                @if ($collection->status === 'collected')
                                    <!-- Print Consignment Button -->
                                    <button
                                        class="btn btn-primary btn-sm mb-1 mr-1 d-flex align-items-center gap-1 w-100 justify-content-center"
                                        title="Consignment Note" data-toggle="modal"
                                        data-target="#printModal-{{ $collection->id }}">
                                        <i class="fas fa-file-alt mr-1"></i> <span>Consignment Note</span>
                                    </button>

                                    <!-- Handover Button -->
                                    <button
                                        class="btn btn-info btn-sm d-flex mb-1 mr-1 align-items-center gap-1 w-50 justify-content-center"
                                        title="Handover to Rider" data-toggle="modal"
                                        data-target="#handoverModal-{{ $collection->id }}">
                                        <i class="fas fa-exchange-alt mr-1"></i> <span>Handover</span>
                                    </button>

                                    <!-- Release Collection Button -->
                                    <button class="btn btn-sm btn-warning ml-1 mr-1 mb-1" title="Release Collection"
                                        data-toggle="modal" data-target="#releaseCollectionModal-{{ $collection->id }}">
                                        Release Collection <i class="fas fa-arrow-circle-right ml-1"></i>
                                    </button>

                                    <!-- <button class="btn btn-warning btn-sm text-white d-flex mb-1 align-items-center gap-1 w-100 justify-content-center"
                                                                                                            title="Waybill" data-toggle="modal"
                                                                                                            data-target="#waybillModal{{ $collection->requestId }}">
                                                                                                            <i class="fas fa-file-invoice"></i> <span>Waybill</span>
                                                                                                        </button> -->
                                @endif

                                @if (in_array($collection->status, ['verified', 'delivered']))
                                    <button
                                        class="btn btn-primary btn-sm d-flex mb-1 align-items-center gap-1 w-100 justify-content-center"
                                        title="Receipt" data-toggle="modal"
                                        data-target="#printModal-{{ $collection->id }}">
                                        <i class="fas fa-print mr-1"></i> <span>Receipt</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @foreach ($collections as $collection)
                <!-- MODALS -->
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
                                                                Number</label>
                                                            <input type="text" class="form-control"
                                                                name="sender_id_no" id="sender_id_no" maxlength="8">
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
                                                                <span class="text-danger">*</span></label>
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
                                                            name="receiverContactPerson" required>
                                                        <input type="hidden" name='client_id'
                                                            value="{{ $collection->client->id }}">
                                                        <input type="hidden" name="requestId"
                                                            value="{{ $collection->requestId }}">


                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="form-label text-primary text-primary">Receiver
                                                            Email
                                                        </label>
                                                        <input type="email" class="form-control" name="receiverEmail">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label text-primary text-primary">ID
                                                            Number
                                                        </label>
                                                        <input type="text" class="form-control" name="receiverIdNo"
                                                            maxlength="8">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label text-primary text-primary">Phone
                                                            Number
                                                        </label>
                                                        <input type="text" class="form-control" name="receiverPhone">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label text-primary text-primary">Address
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="receiverAddress"
                                                            required>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label text-primary text-primary">Town
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" name="receiverTown"
                                                            required>
                                                        <input type="hidden" value="{{ $consignment_no }}"
                                                            name="consignment_no">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($collection->client->special_rates_status)
                                        <!-- Origin & Destination -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label text-primary text-primary">Origin
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
                                                </select>
                                            </div>
                                            <input type="hidden" name='destination_id' id="destination_id_special">
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
                                        <table class="table table-bordered shipmentTable"
                                            id="shipmentTable">
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
                                                <tr>
                                                    <td><input type="text" class="form-control"
                                                            name="item_name[]"></td>
                                                    <td><input type="number" min="0"
                                                            class="form-control" name="packages[]">
                                                    </td>
                                                    <td><input type="number" min="0"
                                                            class="form-control" name="weight[]"></td>
                                                    <td><input type="number" min="0"
                                                            class="form-control" name="length[]"></td>
                                                    <td><input type="number" min="0"
                                                            class="form-control" name="width[]"></td>
                                                    <td><input type="number" min="0"
                                                            class="form-control" name="height[]"></td>
                                                    <td class="volume-display text-muted"><input
                                                            type="number" min="0"
                                                            class="form-control" name="volume[]"
                                                            readonly></td>
                                                    <td><button type="button"
                                                            class="btn btn-danger btn-sm remove-row"
                                                            title="Delete Row"><i
                                                                class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Add Row Button (works for both views) -->
                                    <button type="button" class="btn btn-primary mb-3 addRowBtn" id="addRowBtn">Add Row</button>

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
                                                    name="total_weight" required readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label text-primary text-primary">Cost
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" min="0" class="form-control"
                                                    value="{{ $collection->rate }}" name="cost" required readonly>
                                            </div>
                                            <input type="hidden" name="base_cost" value="">

                                            <div class="form-group col-md-3">
                                                <label class="form-label text-primary text-primary">Tax
                                                    (16%) <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" min="0" class="form-control" name="vat"
                                                    required readonly>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="form-label text-primary text-primary">Total
                                                    Cost
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" min="0" class="form-control"
                                                    name="total_cost" required readonly>
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

                @if ($collection->shipmentCollection)
                    <!-- Print Receipt Modal -->
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
                                <div class="modal-body" id="print-content-{{ $collection->id }}">
                                    <div id="receipt-{{ $collection->id }}"
                                        style="font-family: monospace; font-size: 13px; line-height: 1.2;">
                                        <div style="text-align: center;">
                                            <img src="{{ asset('images/UCSLogo1.png') }}" alt="Logo"
                                                style="height: 70px;">
                                        </div>

                                        <div style="text-align: center; font-size: 15px;"><strong>Parcel
                                                Consignment Note</strong></div>
                                        <hr style="margin: 4px 0;">

                                        <div style="font-size: 14px;"><strong>Request ID:
                                                {{ $collection->requestId ?? 'N/A' }}</strong></div>
                                        <div style="font-size: 14px;"><strong>Consignment Number:
                                                {{ $collection->consignment_no ?? 'N/A' }}</strong>
                                        </div>
                                        <div>
                                            <strong>From:</strong>
                                            {{ $collection->shipmentCollection->office->name }}
                                        </div>
                                        <div>
                                            <strong>To:</strong>
                                            {{ $collection->shipmentCollection->destination->destination ?? '' }}
                                        </div>
                                        <div><strong>Total Items:</strong>
                                            {{ $collection->shipmentCollection->items->count() }}</div>
                                        <div>
                                            <strong>Date:</strong> {{ now()->format('M d, Y \a\t h:i A') }}
                                        </div>

                                        <hr style="margin: 4px 0;">

                                        <div style="font-weight: bold;">Sender:</div>
                                        <div>Name: {{ $collection->client->name }}</div>
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
                                                        str_repeat('*', max($len - 1, 0)) .
                                                        substr($phone, -1);
                                                }
                                            }
                                        @endphp

                                        <div>Phone: {{ $maskedPhone }}</div>
                                        <div>Location: {{ $collection->client->building }}</div>
                                        <div>Town: {{ $collection->client->city }}</div>
                                        <hr style="margin: 4px 0;">

                                        <div style="font-weight: bold;">Receiver:</div>
                                        <div>Name: {{ $collection->shipmentCollection->receiver_name }}
                                        </div>
                                        @php
                                            $phone = $collection->shipmentCollection?->receiver_phone;

                                            if (empty($phone)) {
                                                $maskedPhone = 'N/A';   // or leave blank, or whatever you want
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
                                                *** High Priority Parcel***
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
                                                <span> {{ $collection->user->name }} </span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Contact:</strong>
                                                @php
                                                    $phone = $collection->user->phone_number;
                                                    $maskedPhone =
                                                        substr($phone, 0, 3) .
                                                        str_repeat('*', strlen($phone) - 6) .
                                                        substr($phone, -3);
                                                @endphp
                                                <span> {{ $maskedPhone }} </span>
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <strong>Vehicle REG:</strong>
                                                <span> {{ $collection->vehicle->regNo ?? '' }} </span>
                                            </div>

                                            These are provisional charges based on details provided by
                                            sender.<br>
                                            <div class=" mt-0 pt-0 " style="text-align: center">
                                                <img src="{{ asset('qrcodes') . '/' . $collection->requestId . '.svg' }}"
                                                    alt="Authorized QR Code"
                                                    style="width: 120px; height: auto; margin-top: 10px;">
                                            </div>
                                            <br>
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
                                        onclick="printModalContent({{ $collection->id }})">Print</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Handover Modal -->
                <div class="modal fade" id="handoverModal-{{ $collection->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="handoverModalLabel-{{ $collection->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title" id="handoverModalLabel-{{ $collection->id }}">
                                    Handover Shipment #{{ $collection->requestId }}
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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

                <!-- Waybill Modal -->
                <div class="modal fade" id="waybillModal{{ $collection->requestId }}" tabindex="-1" role="dialog"
                    aria-labelledby="waybillLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document" style="max-width: 850px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-primary">Waybill Preview</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="max-height: 80vh; overflow-y: auto; background: #f9f9f9;">
                                <iframe src="{{ route('waybill.preview', $collection->requestId) }}" width="100%"
                                    height="500" frameborder="0"></iframe>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <a href="{{ route('waybill.generate', $collection->requestId) }}" target="_blank"
                                    class="btn btn-primary">
                                    Generate
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($collection->status === 'pending collection' || $collection->status === 'collection_failed')
                    <!-- Failed Collection Modal -->
                    <div class="modal fade" id="failedCollectionModal-{{ $collection->id }}" tabindex="-1"
                        role="dialog" aria-labelledby="failedCollectionModalLabel-{{ $collection->id }}"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="failedCollectionModalLabel-{{ $collection->id }}">
                                        Failed Collection for RequestId
                                        #{{ $collection->requestId }}
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <form id="failedCollectionForm-{{ $collection->id }}"
                                    action="{{ route('shipments.failedCollections', $collection->requestId) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="mb-3">Please select the reasons for failing to
                                            collect:</p>

                                        <div class="form-group">
                                            <label for="failedCollection-{{ $collection->id }}">Select Reason</label>
                                            <select name="reason" id="failedCollection-{{ $collection->id }}"
                                                class="form-control" required>
                                                <option value="">-- Choose Reason --</option>
                                                @foreach ($failedCollections as $reason)
                                                    <option value="{{ $reason->id }}">
                                                        {{ $reason->reason }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="remarks-{{ $collection->id }}">Remarks</label>
                                            <textarea name="remarks" id="remarks-{{ $collection->id }}" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-info">Submit</button>
                                    </div>
                                </form>

                                <!-- Success/Error alert -->
                                <div id="failedCollectionAlert-{{ $collection->id }}" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Release Collection Modal -->
                <div class="modal fade" id="releaseCollectionModal-{{ $collection->id }}" tabindex="-1"
                    role="dialog" aria-labelledby="releaseCollectionLabel" aria-hidden="true">
                    <div class="modal-dialog " role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-white">Release Collection to Front
                                    Office - RequestId {{ $collection->requestId }} </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('collection.release', $collection->requestId) }}" method="POST">
                                <div class="modal-body">
                                    @csrf

                                    <p>Are you sure you want to release this collection to the front
                                        office?</p>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">
                                        Yes Release
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- JavaScript to handle AJAX form submission for Failed Collection -->
            <script>
                $(document).ready(function() {
                    $(document).off('submit', 'form[id^="failedCollectionForm-"]').on('submit',
                        'form[id^="failedCollectionForm-"]',
                        function(e) {
                            e.preventDefault();

                            const form = $(this);
                            const actionUrl = form.attr('action');
                            const formData = form.serialize();
                            const formId = form.attr('id').split('-')[1]; // get collection ID suffix
                            const alertBox = $('#failedCollectionAlert-' + formId);

                            $.ajax({
                                url: actionUrl,
                                type: 'POST',
                                data: formData,
                                beforeSend: function() {
                                    alertBox.html(
                                        '<div class="alert alert-info">Submitting, please wait...</div>'
                                    );
                                },
                                success: function(response) {
                                    if (response.status === 'success') {
                                        alertBox.html('<div class="alert alert-success">' + response
                                            .message + '</div>');
                                        form.find('button[type="submit"]').prop('disabled', true);
                                        setTimeout(() => {
                                            $('#failedCollectionModal-' + formId).modal('hide');
                                            location.reload();
                                        }, 1500);
                                    } else if (response.status === 'warning') {
                                        alertBox.html('<div class="alert alert-warning">' + response
                                            .message + '</div>');
                                    }
                                },
                                error: function(xhr) {
                                    let message = 'An unexpected error occurred.';
                                    if (xhr.status === 422) {
                                        message = 'Please fill in all required fields.';
                                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                        message = xhr.responseJSON.message;
                                    }
                                    alertBox.html('<div class="alert alert-danger">' + message + '</div>');
                                }
                            });
                        });
                });
            </script>

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
                        $('#origin_id').val(selectedOfficeId2);
                        destinationSelect2.html('<option value="">Select Destination</option>');

                        if (selectedOfficeId2) {
                            $.get('/get_destinations/' + selectedOfficeId2 + '/' + cid)
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

                        $('#shipmentTable tbody tr').each(function() {
                            const row = $(this);
                            const weight = parseFloat(row.find('input[name="weight[]"]').val()) || 0;
                            const packages = parseFloat(row.find('input[name="packages[]"]').val()) || 1;
                            totalWeight += weight * packages;
                        });

                        $('input[name="total_weight"]').val(totalWeight.toFixed(2));

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

                    // Trigger when destination changes
                    $(document).on('change', '.destination-dropdown-special', function() {

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
                                    recalculateCosts();
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
