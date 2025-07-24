@extends('layouts.custom')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header pt-4">
            <div class="d-sm-flex align-items-center justify-content-between">

                <h4 class="m-0 font-weight-bold text-success"> <i class="fas fa-plus-circle"></i> Create New Client </h4>

                <a href="{{ route('clients.index') }}" class="btn btn-success"><i class="fas fa-bars"></i>
                    All Clients List</a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('clients.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Account Number</label>
                        <input type="text" value="{{ $accountNo }}" name="accountNo" class="form-control"
                            placeholder="Enter Account Number" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Full Name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>ID Number</label>
                        <input type="text" name="id_number" id="id_number" class="form-control" placeholder="Enter ID Number">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Contact</label>
                        <input type="text" name="contact" id="contact" class="form-control" placeholder="Enter Phone Number">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Enter Address">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>City</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Building</label>
                        <input type="text" name="building" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="country">Country</label>
                        
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Category (Select single or multiple)</label>
                        {{-- <input type="text" name="category" class="form-control"> --}}
                        <select name="category_id[]" class="form-control" id="categories-multiselect"
                            multiple="multiple">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group col-md-4">
                        <label>Type</label>
                        <select name="type" class="form-control" id="type">
                            <option value="">Select Account Type</option>
                            <option value="on_account">On Account</option>
                            <option value="walkin">Walkin</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter Password">
                    </div>
                    {{-- <div class="form-group col-md-4">
                        <label>Industry</label>
                        <input type="text" name="industry" class="form-control">
                    </div> --}}
                </div>

                <h5 class="mt-4">Contact Person Details</h5>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Contact Person</label>
                        <input type="text" name="contactPerson" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Phone</label>
                        <input type="text" name="contactPersonPhone" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="email" name="contactPersonEmail" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>KRA Pin</label>
                        <input type="text" name="kraPin" id="kraPin" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Postal Code</label>
                        <input type="text" name="postalCode" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="special_rates">Client Have Special Rates?</label>
                        <input type="checkbox" name="special_rates_status">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block"><strong>Register Client Account</strong></button>
            </form>
        </div>
    </div>
    {{-- <div class="card mb-3">
        <div class="card-header pt-4">
            <div class="row pt-2">
                <div class="col-sm-4">
                    <i class="fas fa-table text-success"></i>
                    Add New Client
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="  {{ route('clients.store') }} " method="post">

                <div class="row">
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group"><label>Client Name</label>
                            <input type="text" name="client_name" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Phone Number</label>
                            <input type="text" name="tel_number" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Email</label>
                            <input type="text" name="email" class="form-control">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label>Physical Address</label>
                            <input type="text" name="physical_address" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Postal Address</label>
                            <input type="text" name="postal_address" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Postal Code</label>
                            <input type="text" name="postal_code" class="form-control" required="">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4 pt-3">
                        <button type="submit" class="form-control btn btn-primary btn-sm submit">

                            <strong> SAVE CLIENT </strong> <i class="fas fa-arrow-right text-white"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div> --}}
@endsection
