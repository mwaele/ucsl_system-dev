@extends('layouts.custom')

@section('content')
    <div class="card">

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <!-- Create Category Button (opens modal) -->
                <button class="btn btn-success" data-toggle="modal" data-target="#createCategoryModal">
                    <i class="fas fa-plus-circle"></i> Category
                </button>

                <h4 class="m-0 font-weight-bold text-success">Categories List</h4>

                <a href="/categories_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                    <i class="fas fa-download fa-sm text-white"></i> Generate Report
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->category_name }}</td>
                                <td>{{ $category->description }}</td>
                                <td class="row pl-4">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-info mr-1" title="Edit" data-toggle="modal"
                                        data-target="#editCategoryModal-{{ $category->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#deleteCategory-{{ $category->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteCategory-{{ $category->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteCategoryLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete
                                                        <strong>{{ $category->category_name }}</strong>?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action="{{ route('categories.destroy', ['category' => $category->id]) }}"
                                                        method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            YES DELETE <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editCategoryModal-{{ $category->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-info">Edit Category</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <form
                                                        action="{{ route('categories.update', ['category' => $category->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Category Name <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="category_name"
                                                                        class="form-control"
                                                                        value="{{ $category->category_name }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Description</label>
                                                                    <input type="text" name="description"
                                                                        class="form-control"
                                                                        value="{{ $category->description }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit"
                                                                class="btn btn-info btn-sm"><i class="fas fa-save"></i>
                                                                Save Changes</button>
                                                            <button type="button" class="btn btn-secondary btn-sm"
                                                                data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
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
    </div>

    <!-- Create Category Modal -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog"
        aria-labelledby="createCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success">Create New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category Name <span class="text-danger">*</span></label>
                                    <input type="text" id="category" name="category_name" class="form-control" required>
                                    <span id="category_name_feedback"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="description" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" id="submit-btn" class="btn btn-primary btn-sm">
                                <i class="fas fa-save text-white"></i> Save
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
