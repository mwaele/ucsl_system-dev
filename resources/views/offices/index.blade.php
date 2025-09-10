@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">Offices Lists</h6>
                <a href="/offices_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>

                            <th>Front Office User</th>
                            <th>City</th>
                            <th>Mpesa Till</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Front Office User</th>
                            <th>City</th>
                            <th>Mpesa Till</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($offices as $office)
                            <tr>
                                <td> {{ $office->name }} </td>
                                <td>
                                    @php
                                        $activeUsers = $office->activeFrontOfficeUsers->pluck('user.name')->toArray();
                                    @endphp
                                    {{ implode(', ', $activeUsers) }}
                                </td>
                                <td> {{ $office->city }} </td>
                                <td> {{ $office->mpesaTill }} </td>
                                <td> {{ $office->status }} </td>
                                <td class="row pl-4">
                                    {{-- <a href="{{ route('offices.edit', $office->id) }}">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a> --}}
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#updateOffice-{{ $office->id }}"><i
                                            class="fas fa-edit">Edit</i></button>
                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $office->id }}"><i
                                            class="fas fa-trash"></i></button> --}}
                                    <!-- Logout Modal-->
                                    {{-- <div class="modal fade" id="delete_floor-{{ $office->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $office->regNo }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('offices.destroy', ['office' => $office->id]) }}"
                                                        method = "POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                            value="DELETE">YES DELETE <i class="fas fa-trash"></i> </button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                </td>
                            </tr>
                            <div class="modal fade" id="updateOffice-{{ $office->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="UpdateOfficeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="UpdateOfficeModalLabel">Update Office</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="  {{ route('offices.update', ['office' => $office->id]) }} "
                                                method="post">
                                                <div class="row">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="col-md-6">
                                                        <div class="form-group"><label>Front Office User <span
                                                                    class="text-danger">*</span></label>

                                                            <select name="user" class="form-control" required>
                                                                <option value="">Select User</option>
                                                                @foreach ($office->users as $user)
                                                                    <option value="{{ $user->id }}"
                                                                        @if ($office->officeUsers->contains('user_id', $user->id)) selected @endif>
                                                                        {{ $user->name }} - {{ $user->email }}
                                                                        ({{ $user->office->name ?? 'No Office' }})
                                                                    </option>
                                                                @endforeach
                                                            </select>


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"><label>Status <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="status" class="form-control" required>
                                                                <option value="">Select</option>
                                                                <option value="active">Active</option>
                                                                <option value="inactive">Inactive</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
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
@endsection
