<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Route Performance Report</title>
</head>

<body>
    <h2 style="text-align:center;">Route Performance Report</h2>

    @if (!empty($start_date) && !empty($end_date))
        <p style="text-align:center;"><strong>Period:</strong> {{ $start_date }} to {{ $end_date }}</p>
    @endif

    <table border="1" cellspacing="0" cellpadding="6" width="100%">
        <thead style="background-color:#f2f2f2;">
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
</body>

</html>
