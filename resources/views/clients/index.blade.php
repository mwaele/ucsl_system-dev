@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <a href="/clients_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
                <h4 class="m-0 font-weight-bold text-success">All Clients Lists</h4>
                <a href="{{ route('clients.create') }}" class="btn btn-success"><i class="fas fa-plus-circle"></i>
                    Create New Client</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Telephone Number</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Physical Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Telephone Number</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Physical Address</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td> {{ $loop->iteration }}. </td>
                                <td> {{ $client->name }} </td>
                                <td> {{ $client->contact }} </td>
                                <td> {{ $client->email }} </td>
                                <td> {{ $client->type }} </td>
                                <td> {{ $client->address }} </td>
                                <td class="row pl-4">

                                    <button class="btn btn-sm btn-warning mr-1" data-toggle="modal"
                                        data-target="#editClientModal-{{ $client->id }}">
                                        Edit
                                    </button>
                                    {{-- <a href="{{ route('clients.show', $client->id) }}">
                                        <button class="btn btn-sm btn-warning mr-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </a> --}}
                                    {{-- <a href="/client_report/{{ $client->id }}">
                                        <button class="btn btn-sm btn-success mr-1" title="PDF Download">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    </a> --}}
                                    @if ($client->otp != $client->verified_otp)
                                        <button class="btn btn-info mr-1" data-target="#verify_otp-{{ $client->id }}"
                                            data-toggle="modal">
                                            Verify OTP
                                        </button>
                                    @endif
                                    @if ($client->otp === $client->verified_otp)
                                        <button class="btn btn-primary">
                                            OTP Verified
                                        </button>
                                    @endif
                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $client->id }}"><i
                                            class="fas fa-trash"></i></button> --}}
                                    <!-- Logout Modal-->
                                    {{-- <div class="modal fade" id="delete_floor-{{ $client->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $client->name }}?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('clients.destroy', ['client' => $client->id]) }}"
                                                        method = "POST">
                                                        @method('DELETE')
                                                        @csrf

                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                        value="DELETE">DELETE <i class="fas fa-trash"></i> </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="modal fade" id="verify_otp-{{ $client->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content">
                                                <form method="POST"
                                                    action="{{ route('clients_update.update_otp', $client->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info text-white">
                                                            <h5 class="modal-title"
                                                                id="editUserModalLabel-{{ $client->id }}">Verify OTP for:
                                                                {{ $client->name }}</h5>
                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body row">

                                                            <div class="form-group col-md-12">
                                                                <label>OTP</label>
                                                                <input name="verified_otp" type="number"
                                                                    oninput="if(this.value.length > 6) this.value = this.value.slice(0,6);"
                                                                    class="form-control" required>
                                                            </div>

                                                        </div>

                                                        <div
                                                            class="modal-footer d-flex justify-content-between align-items-center">
                                                            <button type="button" class="btn btn-warning"
                                                                data-dismiss="modal">Cancel X</button>
                                                            <button type="submit" class="btn btn-primary">Update
                                                                Client</button>
                                                        </div>

                                                    </div>
                                                </form>
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
        @foreach ($clients as $client)
            <div class="modal fade" id="editClientModal-{{ $client->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editClientModalLabel-{{ $client->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <form method="POST" action="{{ route('clients.update', $client->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title" id="editUserModalLabel-{{ $client->id }}">Edit Client Details:
                                    {{ $client->name }}</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body row">

                                <div class="form-group col-md-6">
                                    <label>Name</label>
                                    <input name="name" class="form-control" value="{{ $client->name }}" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input name="email" type="email" class="form-control" value="{{ $client->email }}"
                                        required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Phone Number</label>
                                    <input name="contact" class="form-control" value="{{ $client->contact }}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Type</label>
                                    <select name="type" class="form-control">
                                        <option value="on_account" {{ $client->type == 'on_account' ? 'selected' : '' }}>
                                            On
                                            Account</option>
                                        <option value="walkin" {{ $client->type == 'walkin' ? 'selected' : '' }}>Walkin
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Password</label>
                                    <input name="password" type="password" class="form-control"
                                        value="{{ $client->password }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Role</label>
                                    <select name="role" id="" class="form-control">
                                        <option value="">Select</option>
                                        <option value="client">Client</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="modal-footer d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel X</button>
                                <button type="submit" class="btn btn-primary">Update Client</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
