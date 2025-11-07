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
                <h2 class="text-danger">Failed Delivery Records</h2>

                <a href="/delivery_failed_report"><button type="button" class="btn btn-danger " data-toggle="generateReport"
                        data-target="#generateModal">
                        Generate Report <i class="fas file-pdf-o"></i>
                    </button></a>
            </div>
        </div>

        <div class="card-body">

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
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td>{{ $record->reason }}</td>
                            <td>{{ $record->reference_code }}</td>
                            <td>{{ $record->description }}</td>
                            <td>
                                {{-- Edit Button (triggers modal) --}}
                                <button type="button" class="btn btn-warning btn-sm editBtn" data-toggle="modal"
                                    data-target="#editModal" data-id="{{ $record->id }}"
                                    data-reason="{{ $record->reason }}" data-code="{{ $record->reference_code }}"
                                    data-description="{{ $record->description }}">
                                    Edit
                                </button>

                                {{-- Delete --}}
                                {{-- <form action="{{ route('delivery_faileds.destroy', $record->id) }}" method="POST"
                                style="display:inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>


    {{-- ================= CREATE MODAL ================= --}}
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('delivery_faileds.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="createModalLabel">Add Delivery Failed Record</h5>
                        <button type="button" class="btn-close btn btn-danger" data-dismiss="modal">X</button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Reason</label>
                            <input type="text" name="reason" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Reference Code</label>
                            <input type="text" name="reference_code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ================= EDIT MODAL ================= --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Delivery Failed Record</h5>
                        <button type="button" class="btn-close btn-danger" data-dismiss="modal">X</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-3">
                            <label>Reason</label>
                            <input type="text" name="reason" id="edit_reason" class="form-control" required readonly>
                        </div>
                        <div class="mb-3">
                            <label>Reference Code</label>
                            <input type="text" name="reference_code" id="edit_code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" id="edit_description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ================= SCRIPT ================= --}}
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
                modal.find('#editForm').attr('action', '/delivery_faileds/' + id);
            });
        });
    </script>
@endsection
