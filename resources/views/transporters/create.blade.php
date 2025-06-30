@extends('layouts.custom')

@section('content')
    <div class="card mb-3">
        <div class="card-header pt-4">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-success">Create New Transporter </h4>

                <a href="{{ route('transporters.index') }}" class="btn btn-success"><i class="fas fa-bars"></i>
                    All Transporters</a>
            </div>

        </div>
        <div class="card-body">
            <form action="  {{ route('transporters.store') }} " method="post" enctype="multipart/form-data">

                <div class="row">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Transporter Name <span class="text-danger">*</span></label>
                            <input type="text" id="transporter" name="name" class="form-control" required="">
                            <span id="name_feedback"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone Number (+254 ...) <span class="text-danger">*</span></label>
                            <input type="text" name="phone_no" class="form-control">
                            <span id="phone_no_feedback"></span> <!-- Correct placement -->
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Registration Details</label>
                            <input type="text" name="reg_details" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="text" id="email" name="email" class="form-control" required="">
                            <span id="email_feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>CBV Number</label>
                            <input type="text" id="cbv_no" name="cbv_no" class="form-control">
                            <span id="cbv_no_feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Account No <span class="text-danger">*</span></label>
                            <input type="text" value="{{ $account_no }}" id="account_no" name="account_no"
                                class="form-control" required="" readonly>
                            <span id="account_no_feedback"></span>
                        </div>
                    </div>

                </div>



                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Signature <span class="text-danger">*</span></label>
                            <input type="file" id="signature" name="signature" class="form-control">
                            <span id="signature_feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <label for="">.</label>
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
