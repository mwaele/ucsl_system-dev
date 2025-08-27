@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <!-- Heading (Left) -->
                <h4 class="m-0 font-weight-bold text-danger">Accounts Management - Unposted Invoice</h4>

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

                    <!-- Generate PDF -->
                    <button id="generateReport" class="btn btn-danger shadow-sm">
                        <i class="fas fa-download fa text-white"></i> Generate Report
                    </button>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="d-flex justify-content-center">
                    <button id="bulkActionBtn" class="btn btn-primary" disabled>
                        Process Selected
                    </button>
                </div>
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>#</th>
                            <th>W/Bill Date</th>
                            <th>Client</th>
                            <th>Request ID</th>
                            <th>W/Bill No</th>
                            <th>Dispatch Batch No</th>
                            <th>INV No</th>
                            <th>Parcel Desc.</th>
                            <th>INV Amt.</th>
                            <th>VAT</th>
                            <th>Total Amnt.</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>
                                    <input type="checkbox" class="rowCheckbox" value="{{ $invoice->id }}">
                                </td>
                                <td> {{ $loop->iteration }}. </td>
                                <td data-date="{{ $invoice->shipment_collection->verified_at }}"> {{ \Carbon\Carbon::parse($invoice->shipment_collection->verified_at)->format('M d, Y') ?? null }} </td>
                                <td> {{ $invoice->shipment_collection->sender_name }} </td>
                                <td> {{ $invoice->shipment_collection->requestId }} </td>
                                <td> {{ $invoice->shipment_collection->waybill_no }} </td>
                                <td> {{ $invoice->loading_sheet_waybills->loading_sheet->batch_no_formatted ?? null }} </td>
                                <td> {{ $invoice->invoice_no }} </td>
                                <td> {!! $invoice->shipment_collection->items->pluck('item_name')->join('<br>') !!} </td>
                                <td> Ksh {{ number_format($invoice->shipment_collection->actual_cost, 2) }}</td>
                                <td> Ksh {{ number_format($invoice->shipment_collection->actual_vat, 2) }}</td>
                                <td> Ksh {{ number_format($invoice->amount, 2) }}</td>
                                <td>
                                    @if(strtolower(str_replace(' ', '', $invoice->status)) === 'unpaid')
                                        <span class="badge bg-info text-white p-2">Unposted</span>
                                    @else
                                        <span class="badge bg-success text-white p-2">Posted</span>
                                    @endif
                                </td>
                                <td>
                                    @if(strtolower(str_replace(' ', '', $invoice->status)) === 'paid')
                                        <button class="btn btn-sm btn-success" disabled>Posted</button>
                                    @else
                                        <!-- Trigger button -->
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#post-invoice-{{ $invoice->id }}">
                                            Post
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="post-invoice-{{ $invoice->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="postInvoiceLabel-{{ $invoice->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="postInvoiceLabel-{{ $invoice->id }}">Confirm Post Invoice</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <form action="" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to post Invoice <strong>{{ $invoice->invoice_no }}</strong>?</p>
                                                            <p>Amount: <strong>Ksh {{ number_format($invoice->amount, 2) }}</strong></p>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Yes, Post</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <!-- Totals row -->
                        <tr class="font-weight-bold bg-light">
                            <td colspan="8"></td>
                            <td>Totals:</td>
                            <td>Ksh {{ number_format($invoices->sum(fn($i) => $i->shipment_collection->actual_cost), 2) }}</td>
                            <td>Ksh {{ number_format($invoices->sum(fn($i) => $i->shipment_collection->actual_vat), 2) }}</td>
                            <td>Ksh {{ number_format($invoices->sum('amount'), 2) }}</td>
                            <td colspan="2"></td>
                        </tr>

                        <!-- Original footer headers (useful if you're using DataTables) -->
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th>W/Bill Date</th>
                            <th>Client Name</th>
                            <th>Request ID</th>
                            <th>W/Bill No</th>
                            <th> Dispatch Batch No</th>
                            <th>INV. No</th>
                            <th>Desc.</th>
                            <th>INV Amt.</th>
                            <th>VAT</th>
                            <th>Total Amt.</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

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
                initDateFilter("dataTable", 1, "/payments_report");
            </script>
            <script>
                // Select/Deselect all
                document.getElementById('selectAll').addEventListener('change', function() {
                    let checked = this.checked;
                    document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = checked);
                    toggleBulkButton();
                });

                // Enable/disable button when checkboxes change
                document.querySelectorAll('.rowCheckbox').forEach(cb => {
                    cb.addEventListener('change', toggleBulkButton);
                });

                function toggleBulkButton() {
                    let anyChecked = document.querySelectorAll('.rowCheckbox:checked').length > 0;
                    document.getElementById('bulkActionBtn').disabled = !anyChecked;
                }

                // Handle bulk action click
                document.getElementById('bulkActionBtn').addEventListener('click', function() {
                    let selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked'))
                                        .map(cb => cb.value);

                    if (selectedIds.length > 0) {
                        // Example: send via AJAX or form
                        console.log("Selected IDs:", selectedIds);

                        // If you want to submit to Laravel route:
                        fetch("", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({ ids: selectedIds })
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert("Processed: " + data.message);
                            location.reload();
                        })
                        .catch(err => console.error(err));
                    }
                });
            </script>
        </div>
    </div>
@endsection
