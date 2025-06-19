@extends('layouts.custom')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5>User Accounts</h5>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
            + Create User
        </button>
    </div>

    <table class="table table-bordered table-striped table-hover" id="ucsl-table" width="100%"
                    cellspacing="0" style="font-size: 14px;">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Station</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone_number ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : 'secondary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->station ?? 'Unassigned' }}</td>
                    <td>
                        <span class="badge badge-{{ $user->status == 'active' ? 'success' : 'warning' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d H:i') }}</td>
                    <td>
                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editUserModal-{{ $user->id }}">
                            Edit
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @foreach($users as $user)
        <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel-{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editUserModalLabel-{{ $user->id }}">Edit User: {{ $user->name }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>

                <div class="modal-body row">

                <div class="form-group col-md-6">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="form-group col-md-6">
                    <label>Phone Number</label>
                    <input name="phone_number" class="form-control" value="{{ $user->phone_number }}">
                </div>

                <div class="form-group col-md-6">
                    <label>Station</label>
                    <select name="station" class="form-control" required>
                        <option value="">-- Select Station --</option>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}" {{ old('station') == $station ? 'selected' : '' }}>
                                {{ $station->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="driver" {{ $user->role == 'driver' ? 'selected' : '' }}>Driver</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update User</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>

            </div>
            </form>
        </div>
        </div>
    @endforeach
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form method="POST" action="{{ route('users.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body row">

          <div class="form-group col-md-6">
              <label>Name</label>
              <input name="name" class="form-control" required>
          </div>

          <div class="form-group col-md-6">
              <label>Email</label>
              <input name="email" type="email" class="form-control" required>
          </div>

          <div class="form-group col-md-6">
              <label>Phone Number</label>
              <input name="phone_number" class="form-control">
          </div>

          <div class="form-group col-md-6">
              <label>Station</label>
              <select name="station" class="form-control" required>
                    <option value="">-- Select Station --</option>
                    @foreach ($stations as $station)
                        <option value="{{ $station->id }}">
                            {{ $station->name }}
                        </option>
                    @endforeach
                </select>
          </div>

          <div class="form-group col-md-6">
              <label>Role</label>
              <select name="role" class="form-control">
                  <option value="user" selected>User</option>
                  <option value="admin">Admin</option>
                  <option value="driver">Driver</option>
              </select>
          </div>

          <div class="form-group col-md-6">
              <label>Status</label>
              <select name="status" class="form-control">
                  <option value="active">Active</option>
                  <option value="inactive" selected>Inactive</option>
              </select>
          </div>

          <div class="form-group col-md-6">
              <label>Password</label>
              <input name="password" type="password" class="form-control" required>
          </div>

          <div class="form-group col-md-6">
              <label>Confirm Password</label>
              <input name="password_confirmation" type="password" class="form-control" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Create User</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
