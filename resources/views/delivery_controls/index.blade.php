@extends('layouts.custom')

@section('content')
    <div class=" mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h4>Delivery Controls</h4>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">+ Add New</button>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}

        <table class="table table-bordered table-striped" id="dataTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Control ID</th>
                    <th>Details</th>
                    <th>Time</th>
                    <th>Route</th>
                    <th>Days</th>
                    <th>Months</th>
                    <th>Years</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($controls as $ctrl)
                    <tr>
                        <td>{{ $ctrl->id }}</td>
                        <td>{{ $ctrl->control_id }}</td>
                        <td>{{ $ctrl->details }}</td>
                        <td>{{ $ctrl->ctr_time }}</td>
                        <td>{{ $ctrl->route_id }}</td>
                        <td>{{ $ctrl->ctr_days }}</td>
                        <td>{{ $ctrl->ctr_months }}</td>
                        <td>{{ $ctrl->ctr_years }}</td>
                        <td>
                            <button class="btn btn-sm btn-info" data-toggle="modal"
                                data-target="#editModal{{ $ctrl->id }}">Edit</button>

                            <form action="{{ route('delivery_controls.destroy', $ctrl->id) }}" method="POST"
                                class="d-inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Are you sure?')"
                                    class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editModal{{ $ctrl->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('delivery_controls.update', $ctrl->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title">Edit Record</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- <div class="mb-2">
                                            <label>Control ID</label>
                                            <input type="text" name="control_id" class="form-control"
                                                value="{{ $ctrl->control_id }}" required>
                                        </div> --}}
                                        <div class="mb-2">
                                            <label>Details</label>
                                            <input type="text" name="details" class="form-control"
                                                value="{{ $ctrl->details }}">
                                        </div>
                                        <div class="mb-2">
                                            <label>CTR Time</label>
                                            <input type="text" name="ctr_time" class="form-control"
                                                value="{{ $ctrl->ctr_time }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Route ID</label>
                                            <input type="number" name="route_id" class="form-control"
                                                value="{{ $ctrl->route_id }}">
                                        </div>
                                        <div class="mb-2">
                                            <label>Days</label>
                                            <input type="text" name="ctr_days" class="form-control"
                                                value="{{ $ctrl->ctr_days }}">
                                        </div>
                                        <div class="mb-2">
                                            <label>Months</label>
                                            <input type="text" name="ctr_months" class="form-control"
                                                value="{{ $ctrl->ctr_months }}">
                                        </div>
                                        <div class="mb-2">
                                            <label>Years</label>
                                            <input type="text" name="ctr_years" class="form-control"
                                                value="{{ $ctrl->ctr_years }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('delivery_controls.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Delivery Control</h5>
                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- <div class="mb-2">
                            <label>Control ID</label>
                            <input type="text" name="control_id" class="form-control" required>
                        </div> --}}
                        <div class="mb-2">
                            <label>Details</label>
                            <input type="text" name="details" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>CTR Time</label>
                            <input type="text" name="ctr_time" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Route ID</label>
                            <input type="number" name="route_id" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Days</label>
                            <input type="text" name="ctr_days" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Months</label>
                            <input type="text" name="ctr_months" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Years</label>
                            <input type="text" name="ctr_years" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
