@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">Rates Lists</h6>
                <a href="/rates_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Origin</th>
                            <th>Zone</th>
                            <th>Destination</th>
                            <th>Rate</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Approval Status</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Origin</th>
                            <th>Zone</th>
                            <th>Destination</th>
                            <th>Rate</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Approval Status</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($rates as $rate)
                            <tr>
                                <td> {{ $rate->office->name }} </td>
                                <td> {{ $rate->zone }} </td>

                                <td> {{ $rate->destination }} </td>
                                <td> {{ $rate->rate }} </td>
                                <td> {{ $rate->applicableFrom }} </td>
                                <td> {{ $rate->applicableTo }} </td>
                                <td> {{ $rate->approvalStatus }} </td>
                                <td> {{ $rate->status }} </td>
                                <td class="row pl-4">
                                    <a href="{{ route('rates.edit', $rate->id) }}">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                    {{-- <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $rate->id }}"><i
                                            class="fas fa-trash"></i></button> --}}
                                    <!-- Logout Modal-->


                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
