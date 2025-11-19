@extends('layouts.custom')

@section('content')
    <div class="card">
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-4">
                <h2 class="m-0 font-weight-bold text-danger">User Activity Logs</h2>

                <form method="GET" class="d-flex flex-wrap align-items-end gap-2">
                    <div class="form-group mr-4">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control text-primary" value="{{ request('date') }}">
                    </div>

                    <div class="form-group mr-4">
                        <label class="form-label">From</label>
                        <input type="date" name="from" class="form-control text-primary"
                            value="{{ request('from') }}">
                    </div>

                    <div class="form-group mr-4">
                        <label class="form-label">To</label>
                        <input type="date" name="to" class="form-control text-primary" value="{{ request('to') }}">
                    </div>

                    <div class="form-group mr-4 d-flex align-items-center">
                        <input type="checkbox" name="today" value="1" class="me-2 text-primary"
                            @checked(request('today'))>
                        <label class="mb-0 ml-2"> Today Only</label>
                    </div>

                    <div class="form-group mr-4 d-flex gap-2">
                        <button class="btn btn-primary mr-4 ">Filter</button>
                        <a href="{{ route('user_logs.index') }}" class="btn btn-danger mr-4">Reset <i
                                class="fas fa-refresh"></i></a>
                    </div>
                </form>
            </div>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-primary" id="dataTable">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($user_logs as $name => $dates)
                            @foreach ($dates as $date => $logs)
                                @php $log = $logs->first(); @endphp

                                <tr>
                                    <td>{{ $name }}</td>
                                    <td>{{ $date }}</td>
                                    <td>
                                        <a href="{{ route('user_logs.show', $log->id) }}" class="btn btn-info btn-sm">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
