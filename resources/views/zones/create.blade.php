@extends('layouts.custom')

@section('content')
    <div class="card mb-3">
        <div class="card-header pt-4">
            <div class="row pt-2">
                <div class="col-sm-4">
                    <i class="fas fa-table text-success"></i>
                    Add Zone
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="  {{ route('zones.store') }} " method="post">

                <div class="row">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-group"><label>Zone Name <span class="text-danger">*</span></label>
                            <input type="text" id="zone" name="zone_name" class="form-control" required="">
                            <span id="zone_name_feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label>Description</label>
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
