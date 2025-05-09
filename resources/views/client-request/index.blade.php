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
                                                    <option value="{{ $driver->id }}">{{ $driver->name }} ({{ $driver->station }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="vehicle" class="form-label">Vehicle</label>
                                            <input type="text" id="vehicle" class="form-control" name="vehicle_display" placeholder="Select rider to populate" readonly>
                                            <input type="hidden" id="vehicleId" name="vehicleId">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="requestId" class="form-label">Request ID</label>
                                            <input type="text" value="{{ $request_id }}" name="requestId"
                                                class="form-control" id="request_id" readonly>
                                        </div>
                                        <script>
                                            const vehicleMap = {
                                                @foreach ($vehicles as $vehicle)
                                                    "{{ $vehicle->user_id }}": {
                                                        id: "{{ $vehicle->id }}",
                                                        regNo: "{{ $vehicle->regNo }}",
                                                        status: "{{ $vehicle->status }}"
                                                    },
                                                @endforeach
                                            };

                                            document.addEventListener('DOMContentLoaded', function () {
                                                const userSelect = document.getElementById('userId');
                                                const vehicleInput = document.getElementById('vehicle');
                                                const vehicleIdInput = document.getElementById('vehicleId');

                                                userSelect.addEventListener('change', function () {
                                                    const selectedUserId = this.value;
                                                    const vehicle = vehicleMap[selectedUserId];

                                                    if (vehicle) {
                                                        vehicleInput.value = `${vehicle.regNo} (${vehicle.status})`;
                                                        vehicleIdInput.value = vehicle.id;
                                                    } else {
                                                        vehicleInput.value = '';
                                                        vehicleIdInput.value = '';
                                                    }
                                                });
                                            });
                                        </script>

                                        <div class="col-md-4 mb-3">
                                            <label for="datetime">Date of Request</label>
                                            <div class="input-group">
                                                <input type="text" name="dateRequested" id="datetime" class="form-control" placeholder="Select date & time">
                                                <div class="input-group-append" >
                                                    <span class="input-group-text">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
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
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Pick-up Location</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Description of goods</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Request ID</th>
                            <th>Client</th>
                            <th>Pick-up Location</th>
                            <th>Date Requested</th>
                            <th>Rider</th>
                            <th>Vehicle</th>
                            <th>Description of goods</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($client_requests as $request)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $request->requestId }} </td>
                                <td> {{ $request->client->name }} </td>
                                <td> {{ $request->collectionLocation }} </td>
                                <td> {{ $request->dateRequested }} </td>
                                <td> {{ $request->user->name }} </td>
                                <td> {{ $request->vehicle->regNo }} </td>
                                <td> {{ $request->parcelDetails }} </td>
                                <td> <p class="badge
                                            @if ($request->status == 'pending collection')
                                                bg-warning
                                            @elseif ($request->status == 'Collected')
                                                bg-success
                                            @elseif ($request->status == 'Delayed')
                                                bg-danger
                                            @endif
                                            fs-5"
                                           >
                                        {{ $request->status }}
                                    </p>
                                </td>
                                <td class="d-flex pl-4">
                                    <button class="btn btn-sm btn-info mr-1" title="Edit" data-toggle="modal" data-target="#editModal-{{ $request->requestId }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal-{{ $request->requestId }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel-{{ $request->requestId }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <form action="{{ route('clientRequests.update', $request->requestId) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel-{{ $request->requestId }}">Edit Client Request</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form Fields -->
                                                    <div class="form-group">
                                                        <label>Request ID</label>
                                                        <input type="text" name="requestId" class="form-control" value="{{ $request->requestId }}" readonly>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Client</label>
                                                        <select name="clientId" class="form-control">
                                                            @foreach($clients as $client)
                                                                <option value="{{ $client->id }}" {{ $client->id == $request->clientId ? 'selected' : '' }}>{{ $client->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Pick-up Location</label>
                                                        <input type="text" name="collectionLocation" class="form-control" value="{{ $request->collectionLocation }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Date Requested</label>
                                                        <input type="datetime-local" name="dateRequested" class="form-control" value="{{ \Carbon\Carbon::parse($request->dateRequested)->format('Y-m-d\TH:i') }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Rider</label>
                                                        <select name="userId" class="form-control">
                                                            @foreach($drivers as $driver)
                                                                <option value="{{ $driver->id }}" {{ $driver->id == $request->userId ? 'selected' : '' }}>{{ $driver->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Vehicle</label>
                                                        <select name="vehicleId" class="form-control">
                                                            @foreach($vehicles as $vehicle)
                                                                <option value="{{ $vehicle->id }}" {{ $vehicle->id == $request->vehicleId ? 'selected' : '' }}>{{ $vehicle->regNo }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Description of Goods</label>
                                                        <textarea name="parcelDetails" class="form-control">{{ $request->parcelDetails }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <button class="btn btn-sm btn-danger mr-1" title="Delete"  data-toggle="modal" data-target="#{{ $request->requestId }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <!-- Delete Modal-->
                                    <div class="modal fade" id="{{ $request->requestId }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $request->requestId }}?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                    <form
                                                        action =" {{ route('clientRequests.destroy', $request->requestId) }}"
                                                        method = "POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                            value="DELETE">YES DELETE <i class="fas fa-trash"></i> </button>
                                                    </form>
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
