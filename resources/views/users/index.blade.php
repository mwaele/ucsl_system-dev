@extends('layouts.custom')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>User Accounts</h2>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
            + Create User
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped table-hover">
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
                </tr>
            @endforeach
        </tbody>
    </table>
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
              <input name="station" class="form-control">
          </div>

          <div class="form-group col-md-6">
              <label>Role</label>
              <select name="role" class="form-control">
                  <option value="user" selected>User</option>
                  <option value="admin">Admin</option>
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
