@extends('layouts.custom')

@section('content')
<div class="card">

    <div class="card-header py-3">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h4 class="m-0 font-weight-bold text-success">Transporters List</h4>

            <div>
                <a href="/transporters_report" class="btn btn-sm btn-danger shadow-sm">
                    <i class="fas fa-download fa-sm text-white"></i> Generate Report
                </a>

                <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#createTransporterModal">
                    <i class="fas fa-plus text-white"></i> Create Transporter
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered text-primary" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Account</th>
                        <th>Reg. Details</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($transporters as $transporter)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transporter->name }}</td>
                            <td>{{ $transporter->phone_no }}</td>
                            <td>{{ $transporter->email }}</td>
                            <td>{{ $transporter->account_no }}</td>
                            <td>{{ $transporter->reg_details }}</td>
                            <td>
                                {{ $transporter->transporter_type == 'self' ? 'Self' : 'Third Party' }}
                            </td>

                            <td class="d-flex">

                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-info mr-1" data-toggle="modal"
                                    data-target="#editTransporterModal-{{ $transporter->id }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <!-- Trucks Button -->
                                <a href="transporter/trucks/{{ $transporter->id }}" class="btn btn-sm btn-primary mr-1">
                                    <i class="fas fa-truck"></i> Trucks
                                </a>

                                <!-- Delete Button -->
                                <!-- <button class="btn btn-sm btn-danger" data-toggle="modal"
                                    data-target="#deleteTransporterModal-{{ $transporter->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button> -->

                            </td>
                        </tr>

                        <!-- ✅ EDIT MODAL -->
                        <div class="modal fade" id="editTransporterModal-{{ $transporter->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title">Edit Transporter</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <form action="{{ route('transporters.update', $transporter->id) }}" method="POST" enctype="multipart/form-data">
                                        @method('PUT')
                                        <div class="modal-body">
                                            @include('transporters.form', [
                                                'transporter' => $transporter,
                                                'account_no' => $transporter->account_no
                                            ])
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-save"></i> Update</button>
                                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                        </div>
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- ✅ DELETE MODAL -->
                        <div class="modal fade" id="deleteTransporterModal-{{ $transporter->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-body">
                                        <p>Are you sure you want to delete <b>{{ $transporter->name }}</b>?</p>
                                    </div>

                                    <div class="modal-footer">
                                        <form action="{{ route('transporters.destroy', $transporter->id) }}" method="POST">
                                            @method('DELETE') @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Yes, Delete <i class="fas fa-trash"></i></button>
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

<!-- ✅ CREATE MODAL -->
<div class="modal fade" id="createTransporterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Create Transporter</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('transporters.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @include('transporters.form', [
                        'transporter' => null,
                        'account_no' => $account_no
                    ])
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Save</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                </div>
                @csrf
            </form>

        </div>
    </div>
</div>

@endsection
