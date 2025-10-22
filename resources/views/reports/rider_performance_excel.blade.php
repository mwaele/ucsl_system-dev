<table>
    <thead>
        <tr>
            <th>#</th>
            <th style="text-align: left;">User Name</th>
            <th>Total Shipments</th>
            <th style="text-align: right;">Total Amount (KES)</th>
            <th style="text-align: right;">Total Volume (KG)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($report as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="text-align: left;">{{ $item->name }}</td>
                <td>{{ $item->total_shipments }}</td>
                <td style="text-align: right;">{{ number_format($item->total_amount, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->total_volume, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
