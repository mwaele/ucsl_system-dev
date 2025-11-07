@extends('layouts.custom')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-danger">Motor Vehicles Lists</h6>

            <div>
                <!-- Create Vehicle Button -->
                <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#createVehicleModal">
                    <i class="fas fa-plus text-white"></i> Create Vehicle
                </button>

                <a href="/vehicles_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                    <i class="fas fa-download fa-sm text-white"></i> Generate Report
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reg No.</th>
                        <th>Type</th>
                        <th>Owned By</th>
                        <th>Driver</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Reg No.</th>
                        <th>Type</th>
                        <th>Owned By</th>
                        <th>Driver</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($vehicles as $vehicle)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $vehicle->regNo }}</td>
                            <td>{{ $vehicle->type }}</td>
                            <td>{{ $vehicle->ownedBy }}</td>
                            <td>{{ $vehicle->user->name }}</td>
                            <td>{{ \Illuminate\Support\Str::title($vehicle->status) }}</td>

                            <td class="row pl-4">

                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-info mr-1" data-toggle="modal"
                                    data-target="#editVehicleModal-{{ $vehicle->id }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <!-- Delete Button -->
                                <!-- <button class="btn btn-sm btn-danger" data-toggle="modal"
                                    data-target="#deleteVehicleModal-{{ $vehicle->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button> -->

                            </td>
                        </tr>

                        <!-- ✅ Edit Modal -->
                        <div class="modal fade" id="editVehicleModal-{{ $vehicle->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title">Edit Vehicle ({{ $vehicle->regNo }})</h5>
                                        <button class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            @include('vehicles.form', ['vehicle' => $vehicle, 'users' => $users, 'companies' => $companies])
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-info btn-sm">Update</button>
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- ✅ Delete Modal -->
                        <div class="modal fade" id="deleteVehicleModal-{{ $vehicle->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete <b>{{ $vehicle->regNo }}</b>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ✅ Create Modal -->
<div class="modal fade" id="createVehicleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Create Vehicle</h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('vehicles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('vehicles.form', ['vehicle' => null, 'users' => $users, 'companies' => $companies])
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

