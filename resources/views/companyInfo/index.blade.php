@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">Company Information</h6>
                <a href="/company_info_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Slogan</th>
                            <th>Postal Address</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Company Name</th>
                            <th>Slogan</th>
                            <th>Postal Address</th>
                            <th>Email</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($company_infos as $company_info)
                            <tr>
                                <td> {{ $company_info->company_name }} </td>
                                <td> {{ $company_info->slogan }} </td>
                                <td> {{ $company_info->address }} </td>
                                <td> {{ $company_info->email }} </td>
                                <td class="row pl-4">
                                    <a href="{{ route('company_infos.edit', $company_info->id) }}">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $company_info->id }}"><i
                                            class="fas fa-trash"></i></button>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="delete_floor-{{ $company_info->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $company_info->company_name }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('company_infos.destroy', ['company_info' => $company_info->id]) }}"
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

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
