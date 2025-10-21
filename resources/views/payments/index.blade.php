@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-danger">List of all Shipment Payments </h4>

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
                            <th>Client</th>
                            <th>Request Id</th>
                            <th>Waybill No</th>
                            <th>Amount To Pay (Ksh)</th>
                            <th>Amount Paid (Ksh)</th>
                            <th>Ref. No</th>
                            <th>Balance (Ksh)</th>
                            <th>Date Paid</th>
                            <th>Received By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Request Id</th>
                            <th>Waybill No</th>
                            <th>Amount To Pay  (Ksh)</th>
                            <th>Amount Paid  (Ksh)</th>
                            <th>Ref. No</th>
                            <th>Balance (Ksh)</th>
                            <th>Date Paid</th>
                            <th>Received By</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td> {{ $loop->iteration }}. </td>
                                <td> {{ $payment->client->name }} </td>
                                <td> {{ $payment->shipment_collection->requestId }} </td>
                                <td> {{ $payment->shipment_collection->waybill_no }} </td>
                                <td style="text-align: right;">{{ number_format($payment->shipment_collection->total_cost, 2) }}</td>
                                <td style="text-align: right;"> {{ number_format($payment->amount, 2) }} </td>
                                <td> {{ $payment->reference_no }} </td>
                                <td style="text-align: right;"> {{ number_format($payment->shipment_collection->total_cost - $payment->amount, 2) }} </td>
                                <td data-date="{{ $payment->date_paid }}"> {{ \Carbon\Carbon::parse($payment->date_paid)->format('d M, Y') }} </td>
                                <td> {{ $payment->user->name }} </td>
                                <td class="row pl-4">
                                    <a href="{{ route('payments.show', $payment->id) }}">
                                        <button class="btn btn-sm btn-info mr-1" title="Receipt">
                                            <i class="fas fa-file"></i> Generate Receipt
                                        </button>
                                    </a>
                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $payment->id }}"><i
                                            class="fas fa-trash"></i></button> --}}
                                    <!-- Logout Modal-->
                                    {{-- <div class="modal fade" id="delete_floor-{{ $payment->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $payment->reference_no }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('payments.destroy', ['payment' => $payment->id]) }}"
                                                        method = "POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                            value="DELETE">YES DELETE <i class="fas fa-trash"></i> </button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
