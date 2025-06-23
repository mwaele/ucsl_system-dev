@extends('layouts.custom')

@section('content')
    <div class="card mb-3">
        <div class="card-header pt-4">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-success">Create New Category </h4>

                <a href="{{ route('categories.index') }}" class="btn btn-success"><i class="fas fa-bars"></i>
                    All Sub Categories</a>
            </div>

        </div>
        <div class="card-body">
            <form action="  {{ route('sub_categories.store') }} " method="post">

                <div class="row">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Sub Category Name <span class="text-danger">*</span></label>
                            <input type="text" id="subcategory" name="sub_category_name" class="form-control"
                                required="">
                            <span id="subcategory_name_feedback"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Category Name <span class="text-danger">*</span></label>
                            <select id="category" name="category_id" class="form-control" required="">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            <span id="category_name_feedback"></span> <!-- Correct placement -->
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-4 pt-2">
                        <label for=""></label>
                        <button type="submit" id='submit-btn' class="form-control btn btn-primary btn-sm submit">
                            <i class="fas fa-save text-white"></i>
                            Save</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div>
@endsection
