@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">Offices Users Lists</h6>

                <div>
                    <a href="/offices_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                        <i class="fas fa-download fa-sm text-white"></i> Generate Report
                    </a>

                    <button class="btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#createOffice">
                        <i class="fas fa-plus fa-sm text-white"></i> Create Front Office User
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
                            <th>Phone Number</th>
                            <th>Office Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Office Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($office_users as $office_user)
                            <tr>
                                <td> {{ $loop->iteration }}.</td>
                                <td> {{ $office_user->user->name }} </td>
                                <td> {{ $office_user->user->phone_number }} </td>
                                <td> {{ $office_user->office->name }} </td>
                                <td> {{ $office_user->user->email }} </td>
                                <td>
                                    @if ($office_user->status === 'active')
                                        <span class="badge badge-success p-2"> {{ strtoupper($office_user->status) }}
                                        </span>
                                    @else
                                        <span class="badge badge-danger p-2"> {{ strtoupper($office_user->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Modal-->
        <div class="modal fade" id="createOffice" tabindex="-1" role="dialog" aria-labelledby="CreateOfficeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="{{ route('office_users.store') }}" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="CreateOfficeModalLabel">Create Front Office User</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>User <span class="text-danger">*</span></label>
                                        <select name="user_id" id="user_id" class="form-control">
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Office Name <span class="text-danger">*</span></label>
                                        <select name="office_id" id="office_id" class="form-control">
                                            <option value="">Select Office</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                                            @endforeach
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
