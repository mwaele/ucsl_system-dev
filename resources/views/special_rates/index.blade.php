@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add"> Add
                    Rate <i class="fas fa-plus"></i></button>
                <h4 class="m-0 font-weight-bold text-warning">Special Rates Lists</h4>
                <a href="/rates_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Client</th>
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
                            <th>Client</th>
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
                                <td>{{ $rate->client->name }}</td>
                                <td> {{ $rate->office->name }} </td>
                                <td> {{ $rate->zone_id }} </td>

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
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $rate->id }}"><i
                                            class="fas fa-trash"></i></button>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="delete_floor-{{ $rate->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $rate->regNo }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action =" {{ route('rates.destroy', ['rate' => $rate->id]) }}"
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
