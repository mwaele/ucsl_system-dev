@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#createModal">
                    Add New
                </button>
                <h2 class="text-danger"> Failed Collections Records</h2>

                <a href="{{ route('failed_collection_report') }}"><button type="button" class="btn btn-danger "
                        data-toggle="generateReport" data-target="#generateModal">
                        Generate Report <i class="fas file-pdf-o"></i>
                    </button></a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Reason</th>
                            <th>Reference Code</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <!-- ================= TABLE ================= -->
                    <tbody>
                        @forelse($records as $record)
                            <tr>
                                <td>{{ $record->id }}</td>
                                <td>{{ $record->reason }}</td>
                                <td>{{ $record->reference_code }}</td>
                                <td>{{ $record->description }}</td>
                                <td>
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-warning btn-sm editBtn" data-toggle="modal"
                                        data-target="#editModal" data-id="{{ $record->id }}"
                                        data-reason="{{ $record->reason }}" data-code="{{ $record->reference_code }}"
                                        data-description="{{ $record->description }}">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= EDIT MODAL ================= -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title text-white" id="editModalLabel">Edit Collections Failed Record</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_id">

                            <div class="form-group">
                                <label>Reason</label>
                                <input type="text" name="reason" id="edit_reason" class="form-control" required
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label>Reference Code</label>
                                <input type="text" name="reference_code" id="edit_code" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" id="edit_description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
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
@endsection
