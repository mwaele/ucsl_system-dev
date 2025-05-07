@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">All Loading Sheets</h6>
                <button type="button"class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#createLoadingSheet"><i
                    class="fas fa-plus fa-sm text-white"></i> Create Loading Sheet</button>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="createLoadingSheet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for wider layout -->
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create Loading Sheet</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                        <h6 class="fw-bold text-black">Loading Sheet Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                            <label for="origin" class="form-label">Origin</label>
                            <input type="text" class="form-control" id="origin" placeholder="Origin">
                            </div>
                            <div class="col-md-6">
                            <label for="destination" class="form-label">Destination</label>
                            <input type="text" class="form-control" id="destination" placeholder="Destination">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="vehicleNo" class="form-label">Vehicle Number</label>
                            <input type="vehicleNo" class="form-control" id="vehicleNo" placeholder="e.g KCT 458T">
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Driver</label>
                            <select class="form-select" id="country">
                                <option value="">Select Driver</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->name }} ({{ $driver->status }})</option>
                                @endforeach<option selected>United States</option>
                            <option>Canada</option>
                            <option>United Kingdom</option>
                            <!-- More countries if needed -->
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="street" class="form-label">Street address</label>
                            <input type="text" class="form-control" id="street" placeholder="1234 Main St">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" placeholder="City">
                            </div>
                            <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State / Province</label>
                            <input type="text" class="form-control" id="state" placeholder="State / Province">
                            </div>
                            <div class="col-md-4 mb-3">
                            <label for="zip" class="form-label">ZIP / Postal code</label>
                            <input type="text" class="form-control" id="zip" placeholder="ZIP / Postal code">
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Dispatch date</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Vehicle Number</th>
                            <th>Transporter</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Dispatch date</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Vehicle Number</th>
                            <th>Transporter</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($sheets as $sheet)
                            <tr>
                                <td> {{ $sheet->dispatchDate }} </td>
                                <td> {{ $sheet->origin }} </td>
                                <td> {{ $sheet->destination }} </td>
                                <td> {{ $sheet->vehicleNo }} </td>
                                <td> {{ $sheet->transporter }} </td>
                                <td class="row pl-4">
                                    <a href="">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                    <a href="">
                                        <button class="btn btn-sm btn-warning mr-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </a>
                                    <a href="">
                                        <button class="btn btn-sm btn-success mr-1" title="PDF Download">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target=""><i
                                            class="fas fa-trash"></i></button>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" "
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
