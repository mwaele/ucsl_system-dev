@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">Offices Lists</h6>

                <div>
                    <a href="/offices_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                        <i class="fas fa-download fa-sm text-white"></i> Generate Report
                    </a>

                    <button class="btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#createOffice">
                        <i class="fas fa-plus fa-sm text-white"></i> Create Office
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Office Code</th>
                            <th>Office Type</th>
                            <th>City</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Office Code</th>
                            <th>Office Type</th>
                            <th>City</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($offices as $office)
                            <tr>
                                <td> {{ $loop->iteration }}.</td>
                                <td> {{ $office->name }} </td>
                                <td> {{ $office->office_code }} </td>
                                <td> {{ \Illuminate\Support\Str::title($office->type) }} </td>
                                <td> {{ $office->city }} </td>
                                <td> {{ \Illuminate\Support\Str::title($office->status) }} </td>
                                <td class="row pl-4">
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-sm btn-info mr-1" data-toggle="modal"
                                        data-target="#updateOffice-{{ $office->id }}" data-id="{{ $office->id }}"><i
                                            class="fas fa-edit"></i> Edit</button>

                                    <!-- Delete Button -->
                                    <!-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $office->id }}"><i
                                            class="fas fa-trash"></i> Delete </button> -->

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="updateOffice-{{ $office->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="UpdateOfficeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <form action="  {{ route('offices.update', ['office' => $office->id]) }} "
                                                method="post">
                                                @method('PUT')
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="UpdateOfficeModalLabel">Update Office
                                                        </h5>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Office Name <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" value="{{ $office->name }}"
                                                                        name="office_name" class="form-control" required>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Office Code <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" value="{{ $office->shortName }}"
                                                                        name="office_code" class="form-control" required
                                                                        maxlength="10">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Office type <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="type" class="form-control" required>
                                                                        <option value="">Select</option>
                                                                        <option value="staff"
                                                                            {{ $office->type == 'staff' ? 'selected' : '' }}>
                                                                            Staff</option>
                                                                        <option value="agent"
                                                                            {{ $office->type == 'agent' ? 'selected' : '' }}>
                                                                            Agent</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Status <span class="text-danger">*</span></label>
                                                                    <select name="status" class="form-control" required>
                                                                        <option value="">Select</option>
                                                                        <option value="active"
                                                                            {{ $office->status == 'active' ? 'selected' : '' }}>
                                                                            Active</option>
                                                                        <option value="inactive"
                                                                            {{ $office->status == 'inactive' ? 'selected' : '' }}>
                                                                            Inactive</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Town <span class="text-danger">*</span></label>
                                                                    <select name="city" class="form-control" required>
                                                                        <option value="">Select</option>

                                                                        <option value="Nairobi"
                                                                            {{ $office->city == 'Nairobi' ? 'selected' : '' }}>
                                                                            Nairobi</option>

                                                                        @foreach ($rates as $rate)
                                                                            <option value="{{ $rate->destination }}"
                                                                                {{ $office->city == $rate->destination ? 'selected' : '' }}>
                                                                                {{ $rate->destination }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Country <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="country" class="form-control" required>
                                                                        <option value="">Select</option>
                                                                        <option value="Kenya"
                                                                            {{ $office->country == 'Kenya' ? 'selected' : '' }}>
                                                                            Kenya</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Delete Modal-->
                                    {{-- <div class="modal fade" id="delete_floor-{{ $office->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete
                                                        <strong>{{ $office->name }}</strong>?.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('offices.destroy', ['office' => $office->id]) }}"
                                                        method = "POST">
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
                                    </div> --}}
                                </td>
                            </tr>
            </div>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal-->
    <div class="modal fade" id="createOffice" tabindex="-1" role="dialog" aria-labelledby="CreateOfficeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('offices.store') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="CreateOfficeModalLabel">Create Office</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Office Name <span class="text-danger">*</span></label>
                                    <input type="text" name="office_name" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Office Code <span class="text-danger">*</span></label>
                                    <input type="text" name="office_code" class="form-control" required
                                        maxlength="10">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Office type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="staff">Staff</option>
                                        <option value="agent">Agent</option>
                                        <option value="caravan">Caravan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="active" selected>Active</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Town <span class="text-danger">*</span></label>
                                    <select name="city" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="Nairobi">Nairobi</option>
                                        @foreach ($rates as $rate)
                                            <option value="{{ $rate->destination }}">{{ $rate->destination }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Country <span class="text-danger">*</span></label>
                                    <select name="country" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="Kenya" selected>Kenya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Create Office</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
