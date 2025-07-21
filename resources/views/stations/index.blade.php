@extends('layouts.custom')

@section('content')
    <!-- DataTales Example -->
    <div class="card">
        {{-- Success Message --}}

        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">Stations Lists</h6>
                <a href="/stations_report" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                        class="fas fa-download fa-sm text-white"></i> Generate Report</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Station Name</th>
                            <th>Station Prefix</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Station Name</th>
                            <th>Station Prefix</th>
                            <th>Description</th>
                            <th>Action</th>

                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($stations as $station)
                            <tr>
                                <td> {{ $station->station_name }} </td>
                                <td> {{ $station->station_prefix }} </td>
                                <td> {{ $station->description }} </td>
                                <td class="row pl-4">
                                    <a href="{{ route('stations.edit', $station->id) }}">
                                        <button class="btn btn-sm btn-info mr-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#delete_floor-{{ $station->id }}"><i
                                            class="fas fa-trash"></i></button>
                                    <!-- Logout Modal-->
                                    <div class="modal fade" id="delete_floor-{{ $station->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $station->station_name }}.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action =" {{ route('stations.destroy', ['station' => $station->id]) }}"
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
