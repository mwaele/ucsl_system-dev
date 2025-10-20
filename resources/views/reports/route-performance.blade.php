@extends('layouts.custom')

@section('content')
    <div class="card p-4 mt-5">
        <h4 class="mb-3">Revenue & Volume Performance by Route/Region</h4>
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-2">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-2">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2">
                <label for="destination" class="text-sm font-semibold">Destination:</label>
                <input type="text" name="destination" id="destination" placeholder="Enter destination..."
                    value="{{ request('destination') }}" class="border rounded px-2 py-1">
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>

                <a href="#" id="clearFilter" class="btn btn-info mr-2">Clear </a>
            </div>
            <div class="col-md-3 align-self-end text-end">
                <a href="{{ route('reports.routes.export.pdf', request()->query()) }}" class="btn btn-danger me-2">PDF</a>
                <a href="{{ route('reports.routes.export.excel', request()->query()) }}" class="btn btn-success">Excel</a>
            </div>
        </form>

        <table class="table table-bordered  text-primary" id='DataTable'>
            <thead class="">
                <tr>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Total Shipments</th>
                    <th>Total Volume (Kg)</th>
                    <th>Total Revenue (KSh)</th>
                    <th>Volume %</th>
                    <th>Revenue %</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report as $row)
                    <tr>
                        <td>{{ $row->origin }}</td>
                        <td>{{ $row->destination }}</td>
                        <td>{{ $row->total_shipments }}</td>
                        <td>{{ number_format($row->total_volume, 2) }}</td>
                        <td>{{ number_format($row->total_revenue, 2) }}</td>
                        <td>{{ $row->volume_contribution }}%</td>
                        <td>{{ $row->revenue_contribution }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
