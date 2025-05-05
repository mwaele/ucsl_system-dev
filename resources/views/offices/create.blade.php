@extends('layouts.custom')

@section('content')
    <div class="card mb-3">
        <div class="card-header pt-4">
            <div class="row pt-2">
                <div class="col-sm-4">
                    <i class="fas fa-table text-success"></i>
                    Add Office
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="  {{ route('offices.store') }} " method="post">

                <div class="row">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group"><label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Short Name <span class="text-danger">*</span> </label>
                            <input type="text" name="shortName" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Country <span class="text-danger">*</span></label>
                            <input type="text" name="country" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label>City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" name="longitude" class="form-control">
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" name="latitude" class="form-control">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label>Type</label>
                            <select name="type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="staff">Staff Main Office</option>
                                <option value="caravan">Caravan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mpesaTill">Mpesa Till</label>
                            <input type="text" name="mpesaTill" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mpesaPaybill">Mpesa Paybill</label>
                            <input type="text" name="mpesaPaybill" class="form-control">
                        </div>
                    </div>

                </div>


                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group"><label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 pt-2">
                        <label for=""></label>
                        <button type="submit" class="form-control btn btn-primary btn-sm submit">
                            <i class="fas fa-save text-white"></i>
                            Save</button>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div>
@endsection
