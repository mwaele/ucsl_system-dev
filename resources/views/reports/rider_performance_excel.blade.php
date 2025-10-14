<table>
    <thead>
        <tr>
            <th>#</th>
            <th>User Name</th>
            <th>Total Shipments</th>
            <th>Total Amount (KES)</th>
            <th>Total Volume (KG)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($report as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->total_shipments }}</td>
                <td>{{ number_format($item->total_amount, 2) }}</td>
                <td>{{ number_format($item->total_volume, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
