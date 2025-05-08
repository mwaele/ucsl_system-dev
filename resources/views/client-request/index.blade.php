@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">All Client Requests</h6>
                <button type="button"class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                    data-target="#createLoadingSheet"><i class="fas fa-plus fa-sm text-white"></i> Create Client
                    Request</button>
            </div>

            <!-- Modal -->
            <form action="{{ route('clientRequests.store') }}" method="POST">
                @csrf
                <div class="modal fade" id="createLoadingSheet" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document"> <!-- Added modal-lg for wider layout -->
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">Create Client Request</h5>
                                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <p class="text-muted">Fill in the client details.</p>

                                    <div class="row mb-3">
                                        <div class="col-md-6">

                                            <label for="clientId" class="form-label">Client</label>
                                            <select class="form-control" id="clientId" name="clientId">
                                                <option value="">Select Client</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}
                                                        ({{ $client->accountNo }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="collectionLocation" class="form-label">Pickup Location</label>
                                            <input type="text" class="form-control" name="collectionLocation"
                                                id="collectionLocation">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="parcelDetails" class="form-label fw-medium text-dark">Parcel
                                            Details</label>
                                        <textarea class="form-control" id="parcelDetails" name="parcelDetails" rows="3"
                                            placeholder="Fill in the description of goods."></textarea>
                                    </div>

                                    <p class="text-muted">Fill in the Rider details.</p>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="userId" class="form-label">Rider</label>
                                            <select class="form-control" id="userId" name="userId">
                                                <option value="">Select Rider</option>
                                                @foreach ($drivers as $driver)
                                                    <option value="{{ $driver->id }}">{{ $driver->name }}
                                                        ({{ $driver->station }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="vehicleId" class="form-label">Vehicle</label>
                                            <select class="form-control" id="vehicleId" name="vehicleId"
                                                aria-label="Default select example">
                                                <option value="">Select Vehicle</option>
                                                @foreach ($vehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}">{{ $vehicle->regNo }}
                                                        ({{ $vehicle->status }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="requestId" class="form-label">Request ID</label>
                                            <input type="text" value="{{ $request_id }}" name="requestId"
                                                class="form-control" id="request_id" readonly>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="datetime">Date of Request</label>
                                            <div class="input-group">
                                                <input type="text" name="dateRequested" id="datetime" class="form-control">
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Pick-up Location</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Description of goods</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Pick-up Location</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Description of goods</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($client_requests as $request)
                            <tr>
                                <td> {{ $request->requestId }} </td>
                                <td> {{ $request->clientId }} </td>
                                <td> {{ $request->collectionLocation }} </td>
                                <td> {{ $request->dateRequested }} </td>
                                <td> {{ $request->userId }} </td>
                                <td> {{ $request->vehicleId }} </td>
                                <td> {{ $request->parcelDetails }} </td>
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
                                        data-target=""><i class="fas fa-trash"></i></button>
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
@endsection
