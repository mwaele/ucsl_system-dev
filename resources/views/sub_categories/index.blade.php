@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <button class="btn btn-success" data-toggle="modal" data-target="#createSubCategoryModal">
                    <i class="fas fa-plus-circle"></i> Add Sub Category
                </button>
                <h4 class="m-0 font-weight-bold text-success">Sub Categories Lists</h4>


                <a href="/sub_categories_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sub Category</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Sub Category</th>
                            <th>Description</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($sub_categories as $sub_category)
                            <tr>
                                <td> {{ $loop->iteration }}. </td>
                                <td> {{ $sub_category->sub_category_name }} </td>
                                <td> {{ $sub_category->description }} </td>
                                <td class="row pl-4">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-info mr-1" title="Edit" data-toggle="modal"
                                        data-target="#editSubCategoryModal-{{ $sub_category->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#deleteSubCategory-{{ $sub_category->id }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteSubCategory-{{ $sub_category->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteSubCategoryLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete <strong>{{ $sub_category->sub_category_name }}</strong>?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('sub_categories.destroy', ['sub_category' => $sub_category->id]) }}"
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

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editSubCategoryModal-{{ $sub_category->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editSubCategoryModalLabel" aria-hidden="true">
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
                                                        action="{{ route('sub_categories.update', ['sub_category' => $sub_category->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div>
                                                            <div>
                                                                <div class="form-group">
                                                                    <label>Subcategory Name <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="sub_category_name"
                                                                        class="form-control"
                                                                        value="{{ $sub_category->sub_category_name }}" required>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="form-group">
                                                                    <label>Description</label>
                                                                    <input type="text" name="description"
                                                                        class="form-control"
                                                                        value="{{ $sub_category->description }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="submit"
                                                                class="btn btn-info btn-sm"><i class="fas fa-save"></i>
                                                                Save Changes</button>
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

    <!-- Create Sub-Category Modal -->
    <div class="modal fade" id="createSubCategoryModal" tabindex="-1" role="dialog"
        aria-labelledby="createSubCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success">Create New Sub-Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('sub_categories.store') }}" method="POST">
                        @csrf
                        <div>
                            <div class="form-group">
                                <label>Sub Category Name <span class="text-danger">*</span></label>
                                <input type="text" id="subcategory" name="sub_category_name" class="form-control"
                                    required="">
                                <span id="subcategory_name_feedback"></span>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="description" class="form-control" required>
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
