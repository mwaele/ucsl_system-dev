@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary mb-3 mb-md-0">Parcel Receipts at Destination Office</h4>


                <!-- Report Button -->
                <a href="/shipment_arrivals_report" id="generateReport"
                    class="btn btn-danger btn-sm ml-md-2 mb-2 mb-md-0 shadow-sm">
                    <i class="fas fa-download fa-sm text-white"></i> Generate Report
                </a>

            </div>

            <!-- Modal: Create Loading Sheet -->
            <div class="modal fade" id="createLoadingSheet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title text-white" id="exampleModalLabel"><strong>Create Loading Sheet</strong>
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <table class="table">
                                <thead></thead>
                                <tbody>
                                    <tr></tr>
                                </tbody>
                            </table>


                        </div>

                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close X</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-center mb-3 shadow-sm  bg-warning pt-2">
                <div class="form-inline flex-wrap justify-content-center">
                    <!-- Radio Filters -->
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" value="dispatch"
                            id="radioDispatch" checked>
                        <label class="form-check-label" for="radioDispatch">Dispatch Note</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" value="date" id="radioDate">
                        <label class="form-check-label" for="radioDate">Date</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" value="type" id="radioType">
                        <label class="form-check-label" for="radioType">COD/Walkin</label>
                    </div>

                    <!-- Dispatch Dropdown -->
                    <select id="dispatchNoteFilter" class="form-control ml-2 mb-2" style="width: 200px;">
                        <option value="">Select Batch No</option>
                        @foreach ($sheets as $sheet)
                            <option value="{{ str_pad($sheet->batch_no, 5, '0', STR_PAD_LEFT) }}">
                                {{ str_pad($sheet->batch_no, 5, '0', STR_PAD_LEFT) }}</option>
                        @endforeach
                    </select>

                    <!-- Date Picker -->
                    <input type="date" id="dateFilter" class="form-control ml-2 mb-2" style="width: 200px;"
                        value="<?= date('Y-m-d') ?>">

                    <!-- Type Filter -->
                    <select id="typeFilter" class="form-control ml-2 mb-2" style="width: 200px;">
                        <option value="">Select Type</option>
                        <option value="COD">COD</option>
                        <option value="Walkin">Walkin</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-primary " id="myTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Manifest #</th>
                            <th>Dispatch date</th>
                            <th>Office of Origin</th>
                            <th>Destination</th>
                            <th>Vehicle Number</th>
                            <th>Transporter</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Manifest #</th>
                            <th>Dispatch date</th>
                            <th>Office of Origin</th>
                            <th>Destination</th>
                            <th>Vehicle Number</th>
                            <th>Transporter</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($sheets as $sheet)
                            <tr>
                                <td class="text-danger sized">{{ str_pad($sheet->batch_no, 5, '0', STR_PAD_LEFT) }}</td>
                                <td> {{ $sheet->dispatch_date ?? 'Pending Dispatch' }} </td>
                                <td> {{ $sheet->office->name }} </td>
                                <td> {{ $sheet->rate->destination ?? '' }} @if ($sheet->destination_id == '0')
                                        {{ 'Various' }}
                                    @endif
                                </td>
                                <td> {{ $sheet->transporter_truck->reg_no }} </td>
                                <td> {{ $sheet->transporter->name }} </td>
                                <td></td>
                                <td class="row pl-4">
                                    @if (!$sheet->dispatch_date)
                                        <a href="{{ route('loadingsheet_waybills', $sheet->id) }}"><button
                                                class="btn btn-primary btn-sm mr-1">Add Waybills</button></a>
                                    @endif
                                    {{-- <a href="">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a> --}}
                                    <a href="{{ route('loading_sheets.show', $sheet->id) }}">
                                        <button class="btn btn-sm btn-warning mr-1" title="View">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#createLoadingSheet"><i class="fas fa-eye"></i> View Waybills</button>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action =" " method = "POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Delete" value="DELETE">YES DELETE <i
                                                                class="fas fa-trash"></i> </button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let table;

            if (!$.fn.DataTable.isDataTable('#myTable')) {
                table = $('#myTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                });
            } else {
                table = $('#myTable').DataTable(); // get existing instance
            }




            function filterTable() {
                const filterOption = $('input[name="filterOption"]:checked').val();
                const dispatch = $('#dispatchNoteFilter').val();
                const date = $('#dateFilter').val();
                const type = $('#typeFilter').val();

                table.rows().every(function() {
                    const data = this.data();
                    let show = true;

                    if (filterOption === 'dispatch') {
                        show = dispatch ? data[0].includes(dispatch) : true;
                    } else if (filterOption === 'date') {
                        show = date ? data[1].includes(date) : true;
                    } else if (filterOption === 'type') {
                        show = type ? data[6].includes(type) : true;
                    }

                    $(this.node()).toggle(show);
                });
            }

            $('#dispatchNoteFilter, #dateFilter, #typeFilter').on('change', filterTable);

            $('input[name="filterOption"]').on('change', function() {
                $('#dispatchNoteFilter').prop('disabled', this.value !== 'dispatch');
                $('#dateFilter').prop('disabled', this.value !== 'date');
                $('#typeFilter').prop('disabled', this.value !== 'type');
                filterTable();
            });

            $('#dispatchNoteFilter').prop('disabled', false);
            $('#dateFilter, #typeFilter').prop('disabled', true);

            // update report link

            function updateReportLink() {
                const selectedOption = $('input[name="filterOption"]:checked').val();
                let filterValue = '';

                if (selectedOption === 'dispatch') {
                    filterValue = $('#dispatchNoteFilter').val();
                } else if (selectedOption === 'date') {
                    filterValue = $('#dateFilter').val();
                } else if (selectedOption === 'type') {
                    filterValue = $('#typeFilter').val();
                }

                const reportUrl =
                    `/shipment_arrivals_report?filter=${selectedOption}&value=${encodeURIComponent(filterValue)}`;
                $('#generateReportBtn').attr('href', reportUrl);
            }

            // Initial run
            updateReportLink();

            // Update on radio change
            $('input[name="filterOption"]').on('change', function() {
                updateReportLink();
            });

            // Update on filter value change
            $('#dispatchNoteFilter, #dateFilter, #typeFilter').on('change input', function() {
                updateReportLink();
            });
        });
    </script>


    <script>
        $('#transporter_id').on('change', function() {

            var transporterId = $(this).val();
            if (transporterId) {
                $.ajax({
                    url: '/get-trucks/' + transporterId,
                    type: 'GET',
                    success: function(data) {
                        $('#truck_id').empty().append('<option value="">Select Truck</option>');
                        $.each(data, function(key, truck) {
                            $('#truck_id').append('<option value="' + truck.id + '">' + truck
                                .reg_no + '</option>');
                        });
                    }
                });
            } else {
                $('#truck_id').empty().append('<option value="">Select Truck</option>');
            }
        });
    </script>
@endsection
