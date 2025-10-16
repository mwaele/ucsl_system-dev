@extends('layouts.custom')

@section('content')
    <div class="container">
        <h3 class="mb-4">Vehicle Performance Report</h3>

        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-md-3">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date', $startDate->toDateString()) }}"
                        class="form-control">
                </div>
                <div class="col-md-3">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate->toDateString()) }}"
                        class="form-control">
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Transporter</th>
                    <th>Vehicle Reg No</th>
                    <th>Total Trips</th>
                    <th>Total Waybills</th>
                    <th>Total Quantity</th>
                    <th>Total Weight</th>
                    <th>Total Volume</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report as $vehicle)
                    <tr>
                        <td>{{ $vehicle->transporter->name ?? 'N/A' }}</td>
                        <td>{{ $vehicle->reg_no }}</td>
                        <td>{{ $vehicle->total_trips ?? 0 }}</td>
                        <td>{{ $vehicle->total_waybills ?? 0 }}</td>
                        <td>{{ number_format($vehicle->total_quantity ?? 0, 2) }}</td>
                        <td>{{ number_format($vehicle->total_weight ?? 0, 2) }}</td>
                        <td>{{ number_format($vehicle->total_volume ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
