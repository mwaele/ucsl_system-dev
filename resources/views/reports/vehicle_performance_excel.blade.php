<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr>
            <th>Transporter</th>
            <th>Truck Reg No</th>
            <th>Total Trips</th>
            <th>Total Waybills</th>
            <th>Total Quantity</th>
            <th>Total Weight</th>
            <th>Total Volume</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($report as $row)
            <tr>
                <td>{{ $row->transporter_name }}</td>
                <td>{{ $row->reg_no }}</td>
                <td>{{ $row->total_trips ?? 0 }}</td>
                <td>{{ $row->total_waybills ?? 0 }}</td>
                <td>{{ $row->total_quantity ?? 0 }}</td>
                <td>{{ $row->total_weight ?? 0 }}</td>
                <td>{{ $row->total_volume ?? 0 }}</td>
                <td>{{ $row->total_amount ?? 0 }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
