@extends('layouts.custom')

@section('content')
    <div class="card mb-3">
        <div class="card-header pt-4">
            <div class="row pt-2">
                <div class="col-sm-4">
                    <i class="fas fa-table text-success"></i>
                    Add Company Information
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="  {{ route('company_infos.store') }} " method="post" enctype="multipart/form-data">

                <div class="row">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group"><label>Logo</label>
                            <input type="file" name="logo" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Company Name</label>
                            <input type="text" name="company_name" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Slogan</label>
                            <input type="text" name="slogan" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label>Location</label>
                            <input type="text" name="location" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group"><label>Postal Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input type="text" name="website" class="form-control">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label>Email</label>
                            <input type="text" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pin">PIN</label>
                            <input type="text" name="pin" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <input type="text" name="contact" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 pt-3">
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
