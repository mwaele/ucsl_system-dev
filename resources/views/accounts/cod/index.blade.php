@extends('layouts.custom')

@section('content')
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <!-- Heading (Left) -->
                <h4 class="m-0 pr-3 font-weight-bold text-danger">COD Management </h4>

                <!-- Right Side (Date Filter + Generate PDF) -->
                <div class="d-flex align-items-center ms-auto">
                    <div id="dateRangeFilter" class="d-flex flex-wrap align-items-center mr-4">
                        <h5 class="m-0 font-weight-bold text-primary mr-2">Filter by date:</h5>
                        <input type="date" id="startDate" class="form-control me-2 mr-2" max="{{ date('Y-m-d') }}"
                            style="width: 150px;">
                        <input type="date" id="endDate" class="form-control me-2 mr-2" max="{{ date('Y-m-d') }}"
                            style="width: 150px;">
                        <button id="clearFilter" class="btn btn-secondary mr-2">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>

                    <!-- Generate PDF -->
                    <a href="/cod_management_report" id="generateReportBtn"
                        class="btn btn-danger btn-sm ml-md-2 mb-2 mb-md-0 shadow-sm">
                        <i class="fas fa-download fa-sm text-white"></i> Generate Report
                    </a>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered " id="myTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Amount Collected</th>
                            <th>Request ID</th>
                            <th>Collected By</th>
                            <th>Date Collected</th>
                            <th>Collection Balance</th>
                            <th>Amount Received</th>
                            <th>Receiver Remarks</th>
                            <th>Received By</th>
                            <th>Date Received</th>
                            <th>Received Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cods as $cod)
                            @php
                                $actualAmount = 0;
                                if ($cod->shipment) {
                                    $actualAmount =
                                        ($cod->shipment->actual_total_cost ?? 0) +
                                        ($cod->shipment->last_mile_delivery_charges ?? 0);
                                }
                            @endphp
                            <tr>
                                <td>{{ number_format($cod->amountCollected, 2) }}</td>
                                <td>{{ $cod->requestId }}</td>
                                <td>{{ $cod->collector->name ?? 'N/A' }}</td>
                                <td>{{ $cod->dateCollected }}</td>
                                <td>{{ number_format($cod->collectionBalance, 2) }}</td>
                                <td>{{ number_format($cod->amountReceived, 2) }}</td>
                                <td>{{ $cod->receiverRemarks }}</td>
                                <td>{{ $cod->receiver->name ?? 'N/A' }}</td>
                                <td>{{ $cod->dateReceived }}</td>
                                <td>{{ number_format($cod->receicedBalance, 2) }}</td>
                                <td>
                                    @if ($cod->status !== 'Complete')
                                        <button class="btn btn-sm btn-primary editBtn" data-id="{{ $cod->id }}"
                                            data-request="{{ $cod->shipment->requestId }}"
                                            data-actual="{{ $actualAmount }}" data-amount="{{ $cod->amount_received }}"
                                            data-balance="{{ $cod->received_balance }}">Receive
                                            Cash</button>
                                    @endif
                                    @if ($cod->status == 'Complete')
                                        <button class="btn btn-info"> Received</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Modal -->
                <div class="modal fade" id="updateCodModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="collectionForm">
                                @csrf
                                <input type="hidden" name="id" id="cod_id">
                                <div class="modal-header">
                                    <h5 class="modal-title">Receive Cash</h5>
                                    <button type="button" class="btn-close btn-danger" data-dismiss="modal">X</button>
                                </div>

                                <div class="modal-body">
                                    {{-- <div class="mb-2">
                                        <label>Rider Remarks</label>
                                        <textarea class="form-control" name="rider_remarks" id="rider_remarks"></textarea>
                                    </div> --}}
                                    <div class="mb-2">
                                        <label>Actual Amount</label>
                                        <input type="number" step="0.01" class="form-control" id="actual_amount"
                                            name="actual_amount" readonly>
                                    </div>
                                    <div class="mb-2">
                                        <label>Amount Received</label>
                                        <input type="number" step="0.01" class="form-control" name="amount_received"
                                            id="amount_received">
                                    </div>
                                    {{-- <div class="mb-2">
                                        <label>Received By</label>
                                        <input type="number" class="form-control" name="received_by" id="received_by">
                                    </div> --}}
                                    {{-- <div class="mb-2">
                                        <label>Date Received</label>
                                        <input type="date" class="form-control" name="date_received" id="date_received">
                                    </div> --}}
                                    <div class="mb-2">
                                        <label> Balance</label>
                                        <input type="number" step="0.01" class="form-control" name="received_balance"
                                            id="balance" readonly>
                                    </div>
                                    <div class="mb-2">
                                        <label>Receiver Remarks</label>
                                        <textarea class="form-control" name="receiver_remarks" id="receiver_remarks"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{ $cods->links() }}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const startDateInput = $('#startDate');
            const endDateInput = $('#endDate');
            const clearFilterBtn = $('#clearFilter');
            const generateReportBtn = $('#generateReportBtn'); // ensure this ID exists or change
            // Initialize DataTable
            const table = $('#myTable').DataTable({
                responsive: true,
                pageLength: 10,
            });

            // Utility: parse various date string formats robustly into a Date (local)
            function parseRowDate(raw) {
                if (!raw) return NaN;
                raw = raw.trim();

                // If already ISO-like: replace space with T
                // e.g. "2025-10-30 17:36:00" -> "2025-10-30T17:36:00"
                if (/^\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}(:\d{2})?$/.test(raw)) {
                    return new Date(raw.replace(' ', 'T'));
                }

                // If just YYYY-MM-DD
                if (/^\d{4}-\d{2}-\d{2}$/.test(raw)) {
                    return new Date(raw + 'T00:00:00');
                }

                // If d-m-Y or d/m/Y (common), convert to Y-m-d
                if (/^\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}$/.test(raw)) {
                    const parts = raw.split(/[-\/]/);
                    // detect ordering: assume d-m-Y
                    const [d, m, y] = parts.map(Number);
                    return new Date(y, m - 1, d);
                }

                // Last resort: try Date.parse
                const parsed = Date.parse(raw);
                return isNaN(parsed) ? NaN : new Date(parsed);
            }

            // Toggle this to true to see debug logs in the console
            const DEBUG = false;

            // DataTables custom search
            $.fn.dataTable.ext.search.push(function(settings, data) {
                // Only run for our specific table (helps if multiple DTs on page)
                if (settings.nTable.id !== 'myTable') return true;

                const startDateVal = startDateInput.val(); // "YYYY-MM-DD"
                const endDateVal = endDateInput.val();

                // If no filters -> include row
                if (!startDateVal && !endDateVal) return true;

                // Build today boundary (end of today)
                const today = new Date();
                today.setHours(23, 59, 59, 999);

                // Validate inputs are not in future (reject row if user input invalid)
                if (startDateVal) {
                    const startCheck = new Date(startDateVal + 'T00:00:00');
                    if (startCheck > today) {
                        // If desired, you can alert here instead — but ext.search must return boolean:
                        if (DEBUG) console.warn('Start date is in the future', startDateVal);
                        return false;
                    }
                }
                if (endDateVal) {
                    const endCheck = new Date(endDateVal + 'T23:59:59');
                    if (endCheck > today) {
                        if (DEBUG) console.warn('End date is in the future', endDateVal);
                        return false;
                    }
                }
                if (startDateVal && endDateVal) {
                    const s = new Date(startDateVal + 'T00:00:00');
                    const e = new Date(endDateVal + 'T23:59:59');
                    if (e < s) {
                        if (DEBUG) console.warn('End earlier than start', startDateVal, endDateVal);
                        return false;
                    }
                }

                // Get raw "Date Collected" - adjust index if your date is in different column
                const rawDate = data[3] || ''; // column index 3
                const rowDate = parseRowDate(rawDate);

                if (DEBUG) {
                    console.debug('Row raw:', rawDate, 'parsed:', rowDate);
                }

                if (isNaN(rowDate)) {
                    // If row date cannot be parsed, exclude it (safer) — change to `return true` to include instead.
                    if (DEBUG) console.warn('Unparseable row date:', rawDate);
                    return false;
                }

                // Prepare start/end Date objects for comparison
                const start = startDateVal ? new Date(startDateVal + 'T00:00:00') : null;
                const end = endDateVal ? new Date(endDateVal + 'T23:59:59') : null;

                // Start-only: include rows whose date >= start
                if (start && !end) {
                    return rowDate >= start;
                }

                // End-only: include rows whose date <= end
                if (!start && end) {
                    return rowDate <= end;
                }

                // Both provided: inclusive range
                if (start && end) {
                    return rowDate >= start && rowDate <= end;
                }

                // fallback include
                return true;
            });

            // Filter runner + validation UI
            function runFilter() {
                const startVal = startDateInput.val();
                const endVal = endDateInput.val();
                const today = new Date();
                today.setHours(23, 59, 59, 999);

                // upfront validation with user alerts for better UX
                if (startVal) {
                    //alert(startVal);
                    const s = new Date(startVal + 'T00:00:00');
                    if (s > today) {
                        alert("'Start Date' cannot be greater than today's date.");
                        startDateInput.val('');
                        return;
                    }
                }
                if (endVal) {
                    const e = new Date(endVal + 'T23:59:59');
                    if (e > today) {
                        alert("'End Date' cannot be greater than today's date.");
                        endDateInput.val('');
                        return;
                    }
                }
                if (startVal && endVal) {
                    const s = new Date(startVal + 'T00:00:00');
                    const e = new Date(endVal + 'T23:59:59');
                    if (e < s) {
                        alert("'End Date' cannot be earlier than 'Start Date'.");
                        return;
                    }
                }

                // redraw table so ext.search runs
                table.draw();
                updateReportLink();
            }

            // Clear button
            clearFilterBtn.on('click', function() {
                startDateInput.val('');
                endDateInput.val('');
                table.draw();
                updateReportLink();
            });

            // updateReportLink (same as you had)
            function updateReportLink() {
                const start = startDateInput.val() || '';
                const end = endDateInput.val() || '';
                let filterValue = '';

                if (start && end) filterValue = `${start}_${end}`;
                else if (start) filterValue = start;
                else if (end) filterValue = end;

                const params = new URLSearchParams({
                    filter: 'daterange',
                    value: filterValue
                });

                // ensure this ID matches your actual Generate button
                if (generateReportBtn.length) {
                    generateReportBtn.attr('href', `/cod_management_report?${params.toString()}`);
                } else {
                    // If button is not a link, adjust accordingly
                    $('#generateReport').attr('href', `/cod_management_report?${params.toString()}`);
                }
            }

            // hook events
            $('#startDate, #endDate').on('change', runFilter);

            // initial draw (no filter)
            table.draw();
        });
    </script>


    <script>
        $(function() {

            let modal = new bootstrap.Modal(document.getElementById('updateCodModal'));

            // Open modal with data
            $('.editBtn').click(function() {
                let btn = $(this);
                $('#cod_id').val(btn.data('id'));
                $('#requestId').val(btn.data('request'));
                $('#actual_amount').val(btn.data('actual'));
                $('#amount_received').val(btn.data('amount'));
                $('#balance').val(btn.data('balance'));
                $('#date_received').val(btn.data('datereceived'));
                modal.show();
            });

            // Recalculate balance interactively
            $('#amount_received').on('input', function() {
                let actual = parseFloat($('#actual_amount').val()) || 0;
                let received = parseFloat($(this).val()) || 0;
                let balance = (actual - received).toFixed(2);
                $('#balance').val(balance);
            });

            // Submit update
            $('#collectionForm').on('submit', function(e) {
                e.preventDefault();

                const id = $('#cod_id').val();
                const formData = $(this).serialize();

                $.ajax({
                    url: '/cod_management/' + id,
                    type: 'POST', // still POST, but we'll spoof PUT
                    data: formData + '&_method=PUT', // Laravel interprets this as PUT
                    success: function(response) {
                        alert('COD updated successfully!');
                        $('#updateCodModal').modal('hide');
                        location.reload(); // reload or update row dynamically
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Error updating COD');
                    }
                });
            });


        });
    </script>
@endsection
