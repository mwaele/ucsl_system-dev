@extends('layouts.custom')

@section('content')
<div class="card">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Client initiated parcels</h5>
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex align-items-center ms-auto">
            <a href="/client_performance_report/generate" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2">
                <i class="fas fa-download fa text-white"></i> Generate Report
            </a>

        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="dataTable">
                <thead class="text-success">
                    <tr>
                        <th>Request ID</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Current Status</th>
                        <th>Assign Rider</th>
                    </tr>
                </thead>
                <tfoot class="text-success">
                    <tr>
                        <th>Request ID</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Current Status</th>
                        <th>Assign Rider</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

