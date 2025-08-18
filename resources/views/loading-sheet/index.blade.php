@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">All Loading Sheets</h6>
                <button type="button"class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                    data-target="#createLoadingSheet"><i class="fas fa-plus fa-sm text-white"></i> Create Loading
                    Sheet</button>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="createLoadingSheet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for wider layout -->
                    <div class="modal-content">
                        <div class="modal-header bg-success">

                            <h5 class="modal-title text-white" id="exampleModalLabel"> <strong>Create Loading Sheet</strong>
                            </h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('loading_sheets.store') }}" method="POST">
                                {{-- <h6 class="fw-bold text-black">Loading Sheet Information</h6> --}}
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="origin" class="form-label">Origin Office</label>
                                        <select name="origin_station_id" id="" class="form-control">
                                            <option value="">Select Origin Office</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="destination" class="form-label">Destination</label>
                                        <select name="destination" id="" class="form-control">
                                            <option value="">Select Destination</option>
                                            <option value="0">Various</option>
                                            @foreach ($destinations as $destination)
                                                <option value="{{ $destination->destination_id ?? '' }}">
                                                    {{ $destination->destination_name ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="origin" class="form-label">Transporter</label>
                                        <select name="transporter_id" id="transporter_id" class="form-control">
                                            <option value="">Select Transporter</option>
                                            @foreach ($transporters as $transporter)
                                                <option value="{{ $transporter->id }}">{{ $transporter->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="truck" class="form-label">Vehicle</label>
                                        <select name="reg_no" id="truck_id" class="form-control">
                                            <option value="">Select Vehicle</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="origin" class="form-label">Dispatcher </label>
                                        <select name="dispatcher_id" id="" class="form-control">
                                            <option value="">Select Dispatcher</option>
                                            @foreach ($dispatchers as $dispatcher)
                                                <option value="{{ $dispatcher->id }}">{{ $dispatcher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="destination" class="form-label">Batch No</label>
                                        <input type="text" value="{{ $batch_no }}" name="batch_no"
                                            class="form-control">
                                    </div>
                                </div>
                                {{-- 
                                <div class="row mb-3">

                                    <div class="col-md-6">
                                        <label for="received_by" class="form-label">Receiver Name</label>
                                        <input type="text" name="received_by" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="receiver_phone" class="form-label">Receiver Phone</label>
                                        <input type="text" name="receiver_phone" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="receiver_id_no" class="form-label">ID No.</label>
                                        <input type="text" name="receiver_id_no" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="receiver_signature" class="form-label">Receiver Signature</label>
                                        <input type="file" name="receiver_signature" class="form-control">
                                    </div>
                                </div> --}}


                        </div>
                        <div class="modal-footer d-flex justify-content-between ">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close X</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Batch No.</th>
                            <th>Dispatch date</th>
                            <th>Office of Origin</th>
                            <th>Destination</th>
                            <th>Vehicle Number</th>
                            <th>Transporter</th>
                            <th>Driver Name</th>
                            <th>Driver Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Batch No.</th>
                            <th>Dispatch date</th>
                            <th>Office of Origin</th>
                            <th>Destination</th>
                            <th>Vehicle Number</th>
                            <th>Transporter</th>
                            <th>Driver Name</th>
                            <th>Driver Phone</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($sheets as $sheet)
                            <tr>
                                <td> {{ $loop->iteration }}. </td>
                                <td> {{ str_pad($sheet->batch_no, 4, '0', STR_PAD_LEFT) }} </td>
                                <td> {{ $sheet->dispatch_date ?? 'Pending Dispatch' }} </td>
                                <td> {{ $sheet->office->name }} </td>
                                <td> {{ $sheet->rate->destination ?? '' }} @if ($sheet->destination_id == '0')
                                        {{ 'Various' }}
                                    @endif
                                </td>
                                <td> {{ $sheet->transporter_truck->reg_no }} </td>
                                <td> {{ $sheet->transporter->name }} </td>
                                <td> {{ $sheet->transporter_truck->driver_name }} </td>
                                <td> {{ $sheet->transporter_truck->driver_contact }} </td>
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
                                    <a href="/generate_loading_sheet/{{ $sheet->id }}">
                                        <button class="btn btn-sm btn-danger mr-1" title="PDF Download">
                                            <i class="fas fa-file-pdf"> Generate Manifest</i>
                                        </button>
                                    </a>
                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target=""><i class="fas fa-trash"></i></button> --}}
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
