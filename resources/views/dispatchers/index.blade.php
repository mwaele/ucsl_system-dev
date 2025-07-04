@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <button data-toggle="modal" data-target="#add_clerk" class="btn btn-primary"><i class="fas fa-plus-circle"></i>
                    Add Dispatch Clerk</button>
                <h4 class="m-0 font-weight-bold text-success">Dispatch Clerks Lists</h4>


                <a href="/dispatch_clerks_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID Number</th>
                            <th>Phone Number</th>
                            <th>Office</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>ID Number</th>
                            <th>Phone Number</th>
                            <th>Office</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($dispatchers as $dispatcher)
                            <tr>
                                <td> {{ $dispatcher->name }} </td>
                                <td> {{ $dispatcher->id_no }} </td>
                                <td> {{ $dispatcher->phone_no }} </td>
                                <td> {{ optional($dispatcher->office)->station_name ?? 'No Station' }} </td>
                                <td class="row pl-4">
                                    <a href="{{ route('dispatchers.edit', $dispatcher->id) }}">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $dispatcher->id }}"><i
                                            class="fas fa-trash"></i></button>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="delete_floor-{{ $dispatcher->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $dispatcher->dispatcher }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('dispatchers.destroy', ['dispatcher' => $dispatcher->id]) }}"
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
                                    </div>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <!-- Modal -->
                <div class="modal fade" id="add_clerk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document"> <!-- Added modal-lg for wider layout -->
                        <div class="modal-content">
                            <div class="modal-header bg-success">

                                <h5 class="modal-title text-white" id="exampleModalLabel"> <strong>Add New Dispatch
                                        Clerk</strong>
                                </h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('dispatchers.store') }} " method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control">

                                        </div>
                                        <div class="col-md-6">
                                            <label for="id_no" class="form-label">ID Number</label>
                                            <input type="number" class="form-control" id="id_no" name="id_no">
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="mb-3 col-md-6">
                                            <label for="phone_no" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" name="phone_no" id="phone_no">
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="signature" class="form-label">Signature</label>
                                            <input type="file" name="signature" class="form-control">
                                        </div>

                                        <input type="hidden" name="office_id" value="{{ Auth::user()->station }}">
                                    </div>

                            </div>
                            <div class="modal-footer d-flex justify-content-between ">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close X</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
