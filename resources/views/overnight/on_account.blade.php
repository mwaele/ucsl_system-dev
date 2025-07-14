@extends('layouts.custom')

@section('content')
<div class="card">

    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User Accounts</h5>
            
            <div class="d-flex gap-2 ms-auto">
                <a href="/users_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
                    <i class="fas fa-download fa text-white"></i> Generate Report
                </a>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                    + Create User
                </button>
            </div>
        </div>
    </div>



    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-primary table-bordered table-striped table-hover" id="ucsl-table" width="100%"
                            cellspacing="0" style="font-size: 14px;">
                <thead>
                    <tr class="text-success">
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Client Type</th>
                        <th>Delivery Level</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : 'secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->office->name ?? 'Unassigned' }}</td>
                            <td>
                                <span class="badge badge-{{ $user->status == 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="d-flex pl-2">
                                <button class="btn btn-sm btn-info mr-1" data-toggle="modal" data-target="#editUserModal-{{ $user->id }}">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger mr-1" title="Delete Client Request"
                                    data-toggle="modal"
                                    data-target="#deleteUser-{{ $user->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <!-- Delete Modal-->
                                <div class="modal fade" id="deleteUser-{{ $user->id }}"
                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete {{ $user->name }}?
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                <form
                                                    action =" {{ route('user.destroy', $user->id) }}"
                                                    method = "POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        title="Delete" value="DELETE">YES DELETE <i
                                                            class="fas fa-trash"></i> </button>
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
