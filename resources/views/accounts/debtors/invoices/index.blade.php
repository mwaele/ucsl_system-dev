@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">Accounting - Debtors Invoice</h6>

                <!-- Date Range Filter -->
                <div class="form-check form-check-inline">
                    <div id="dateRangeFilter" class="d-flex flex-wrap justify-content-center mt-2">
                        <input type="date" id="startDate" class="form-control ml-2 mb-2" style="width: 200px;">
                        <input type="date" id="endDate" class="form-control ml-2 mb-2" style="width: 200px;">

                        <button id="clearFilter" class="btn btn-secondary ml-2 mb-2">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
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
                        initDateFilter("dataTable", 7, "/payments_report");
                    </script>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>W/Bill Date</th>
                            <th>Client Name</th>
                            <th>Request ID</th>
                            <th>W/Bill No</th>
                            <th>Invoice No</th>
                            <th>Batch No</th>
                            <th>Parcel Desc.</th>
                            <th>Invoice Amnt.</th>
                            <th>VAT</th>
                            <th>Total Amnt.</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>W/Bill Date</th>
                            <th>Client Name</th>
                            <th>Request ID</th>
                            <th>W/Bill No</th>
                            <th>Invoice No</th>
                            <th>Batch No</th>
                            <th>Parcel Desc.</th>
                            <th>Invoice Amnt.</th>
                            <th>VAT</th>
                            <th>Total Amnt.</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td> {{ $loop->iteration }}. </td>
                                <td> {{ $invoice->shipment_collection->verified_at }} </td>
                                <td> {{ $invoice->shipment_collection->sender_name }} </td>
                                <td> {{ $invoice->shipment_collection->requestId }} </td>
                                <td> {{ $invoice->shipment_collection->waybill_no }} </td>
                                <td> {{ $invoice->invoice_no }} </td>
                                <td> {{ $invoice->invoice_no }} </td>
                                <td> {{ $invoice->invoice_no }} </td>
                                <td> {{ $invoice->invoice_no }} </td>
                                <td> {{ $invoice->invoice_no }} </td>
                                <td> {{ $invoice->invoice_no }} </td>
                                <td> {{ $invoice->invoice_no }} </td>
                                <td> {{ $invoice->invoice_no }} </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
