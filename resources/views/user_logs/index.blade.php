@extends('layouts.custom')

@section('content')
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">User Activity Logs</h6>


            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Details</th>
                            <th>URL Visited</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Details</th>
                            <th>URL Visited</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($user_logs as $user_log)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $user_log->name }}</td>
                                <td>{{ $user_log->created_at }}</td>
                                <td>{{ $user_log->created_at }}</td>
                                <td>{{ $user_log->actions }}</td>
                                <td>{{ $user_log->url }}</td>
                                <td><button class="btn btn-info btn-sm">Details</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
