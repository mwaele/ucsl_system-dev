@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <button data-toggle="modal" data-target="#add_clerk" class="btn btn-primary"><i class="fas fa-plus-circle"></i>
                    Add Sales / Marketing Person</button>
                <h4 class="m-0 font-weight-bold text-success">Sales and Marketing Lists</h4>


                <a href="/sales_person_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
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
                        @foreach ($sales_person as $sales_person)
                            <tr>
                                <td> {{ $sales_person->name }} </td>
                                <td> {{ $sales_person->id_no }} </td>
                                <td> {{ $sales_person->phone_number }} </td>
                                <td> {{ $sales_person->office->name ?? 'No Station' }} </td>
                                <td class="row pl-4">
                                    {{-- <a href="{{ route('sales_person.edit', $sales_person->id) }}">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a> --}}
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-info btn-sm editBtn" data-toggle="modal"
                                        data-target="#editModal" data-id="{{ $sales_person->id }}"
                                        data-name="{{ $sales_person->name }}" data-id-no="{{ $sales_person->id_no }}"
                                        data-phone-number="{{ $sales_person->phone_number }}"
                                        data-phone-number="{{ $sales_person->phone_number }}">
                                        Edit
                                    </button>
                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $sales_person->id }}"><i
                                            class="fas fa-trash"></i></button> --}}
                                    <!-- Logout Modal-->
                                    {{-- <div class="modal fade" id="delete_floor-{{ $sales_person->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $sales_person->dispatcher }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('sales_person.destroy', ['sales_person' => $sales_person->id]) }}"
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
                        @endforeach

                    </tbody>
                </table>
                <!-- Create Sales Person Modal -->
                <div class="modal fade" id="add_clerk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document"> <!-- Added modal-lg for wider layout -->
                        <div class="modal-content">
                            <div class="modal-header bg-success">

                                <h5 class="modal-title text-white" id="exampleModalLabel"> <strong>Add New Sales / Marketing
                                        Person</strong>
                                </h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('sales_person.store') }} " method="post">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control">

                                        </div>
                                        <div class="col-md-6">
                                            <label for="id_no" class="form-label">ID Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="id_no" name="id_no">
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="mb-3 col-md-6">
                                            <label for="phone_no" class="form-label">Phone Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="phone_number"
                                                id="phone_number">
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="signature" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Office <span class="text-danger">*</span></label>
                                                <select id="office" name="office_id" class="form-control" required="">
                                                    @foreach ($offices as $office)
                                                        <option value="{{ $office->id }}">{{ $office->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span id="office_name_feedback"></span> <!-- Correct placement -->
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Type <span class="text-danger">*</span></label>
                                                <select id="office" name="type" class="form-control" required="">
                                                    <option value="">Select
                                                    </option>
                                                    <option value="Sales">Sales
                                                    </option>
                                                    <option value="Marketing">Marketing
                                                    </option>
                                                    <option value="Both">Both
                                                    </option>

                                                </select>
                                                <span id="office_name_feedback"></span> <!-- Correct placement -->
                                            </div>
                                        </div>

                                        {{-- <input type="hidden" name="office_id" value="{{ Auth::user()->station }}"> --}}
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Remarks ("Indicate agreements if any") <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="remarks" id="" rows="10" class="form-control"></textarea>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between ">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close
                                            X</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Sales Person Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document"> <!-- Added modal-lg for wider layout -->
                        <div class="modal-content">
                            <div class="modal-header bg-success">

                                <h5 class="modal-title text-white" id="exampleModalLabel"> <strong>Edit Sales /
                                        Marketing
                                        Person</strong>
                                </h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('sales_person.store') }} " method="post">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                readonly>

                                        </div>
                                        <div class="col-md-6">
                                            <label for="id_no" class="form-label">ID Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="idNo" name="id_no">
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="mb-3 col-md-6">
                                            <label for="phone_no" class="form-label">Phone Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="phone_number"
                                                id="phoneNumber">
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="signature" class="form-label">Email</label>
                                            <input type="email" name="email" id="email" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Office <span class="text-danger">*</span></label>
                                                <select name="office_id" class="form-control" required=""
                                                    id="office_id">
                                                    @foreach ($offices as $office)
                                                        <option value="{{ $office->id }}">{{ $office->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span id="office_name_feedback"></span> <!-- Correct placement -->
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Type <span class="text-danger">*</span></label>
                                                <select id="type" name="type" class="form-control"
                                                    required="">
                                                    <option value="">Select
                                                    </option>
                                                    <option value="Sales">Sales
                                                    </option>
                                                    <option value="Marketing">Marketing
                                                    </option>
                                                    <option value="Both">Both
                                                    </option>

                                                </select>
                                                <span id="office_name_feedback"></span> <!-- Correct placement -->
                                            </div>
                                        </div>

                                        {{-- <input type="hidden" name="office_id" value="{{ Auth::user()->station }}"> --}}
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Remarks ("Indicate agreements if any") <span
                                                        class="text-danger">*</span></label>
                                                <textarea name="remarks" id="remarks" rows="10" class="form-control"></textarea>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between ">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close
                                            X</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ================= SCRIPT ================= -->
                <script>
                    $(document).ready(function() {
                        // This event fires when the modal is triggered by the button
                        $('#editModal').on('show.bs.modal', function(event) {
                            var button = $(event.relatedTarget); // Button that triggered the modal

                            // Extract values from button data attributes
                            var id = button.data('id');
                            var reason = button.data('reason');
                            var code = button.data('code');
                            var description = button.data('description');

                            // Fill the modal fields with these values
                            var modal = $(this);
                            modal.find('#edit_id').val(id);
                            modal.find('#edit_reason').val(reason);
                            modal.find('#edit_code').val(code);
                            modal.find('#edit_description').val(description);

                            // Update form action dynamically
                            modal.find('#editForm').attr('action', '/failed_collection/' + id);
                        });
                    });
                </script>
            </div>
        </div>
    @endsection
