@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">My Collections List</h6>
                <a href="/collections_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Telephone Number</th>
                            <th>Date Allocated</th>
                            <th>Physical Address</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Client Name</th>
                            <th>Client Telephone Number</th>
                            <th>Date Allocated</th>
                            <th>Physical Address</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($collections as $collection)
                            <tr>
                                <td> {{ $collection->client->name }} </td>
                                <td> {{ $collection->client->contactPersonPhone }} </td>
                                <td> {{ $collection->created_at }} </td>
                                <td> {{ $collection->client->address }} </td>
                                <td>{{ $collection->status }}</td>
                                <td class="row pl-4">
                                    <a href="#">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                    <a href="#">
                                        <button class="btn btn-sm btn-warning" data-toggle="modal"
                                            data-target="#collect-{{ $collection->id }}"><i class="fas fa-bars"></i>
                                        </button>
                                    </a>
                                    {{-- <a href="#">
                                        <button class="btn btn-sm btn-warning mr-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </a>
                                    <a href="#">
                                        <button class="btn btn-sm btn-success mr-1" title="PDF Download">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    </a> --}}
                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $collection->id }}"><i
                                            class="fas fa-trash"></i></button> --}}
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="delete_floor-{{ $collection->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $collection->client_name }}.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action ="#" method = "POST">
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
                                    </div>


                                    <div class="modal fade" id="collect-{{ $collection->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="collectionModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p class="text-dark ">Collection of {{ $collection->parcelDetails }}.
                                                        for
                                                        {{ $collection->client->name }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action ="#" method = "POST">
                                                        @csrf
                                                        <!-- Sender and Receiver in same row -->

                                                        <div class="row">
                                                            <!-- Receiver Panel -->
                                                            <div class="col-md-12">
                                                                <div class="card shadow-sm mb-4">
                                                                    <div class="card-header bg-success text-white">Receiver
                                                                        Details</div>
                                                                    <!-- RECEIVER DETAILS -->
                                                                    <div class="card-body">
                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-6">
                                                                                <label class="form-label text-dark">Receiver
                                                                                    Name <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="text" class="form-control"
                                                                                    name="receiverContactPerson" required>
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label class="form-label text-dark">Address
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="text" class="form-control"
                                                                                    name="receiverAddress" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-6">
                                                                                <label class="form-label text-dark">Town
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="text" class="form-control"
                                                                                    name="receiverTown" required>
                                                                            </div>
                                                                            {{-- <div class="form-group col-md-6">
                                        <label class="form-label text-dark">Contact Person <span
                                                class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" name="receiver_contact" required>
                                    </div> --}}
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-6">
                                                                                <label class="form-label text-dark">ID
                                                                                    Number <span
                                                                                        class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="text" class="form-control"
                                                                                    name="receiverIdNo" required>
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label
                                                                                    class="form-label text-dark">Telephone
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="text" class="form-control"
                                                                                    name="receiverPhone" required>
                                                                            </div>
                                                                        </div>
                                                                        {{-- <div class="form-group">
                                    <label class="form-label text-dark">Date
                                    </label>
                                    <input type="date" class="form-control datetime" name="receiver_datetime"
                                        required>
                                </div> --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Description of Goods -->
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-dark">Origin <span
                                                                        class="text-danger">*</span> </label>
                                                                <select name="origin" id=""
                                                                    class="form-control" required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($offices as $office)
                                                                        <option value="{{ $office->name }}">
                                                                            {{ $office->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-dark">Destination <span
                                                                        class="text-danger">*</span> </label>
                                                                <select name="destination" id=""
                                                                    class="form-control" required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($destinations as $destination)
                                                                        <option value="{{ $destination->destination }}">
                                                                            {{ $destination->destination }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Shipment Info Table -->
                                                        {{-- <div class="section-title"><b class="text-dark">
                        Shipment Information</b></div> --}}

                                                        <div class="table-responsive mt-3">
                                                            <table class="table table-bordered" id="shipmentTable">
                                                                <thead class="thead-success">
                                                                    <tr>
                                                                        <th>Item Name</th>
                                                                        <th>No. of Packages</th>
                                                                        <th>Weight (kg)</th>
                                                                        <th>Length (cm)</th>
                                                                        <th>Width (cm)</th>
                                                                        <th>Height (cm)</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><input type="text" class="form-control"
                                                                                name="item[]"></td>
                                                                        <td><input type="number" min="0"
                                                                                max="100" class="form-control"
                                                                                name="packages[]"></td>
                                                                        <td><input type="number" min="0"
                                                                                max="100" class="form-control"
                                                                                name="weight[]"></td>
                                                                        <td><input type="number" min="0"
                                                                                max="100" class="form-control"
                                                                                name="length[]"></td>
                                                                        <td><input type="number" min="0"
                                                                                max="100" class="form-control"
                                                                                name="width[]"></td>
                                                                        <td><input type="number" min="0"
                                                                                max="100" class="form-control"
                                                                                name="height[]"></td>
                                                                        <td><button type="button"
                                                                                class="btn btn-danger btn-sm remove-row">Remove</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <button type="button" class="btn btn-primary mb-3"
                                                            id="addRowBtn">Add Row</button>


                                                        <!-- Service Level -->
                                                        <div class="section-title"></div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-dark">Select Service <span
                                                                        class="text-danger">*</span> </label>
                                                                <select class="form-control" name="service" required>
                                                                    <option value="">-- Select Service --</option>
                                                                    <option value="standard">Standard</option>
                                                                    <option value="express">Express</option>
                                                                    <option value="overnight">Overnight</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="form-label text-dark">Cost <span
                                                                        class="text-danger">*</span>
                                                                </label>
                                                                <input type="number" min="0" class="form-control"
                                                                    name="cost" required>
                                                            </div>

                                                            <!-- Submit -->
                                                            <button type="submit" class="btn btn-success">Submit
                                                                Shipment</button>

                                                            {{-- <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                            value="DELETE">YES DELETE <i class="fas fa-trash"></i> </button> --}}
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
@endsection
