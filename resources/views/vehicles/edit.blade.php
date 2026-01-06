@extends('layouts.custom')

@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-4">
                    <i class="fas fa-table text-success"></i>
                    Edit Company Info
                </div>
            </div>
        </div>
        <div class="card-body">
            {{-- Success Message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Error Message --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @foreach ($company_infos as $company_info)
                <form action="  {{ route('company_infos.update', $company_info->id) }} " method="post">
                    <div class="row">
                        @csrf
                        @method('PATCH')
                        <div class="col-md-4">
                            <div class="form-group"><label>Company Name</label>
                                <input type="text" name="company_name" value="{{ $company_info->company_name }}"
                                    class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Company Address</label>
                                <input type="text" name="address" value="{{ $company_info->address }}"
                                    class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Contact</label>
                                <input type="text" name="contact" value="{{ $company_info->contact }}"
                                    class="form-control" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label>Pin Number</label>
                                <input type="text" name="pin" value="{{ $company_info->pin }}" class="form-control"
                                    required="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Email</label>
                                <input type="text" name="email" value="{{ $company_info->email }}" class="form-control"
                                    required="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Slogan</label>
                                <input type="text" name="slogan" value="{{ $company_info->slogan }}"
                                    class="form-control" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label>Physical Address</label>
                                <input type="text" name="location" value="{{ $company_info->location }}"
                                    class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label></label>
                                <input type="text" name="website" value="{{ $company_info->website }}"
                                    class="form-control" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label><i class="fa fa-double-check"></i></label>
                            <button type="submit" class="form-control  btn btn-primary btn-sm submit">
                                <i class="fas fa-save text-white"></i>
                                Save</button>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
        <br>
    </div>
@endsection
