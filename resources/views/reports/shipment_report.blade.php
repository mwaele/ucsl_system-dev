@extends('layouts.custom')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Shipments Reports</h5>
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex align-items-center ms-auto">
            <!-- Status Type Filter -->
            <div class="form-group mx-3">
                <label for="status" class="form-label text-primary mb-0"><strong>Status:</strong></label>
                <select id="status" class="form-control">
                    <option value="">All</option>
                    <option value="pending collection">Pending collection</option>
                    <option value="collected">Collected by rider</option>
                    <option value="received_at_front_office">Received at front office from Rider</option>
                    <option value="collection_failed">Failed rider collection</option>
                    <option value="verified">Verified by front office</option>
                    <option value="dispatched_from_front_office">Dispatched from front office</option>
                    <option value="received_at_destination">Received at destination</option>
                    <option value="verified_at_destination">Verified at destination</option>
                    <option value="collected_by_recipient">Collected by recipient</option>
                    <option value="delivered">Delivered to recipient</option>
                    <option value="on_hold">Disputed</option>
                </select>
            </div>

            <!-- Payment Type Filter -->
            <div class="form-group mx-3">
                <label for="paymentType" class="form-label text-primary mb-0"><strong>Payment Type:</strong></label>
                <select id="paymentType" class="form-control">
                    <option value="">All</option>
                    <option value="M-Pesa">M-Pesa</option>
                    <option value="Cash">Cash</option>
                    <option value="COD">COD</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Invoice">Invoice</option>
                </select>
            </div>
            
            <!-- Client Type Filter -->
            <div class="form-group mx-3">
                <label for="clientType" class="form-label text-primary  mb-0"><strong>Client Type:</strong></label>
                <select id="clientType" class="form-control">
                    <option value="">All</option>
                    <option value="walkin">Walk-in</option>
                    <option value="on_account">On Account</option>
                </select>
            </div>

            <!-- Service Level Filter -->
            <div class="form-group mx-3">
                <label for="serviceLevel" class="form-label text-primary mb-0"><strong>Service Level:</strong></label>
                <select id="serviceLevel" class="form-control">
                    <option value="">All</option>
                    <option value="Same Day">Same Day</option>
                    <option value="Overnight">Overnight</option>
                </select>
            </div>

            <!-- Date Range Filter -->
            <div class="form-group mx-3">
                <label for="startDate" class="form-label text-primary mb-1"><strong>Filter by date:</strong></label>
                <div class="d-flex align-items-center">
                    <label for="startDate" class="form-label">Start Date</label>
                    <input type="date" id="startDate" class="form-control me-2 mr-1" style="width: 150px;" placeholder="Start Date">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" id="endDate" class="form-control me-2 mr-1" style="width: 150px;" placeholder="End Date">
                    <button id="clearFilter" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </div>
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

                function initDateFilter(
                    tableId, dateColIndex, reportUrl, 
                    startInputId = "startDate", endInputId = "endDate",
                    reportBtnId = "generateReport", clearBtnId = "clearFilter"
                ) {
                    const startInput = document.getElementById(startInputId);
                    const endInput = document.getElementById(endInputId);
                    const reportBtn = document.getElementById(reportBtnId);
                    const clearBtn = document.getElementById(clearBtnId);
                    const clientTypeFilter = document.getElementById("clientType");
                    const serviceLevelFilter = document.getElementById("serviceLevel");
                    const paymentTypeFilter = document.getElementById("paymentType");
                    const statusFilter = document.getElementById("status");

                    function filterTable() {
                        let startDate = startInput.value;
                        let endDate = endInput.value;
                        let clientType = clientTypeFilter.value.toLowerCase();
                        let serviceLevel = serviceLevelFilter.value.toLowerCase();
                        let paymentType = paymentTypeFilter.value.toLowerCase();
                        let status = statusFilter.value.toLowerCase();

                        let table = document.getElementById(tableId);
                        if (!table) return;

                        let rows = table.getElementsByTagName("tr");

                        for (let i = 1; i < rows.length; i++) {
                            let dateCell = rows[i].getElementsByTagName("td")[dateColIndex];
                            let clientTypeCell = rows[i].getElementsByTagName("td")[5];
                            let serviceLevelCell = rows[i].getElementsByTagName("td")[7];
                            let paymentTypeCell = rows[i].getElementsByTagName("td")[13];
                            let statusCell = rows[i].getElementsByTagName("td")[14];

                            if (!dateCell || !clientTypeCell || !serviceLevelCell || !paymentTypeCell || !statusCell) continue;

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

                            if (clientType && clientTypeCell.innerText.toLowerCase() !== clientType) {
                                showRow = false;
                            }

                            if (serviceLevel && serviceLevelCell.innerText.toLowerCase() !== serviceLevel) {
                                showRow = false;
                            }

                            if (paymentType && paymentTypeCell.innerText.toLowerCase() !== paymentType) {
                                showRow = false;
                            }

                            if (status && statusCell.innerText.toLowerCase() !== status) {
                                showRow = false;
                            }

                            rows[i].style.display = showRow ? "" : "none";
                        }
                    }

                    function clearFilter() {
                        startInput.value = "";
                        endInput.value = "";
                        clientTypeFilter.value = "";
                        serviceLevelFilter.value = "";
                        paymentTypeFilter.value = "";
                        statusFilter.value = "";

                        let table = document.getElementById(tableId);
                        if (!table) return;

                        let rows = table.getElementsByTagName("tr");
                        for (let i = 1; i < rows.length; i++) {
                            rows[i].style.display = "";
                        }
                    }

                    startInput.addEventListener("change", filterTable);
                    endInput.addEventListener("change", filterTable);
                    clientTypeFilter.addEventListener("change", filterTable);
                    serviceLevelFilter.addEventListener("change", filterTable);
                    paymentTypeFilter.addEventListener("change", filterTable);
                    statusFilter.addEventListener("change", filterTable);
                    clearBtn.addEventListener("click", clearFilter);

                    reportBtn.addEventListener("click", function () {
                        let startDate = startInput.value;
                        let endDate = endInput.value;
                        let clientType = clientTypeFilter.value;
                        let serviceLevel = serviceLevelFilter.value;
                        let paymentType = paymentTypeFilter.value;
                        let status = statusFilter.value;

                        // Include filters in report URL
                        window.location.href = `${reportUrl}?start=${startDate}&end=${endDate}&clientType=${clientType}&serviceLevel=${serviceLevel}&paymentType=${paymentType}&status=${status}`;
                    });
                }

                // Example usage for "Overnight walk-in" page
                initDateFilter("dataTable", 2, "/shipment_report/generate");
            </script>

        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="dataTable">
                <thead class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Request ID</th>
                        <th>Date</th>
                        <th>Consigner</th>
                        <th>Consigner contact</th>
                        <th>Client Type</th>
                        <th>Consignee</th>
                        <th>Service Level</th>
                        <th>Items</th>
                        <th>No. of packages</th>
                        <th>Assigned rider & truck</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Payment Terms</th>
                        <th>Collection Status</th>
                        <th>Processed by</th>
                    </tr>
                </thead>
                <tfoot class="text-success">
                    <tr>
                        <th>#</th>
                        <th>Request ID</th>
                        <th>Date</th>
                        <th>Consigner</th>
                        <th>Consigner contact</th>
                        <th>Client Type</th>
                        <th>Consignee</th>
                        <th>Service level</th>
                        <th>Items</th>
                        <th>No. of packages</th>
                        <th>Assigned rider & truck</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Payment Terms</th>
                        <th>Collection status</th>
                        <th>Processed by</th>
                    </tr>
                </tfoot>
                <tbody class="text-primary">
                    @forelse ($clientRequests as $collection)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $collection->requestId }}</td>
                            <td data-date="{{ $collection->dateRequested }}">
                                {{ \Carbon\Carbon::parse($collection->dateRequested)->format('M d, Y') ?? null }}
                            </td>
                            <td>{{ $collection->client->name ?? '' }}</td>
                            <td>{{ $collection->shipmentCollection->sender_contact ?? '' }}</td>
                            <td>{{ $collection->client->type ?? '' }}</td>
                            <td>{{ $collection->shipmentCollection->receiver_name ?? '' }}</td>
                            <td>{{ \Illuminate\Support\Str::title($collection->serviceLevel->sub_category_name) }}</td>
                            <td>{{ $collection->shipmentCollection?->items?->count() ?? '' }}</td>
                            <td>{{ $collection->shipmentCollection->packages_no ?? '' }}</td>
                            <td>{{ optional($collection->user)->name ? optional($collection->user)->name . ' | ' . optional($collection->vehicle)->regNo : 'Pending' }}</td>
                            <td>{{ $collection->shipmentCollection->sender_town ?? '' }}</td>
                            <td>{{ $collection->shipmentCollection->receiver_town ?? '' }}</td>
                            <td>{{ $collection->shipmentCollection->payment_mode ?? '' }}</td>
                            <td>{{ $collection->status ?? '' }}</td>
                            <td>{{ $collection->createdBy->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="13" class="text-center">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

