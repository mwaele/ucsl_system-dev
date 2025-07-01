@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <a href="{{ route('transporters.create') }}" class="btn btn-success"><i class="fas fa-plus-circle"></i>
                    Create Transporter</a>
                <h4 class="m-0 font-weight-bold text-success">Transporters Lists</h4>


                <a href="/transporters_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Account No</th>
                            <th>Reg. Details</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Account No</th>
                            <th>Reg. Details</th>
                            <th>Type</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($transporters as $transporter)
                            <tr>
                                <td> {{ $transporter->name }} </td>
                                <td> {{ $transporter->phone_no }} </td>
                                <td> {{ $transporter->email }} </td>
                                <td> {{ $transporter->account_no }} </td>
                                <td> {{ $transporter->reg_details }} </td>
                                <td>
                                    @if ($transporter->transporter_type == 'third_party')
                                        {{ 'Third Party' }}
                                    @elseif($transporter->transporter_type == 'self')
                                        {{ 'Self' }}
                                    @endif
                                </td>
                                <td class="row pl-4">
                                    <a href="{{ route('transporters.edit', $transporter->id) }}">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                    <a href="transporter/trucks/{{ $transporter->id }}">
                                        <button class="btn btn-sm btn-primary mr-1"><i class="fas fa-truck"></i>
                                            Trucks</button></a>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $transporter->id }}"><i
                                            class="fas fa-trash"></i></button>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="delete_floor-{{ $transporter->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $transporter->name }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('transporters.destroy', ['transporter' => $transporter->id]) }}"
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
