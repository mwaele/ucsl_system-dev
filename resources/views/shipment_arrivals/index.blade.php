@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary mb-3 mb-md-0">Parcel Receipts at <strong>
                        {{ Auth::user()->office->name ?? '' }} </strong></h4>


                <!-- Report Button -->
                <a href="/shipment_arrivals_report" id="generateReportBtn"
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

            {{-- Allocate clerk modal --}}

            <div class="modal fade" id="allocateClerk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title text-white" id="exampleModalLabel"><strong>Allocate Clerk to physically
                                    check arrival parcels</strong>
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="updateArrivalForm" method="POST">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="loading_sheet_id" id="loading_sheet_id">


                                <div class="form-row">
                                    <label for="dispatcher">Dispatchers</label>
                                    <select name="dispatchers" id="dispatchers" class="form-control">

                                        <option value="">Select Dispatcher</option>
                                        @foreach ($dispatchers as $dispatcher)
                                            <option value="{{ $dispatcher->id }}">{{ $dispatcher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>

                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close X</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-center mb-3 shadow-sm  bg-warning pt-2">
                <div class="form-inline flex-wrap justify-content-center">
                    <!-- Radio Filters -->



                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" value="date" id="radioDate">
                        <label class="form-check-label" for="radioDate">Date</label>

                        <!-- Date Picker -->
                        <input type="date" id="dateFilter" class="form-control ml-2 mb-2" style="width: 200px;"
                            value="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" value="dispatch"
                            id="radioDispatch" checked>
                        <label class="form-check-label" for="radioDispatch">Dispatch Note</label>

                        <!-- Dispatch Dropdown -->
                        <select id="dispatchNoteFilter" class="form-control ml-2 mb-2" style="width: 200px;">
                            <option value="">Select Dispatch Note</option>
                            @foreach ($sheets as $sheet)
                                <option value="{{ $sheet->batch_no }}">
                                    {{ str_pad($sheet->batch_no, 5, '0', STR_PAD_LEFT) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range Radio -->
                    <div class="form-check form-check-inline">
                        {{-- <input class="form-check-input" type="radio" name="filterOption" id="filterByDateRange"
                            value="daterange"> --}}
                        {{-- <input class="form-check-input" type="radio" name="filterOption" id="filterByDateRange"
                            value="range">

                        <label class="form-check-label" for="filterByDateRange">Date Range</label> --}}

                        <!-- Date Range Pickers -->
                        {{-- <div id="dateRangeFilter" class="d-flex flex-wrap justify-content-center mt-2">

                            <input type="date" id="startDate" class="form-control ml-2 mb-2" style="width: 200px;"
                                placeholder="Start Date">
                            <input type="date" id="endDate" class="form-control ml-2 mb-2" style="width: 200px;"
                                placeholder="End Date">
                        </div> --}}
                    </div>
                    <!-- Type Filter -->
                    {{-- <select id="typeFilter" class="form-control ml-2 mb-2" style="width: 200px;">
                        <option value="">Select Type</option>
                        <option value="COD">COD</option>
                        <option value="Walkin">Walkin</option>
                    </select> --}}
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
                            <th>Waybills</th>
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
                            <th>Waybills</th>
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

                                <td class="text-center">{{ $sheet->waybills_count }}</td>
                                <td>{{ $sheet->status }}</td>
                                <td class="row pl-4">
                                    @if ($sheet->offloading_clerk == '')
                                        <button class="btn btn-primary btn-sm mr-1 openAllocateModal"
                                            data-id="{{ $sheet->id }}" data-toggle="modal"
                                            data-target="#allocateClerk">Allocate
                                            Clerk</button>
                                    @endif
                                    @if (!$sheet->dispatch_date)
                                        <a href="{{ route('loadingsheet_waybills', $sheet->id) }}"><button
                                                class="btn btn-primary btn-sm mr-1">Add Waybills</button></a>
                                    @endif
                                    {{-- <a href="">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a> --}}
                                    <a href="{{ route('arrival_details', $sheet->id) }}">
                                        <button class="btn btn-sm btn-warning mr-1" title="View">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#createLoadingSheet"><i class="fas fa-eye"></i> View
                                        Waybills</button>
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

            $('.openAllocateModal').on('click', function() {
                const sheetId = $(this).data('id');
                $('#loading_sheet_id').val(sheetId);
            });

            $('#updateArrivalForm').on('submit', function(e) {
                e.preventDefault();

                const formData = {
                    _token: $('input[name="_token"]').val(),
                    loading_sheet_id: $('#loading_sheet_id').val(),
                    dispatchers: $('#dispatchers').val(),
                };

                //alert(JSON.stringify(formData)); // for debugging

                $.ajax({
                    url: '{{ route('update_arrival_details') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert('Updated successfully!');
                        $('#allocateClerk').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Something went wrong!');
                        console.error(xhr.responseText);
                    }
                });
            });

            let table;

            if (!$.fn.DataTable.isDataTable('#myTable')) {
                table = $('#myTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                });
            } else {
                table = $('#myTable').DataTable();
            }

            function toggleDateRangeVisibility() {
                const selected = $('input[name="filterOption"]:checked').val();
                if (selected === 'range') {
                    $('#dateRangeFilter').show();
                } else {
                    $('#dateRangeFilter').hide();
                }
            }


            function filterTable() {
                const filterOption = $('input[name="filterOption"]:checked').val();
                const dispatch = $('#dispatchNoteFilter').val();
                const date = $('#dateFilter').val();
                const type = $('#typeFilter').val();
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();

                table.rows().every(function() {
                    const data = this.data();
                    let show = true;

                    if (filterOption === 'dispatch') {
                        show = dispatch ? data[0].includes(dispatch) : true;
                    } else if (filterOption === 'date') {
                        show = date ? data[1].includes(date) : true;
                    } else if (filterOption === 'type') {
                        show = type ? data[6].includes(type) : true;
                    } else if (filterOption === 'daterange') {
                        const rowDate = new Date(data[1]);
                        const from = new Date(startDate);
                        const to = new Date(endDate);
                        if (startDate && endDate) {
                            show = rowDate >= from && rowDate <= to;
                        }
                    }

                    $(this.node()).toggle(show);
                });
            }

            function updateInputStates() {
                const selected = $('input[name="filterOption"]:checked').val();
                $('#dispatchNoteFilter').prop('disabled', selected !== 'dispatch');
                $('#dateFilter').prop('disabled', selected !== 'date');
                $('#typeFilter').prop('disabled', selected !== 'type');
                $('#startDate, #endDate').prop('disabled', selected !== 'daterange');
            }

            function updateReportLink() {
                const selectedOption = document.querySelector('input[name="filterOption"]:checked')?.value;
                let filterValue = '';

                if (selectedOption === 'dispatch') {
                    filterValue = document.getElementById('dispatchNoteFilter').value;
                } else if (selectedOption === 'date') {
                    filterValue = document.getElementById('dateFilter').value;
                } else if (selectedOption === 'type') {
                    filterValue = document.getElementById('typeFilter').value;
                } else if (selectedOption === 'range') {
                    const start = document.getElementById('startDate').value;
                    const end = document.getElementById('endDate').value;
                    if (start && end) {
                        filterValue = `${start}_${end}`;
                    }
                }

                const url =
                    `/shipment_arrivals_report?filter=${selectedOption || ''}&value=${encodeURIComponent(filterValue)}`;
                document.getElementById('generateReportBtn').setAttribute('href', url);
            }

            $('#dispatchNoteFilter, #dateFilter,  #startDate, #endDate').on('change input', function() {
                filterTable();
                updateReportLink();
            });

            $('input[name="filterOption"]').on('change', function() {
                updateInputStates();
                filterTable();
                updateReportLink();
            });

            // Initial state
            updateInputStates();
            filterTable();
            updateReportLink();
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
