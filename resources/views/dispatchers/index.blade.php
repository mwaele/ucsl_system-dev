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
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
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
                                <td> {{ $dispatcher->office->name ?? 'No Station' }} </td>
                                <td class="row pl-4">

                                    <button type="button" class="btn btn-warning btn-sm editBtn" data-toggle="modal"
                                        data-target="#editModal" data-id="{{ $dispatcher->id }}"
                                        data-name="{{ $dispatcher->name }}" data-id-no="{{ $dispatcher->id_no }}"
                                        data-phone-no="{{ $dispatcher->phone_no }}" data-email="{{ $dispatcher->email }}"
                                        data-type="{{ $dispatcher->type }}" data-remarks="{{ $dispatcher->remarks }}"
                                        data-office-id="{{ $dispatcher->office_id }}">
                                        Edit
                                    </button>

                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $dispatcher->id }}"><i
                                            class="fas fa-trash"></i></button> --}}
                                    <!-- Logout Modal-->


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
                                <form id="editForm" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" id="edit_id">

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name" id="name" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="idNo" class="form-label">ID Number</label>
                                            <input type="number" class="form-control" id="idNo" name="id_no">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="phoneNo" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" name="phone_no" id="phoneNo">
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Office <span class="text-danger">*</span></label>
                                                <select id="office_id" name="office_id" class="form-control" required>
                                                    @foreach ($offices as $office)
                                                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span id="office_name_feedback"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" id="email" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="type" class="form-label">Type</label>
                                            <input type="text" name="type" id="type" class="form-control">
                                        </div>

                                        <div class="col-md-12">
                                            <label for="remarks" class="form-label">Remarks</label>
                                            <textarea name="remarks" id="remarks" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close X</button>
                                <button type="submit" form="editForm" class="btn btn-primary">Update Changes</button>
                            </div>

                            <!-- Script -->
                            <script>
                                $(document).ready(function() {
                                    $('#editModal').on('show.bs.modal', function(event) {
                                        var button = $(event.relatedTarget); // Button that triggered the modal

                                        // Extract data attributes
                                        var id = button.data('id');
                                        var name = button.data('name');
                                        var idNo = button.data('id-no');
                                        var phoneNo = button.data('phone-no');
                                        var email = button.data('email');
                                        var type = button.data('type');
                                        var remarks = button.data('remarks');
                                        var office_id = button.data('office-id');

                                        // Fill modal fields
                                        var modal = $(this);
                                        modal.find('#edit_id').val(id);
                                        modal.find('#name').val(name);
                                        modal.find('#idNo').val(idNo);
                                        modal.find('#phoneNo').val(phoneNo);
                                        modal.find('#email').val(email);
                                        modal.find('#type').val(type);
                                        modal.find('#remarks').val(remarks);
                                        modal.find('#office_id').val(office_id);

                                        // Set the form action URL dynamically
                                        modal.find('#editForm').attr('action', '/upates_dispatchers/' + id);
                                    });
                                });
                            </script>


                            <div class="modal-footer d-flex justify-content-between ">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close X</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document"> <!-- Added modal-lg for wider layout -->
                        <div class="modal-content">
                            <div class="modal-header bg-success">

                                <h5 class="modal-title text-white" id="exampleModalLabel"> <strong>Edit Dispatch
                                        Clerk</strong>
                                </h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id ="editForm" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" id="edit_id">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                readonly>

                                        </div>
                                        <div class="col-md-6">
                                            <label for="id_no" class="form-label">ID Number</label>
                                            <input type="number" class="form-control" id="idNo" name="id_no">
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="mb-3 col-md-6">
                                            <label for="phone_no" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" name="phone_no" id="phoneNo">
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="signature" class="form-label">Signature</label>
                                            <input type="file" name="signature" id="signature" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Office <span class="text-danger">*</span></label>
                                                <select id="office_id" name="office_id" class="form-control"
                                                    required="">
                                                    @foreach ($offices as $office)
                                                        <option value="{{ $office->id }}">{{ $office->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span id="office_name_feedback"></span> <!-- Correct placement -->
                                            </div>
                                        </div>

                                        {{-- <input type="hidden" name="office_id" value="{{ Auth::user()->station }}"> --}}
                                    </div>

                            </div>
                            <div class="modal-footer d-flex justify-content-between ">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close X</button>
                                <button type="submit" class="btn btn-primary">Update Changes</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ================= SCRIPT ================= -->
                <!-- Script -->
                <script>
                    $(document).ready(function() {
                        $('#editModal').on('show.bs.modal', function(event) {
                            var button = $(event.relatedTarget); // Button that triggered the modal

                            // Extract data attributes
                            var id = button.data('id');
                            var name = button.data('name');
                            var idNo = button.data('id-no');
                            var phoneNo = button.data('phone-no');
                            var email = button.data('email');
                            var type = button.data('type');
                            var remarks = button.data('remarks');
                            var office_id = button.data('office-id');

                            // Fill modal fields
                            var modal = $(this);
                            modal.find('#edit_id').val(id);
                            modal.find('#name').val(name);
                            modal.find('#idNo').val(idNo);
                            modal.find('#phoneNo').val(phoneNo);
                            modal.find('#email').val(email);
                            modal.find('#type').val(type);
                            modal.find('#remarks').val(remarks);
                            modal.find('#office_id').val(office_id);

                            // Set the form action URL dynamically
                            modal.find('#editForm').attr('action', '/dispatchers/' + id);
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
