@extends('layouts.custom')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Reports Dashboard</h5>
        </div>
    </div>

    <div class="card-body">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="reportTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="sameday-tab" data-toggle="tab" href="#sameday" role="tab">Sameday & Overnight</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="collection-tab" data-toggle="tab" href="#collection" role="tab">Parcel Collection</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="rider-tab" data-toggle="tab" href="#rider" role="tab">Rider Performance</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="driver-tab" data-toggle="tab" href="#driver" role="tab">Driver Shipment Analysis</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="cod-tab" data-toggle="tab" href="#cod" role="tab">CoD & Cash</a>
            </li>
            <!-- Continue adding tabs for all 10 reports -->
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="reportTabsContent">
            
            <!-- Sameday & Overnight -->
            <div class="tab-pane fade show active" id="sameday" role="tabpanel">
                
            </div>

            <!-- Parcel Collection -->
            <div class="tab-pane fade" id="collection" role="tabpanel">

            </div>

            <!-- Rider Performance -->
            <div class="tab-pane fade" id="rider" role="tabpanel">

            </div>

            <!-- Driver Shipment Analysis -->
            <div class="tab-pane fade" id="driver" role="tabpanel">

            </div>

            <!-- CoD & Cash -->
            <div class="tab-pane fade" id="cod" role="tabpanel">

            </div>

            <!-- Add more includes here -->
        </div>

        <div class="d-flex align-items-center ms-auto">
            <!-- Client Type Filter -->
            <div class="form-group mx-2">
                <label for="clientType" class="form-label mb-0"><strong>Client Type:</strong></label>
                <select id="clientType" class="form-control">
                    <option value="">All</option>
                    <option value="walkin">Walk-in</option>
                    <option value="on_account">On Account</option>
                </select>
            </div>

            <!-- Service Level Filter -->
            <div class="form-group mx-2">
                <label for="serviceLevel" class="form-label mb-0"><strong>Service Level:</strong></label>
                <select id="serviceLevel" class="form-control">
                    <option value="">All</option>
                    <option value="Same Day">Same Day</option>
                    <option value="Overnight">Overnight</option>
                </select>
            </div>

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
                    const clientTypeFilter = document.getElementById("clientType");
                    const serviceLevelFilter = document.getElementById("serviceLevel");

                    function filterTable() {
                        let startDate = startInput.value;
                        let endDate = endInput.value;
                        let clientType = clientTypeFilter.value.toLowerCase();
                        let serviceLevel = serviceLevelFilter.value.toLowerCase();

                        let table = document.getElementById(tableId);
                        if (!table) return;

                        let rows = table.getElementsByTagName("tr");

                        for (let i = 1; i < rows.length; i++) {
                            let dateCell = rows[i].getElementsByTagName("td")[dateColIndex];
                            let clientTypeCell = rows[i].getElementsByTagName("td")[4];
                            let serviceLevelCell = rows[i].getElementsByTagName("td")[6];

                            if (!dateCell || !clientTypeCell || !serviceLevelCell) continue;

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

                            rows[i].style.display = showRow ? "" : "none";
                        }
                    }

                    function clearFilter() {
                        startInput.value = "";
                        endInput.value = "";
                        clientTypeFilter.value = "";
                        serviceLevelFilter.value = "";

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
                    clearBtn.addEventListener("click", clearFilter);

                    reportBtn.addEventListener("click", function () {
                        let startDate = startInput.value;
                        let endDate = endInput.value;
                        let clientType = clientTypeFilter.value;
                        let serviceLevel = serviceLevelFilter.value;

                        // Include filters in report URL
                        window.location.href = `${reportUrl}?start=${startDate}&end=${endDate}&clientType=${clientType}&serviceLevel=${serviceLevel}`;
                    });
                }


                // Example usage for "Overnight walk-in" page
                initDateFilter("dataTable", 2, "/shipment_report.generate");
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
                        <th>Client Type</th>
                        <th>Consignee</th>
                        <th>Service level</th>
                        <th>Items</th>
                        <th>No. of packages</th>
                        <th>Assigned rider & truck</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Collection status</th>
                        <th>Processed by</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Request ID</th>
                        <th>Date</th>
                        <th>Consigner</th>
                        <th>Client Type</th>
                        <th>Consignee</th>
                        <th>Service level</th>
                        <th>Items</th>
                        <th>No. of packages</th>
                        <th>Assigned rider & truck</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Collection status</th>
                        <th>Processed by</th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse ($clientRequests as $collection)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $collection->requestId }}</td>
                            <td data-date="{{ $collection->dateRequested }}">
                                {{ \Carbon\Carbon::parse($collection->dateRequested)->format('M d, Y') ?? null }}
                            </td>
                            <td>{{ $collection->client->name ?? '' }}</td>
                            <td>{{ $collection->client->type ?? '' }}</td>
                            <td>{{ $collection->shipmentCollection->receiver_name ?? '' }}</td>
                            <td>{{ $collection->serviceLevel->sub_category_name }}</td>
                            <td>{{ $collection->shipmentCollection?->items?->count() ?? '' }}</td>
                            <td>{{ $collection->shipmentCollection->packages_no ?? '' }}</td>
                            <td>{{ $collection->user->name ?? '—' }} | {{ $collection->vehicle->regNo ?? '—' }}</td>
                            <td>{{ $collection->shipmentCollection->sender_town ?? '' }}</td>
                            <td>{{ $collection->shipmentCollection->receiver_town ?? '' }}</td>
                            <td>{{ $collection->status ?? '' }}</td>
                            <td>{{ $collection->createdBy->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="13" class="text-center">No records found</td></tr>
                    @endforelse
                </tbody>
                <script>
                $(document).ready(function () {
                    // Apply filters
                    $('#applyFilters').on('click', function () {
                        $.ajax({
                            url: "{{ route('reports.filter') }}",
                            type: "GET",
                            data: $('#filterForm').serialize(),
                            success: function (data) {
                                $('#reportsTableBody').html(data);
                            },
                            error: function () {
                                alert('Something went wrong while filtering.');
                            }
                        });
                    });

                    // Download PDF with filters
                    $('#downloadPdf').on('click', function () {
                        let query = $('#filterForm').serialize();
                        window.location.href = "{{ route('reports.exportPdf') }}?" + query;
                    });
                });
                </script>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    let table = $('#reportsTable').DataTable({
        dom: 'frtip', // still needed for Buttons to initialize
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export Excel',
                exportOptions: {
                    columns: ':visible',
                    modifier: { search: 'applied', order: 'applied' }
                }
            },
            {
                extend: 'csvHtml5',
                text: 'Export CSV',
                exportOptions: {
                    columns: ':visible',
                    modifier: { search: 'applied', order: 'applied' }
                }
            },
            {
                extend: 'pdfHtml5',
                text: 'Export PDF',
                exportOptions: {
                    columns: ':visible',
                    modifier: { search: 'applied', order: 'applied' }
                }
            },
            {
                extend: 'print',
                text: 'Print',
                exportOptions: {
                    columns: ':visible',
                    modifier: { search: 'applied', order: 'applied' }
                }
            }
        ],
        paging: false,
        info: true,
        scrollY: "500px",
        scrollCollapse: true,
        language: {
            lengthMenu: 'Showing _MENU_ Entries Per Page',
            search: 'Search:',
            info: "Showing _START_ to _END_ of _TOTAL_ Entries",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        initComplete: function () {
            this.api()
                .columns()
                .every(function () {
                    let column = this;
                    let headerCell = $(column.header());
                    let title = headerCell.text();

                    headerCell.empty().append(
                        $('<div>').css({ display: 'flex', flexDirection: 'column' }).append(
                            $('<span>').text(title),
                            $('<select>').append($('<option>')).on('change', function () {
                                let val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            })
                        )
                    );

                    column.data().unique().sort().each(function (d) {
                        if (d) {
                            headerCell.find('select').append(new Option(d));
                        }
                    });
                });
        }
    });

    // Move buttons into custom container
    table.buttons().container()
        .appendTo('#exportButtons')
        .find('button')
        .removeClass('dt-button')
        .addClass('btn btn-sm btn-primary me-2');
});
</script>
@endpush

