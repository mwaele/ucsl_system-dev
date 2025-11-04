@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">Zones Lists</h6>
                <div>
                    <!-- Create Button -->
                    <button class="btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#createZoneModal">
                        <i class="fas fa-plus fa-sm text-white"></i> Create Zone
                    </button>

                    <a href="/zones_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
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
                            <th>Zone</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Zone</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($zones as $zone)
                            <tr>
                                <td>{{ $zone->zone_name }}</td>
                                <td>{{ $zone->description }}</td>
                                <td class="row pl-4">
                                    <!-- Edit Button (opens modal) -->
                                    <button class="btn btn-sm btn-info mr-1" data-toggle="modal"
                                        data-target="#editZoneModal-{{ $zone->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#deleteZoneModal-{{ $zone->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editZoneModal-{{ $zone->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="editZoneModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('zones.update', $zone->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-info">Edit Zone</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="zone_name">Zone Name</label>
                                                            <input type="text" class="form-control" name="zone_name"
                                                                value="{{ $zone->zone_name }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="description">Description</label>
                                                            <textarea class="form-control" name="description" rows="3" required>{{ $zone->description }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-info">Save Changes</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteZoneModal-{{ $zone->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete <strong>{{ $zone->zone_name }}</strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('zones.destroy', ['zone' => $zone->id]) }}"
                                                        method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary btn-sm"
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

        <!-- Create Zone Modal -->
        <div class="modal fade" id="createZoneModal" tabindex="-1" role="dialog" aria-labelledby="createZoneModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('zones.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title text-success">Create New Zone</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="zone_name">Zone Name</label>
                                <input type="text" class="form-control" name="zone_name" placeholder="Enter Zone Name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Enter Description"
                                    required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Create Zone</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
