@extends('layouts.custom')

@section('content')
    <div class="">
        <h2>Collections Failed Records</h2>

        {{-- Success Message --}}
        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}

        {{-- Button to trigger Add Modal --}}
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">
            Add New
        </button>

        <table class="table table-bordered" id="dataTable">
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
                                data-target="#editModal" data-id="{{ $record->id }}" data-reason="{{ $record->reason }}"
                                data-code="{{ $record->reference_code }}" data-description="{{ $record->description }}">
                                Edit
                            </button>

                            {{-- Delete --}}
                            {{-- <form action="{{ route('failed_collection.destroy', $record->id) }}" method="POST"
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
        {{ $records->links() }}
    </div>


    {{-- ================= CREATE MODAL ================= --}}
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('failed_collection.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="createModalLabel">Add Collections Failed Record</h5>
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
                        <h5 class="modal-title" id="editModalLabel">Edit Collections Failed Record</h5>
                        <button type="button" class="btn-close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-3">
                            <label>Reason</label>
                            <input type="text" name="reason" id="edit_reason" class="form-control" required>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ================= SCRIPT ================= --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editModal = document.getElementById('editModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const reason = button.getAttribute('data-reason');
                const code = button.getAttribute('data-code');
                const description = button.getAttribute('data-description');

                // populate modal fields
                editModal.querySelector('#edit_id').value = id;
                editModal.querySelector('#edit_reason').value = reason;
                editModal.querySelector('#edit_code').value = code;
                editModal.querySelector('#edit_description').value = description;

                // set form action dynamically
                const form = document.getElementById('editForm');
                form.action = "/failed_collection/" + id;
            });
        });
    </script>
@endsection
