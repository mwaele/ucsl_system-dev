<table border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Request ID</th>
            <th>Date Requested</th>
            <th>Service Level</th>
            <th>From</th>
            <th>To</th>
            <th>Receiver</th>
            <th>Assigned</th>
            <th>Items</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clientRequests as $collection)

            @php
                $serviceLevel = $collection->serviceLevel
                    ? ($collection->serviceLevel->sub_category_name ?? '')
                    : '';

                $shipment = $collection->shipmentCollection;

                $senderTown   = $shipment ? ($shipment->sender_town ?? '') : '';
                $receiverTown = $shipment ? ($shipment->receiver_town ?? '') : '';
                $receiverName = $shipment ? ($shipment->receiver_name ?? '') : '';

                $riderName = $collection->user ? ($collection->user->name ?? '') : '';
                $vehicleNo = $collection->vehicle ? ($collection->vehicle->regNo ?? '') : '';

                $assignment = 'Pending';

                if ($riderName) {
                    $assignment = $riderName;
                    if ($vehicleNo) {
                        $assignment .= ' | ' . $vehicleNo;
                    }
                }

                $itemsCount = $shipment && $shipment->items
                    ? $shipment->items->count()
                    : 0;

                $amount = $shipment ? ($shipment->actual_total_cost ?? 0) : 0;

            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $collection->requestId }}</td>
                <td>{{ $collection->dateRequested }}</td>
                <td>{{ $serviceLevel }}</td>
                <td>{{ $senderTown }}</td>
                <td>{{ $receiverTown }}</td>
                <td>{{ $receiverName }}</td>
                <td>{{ $assignment }}</td>
                <td>{{ $itemsCount }}</td>
                <td>{{ number_format((float) $amount, 2, '.', '') }}</td>
                <td>{{ $collection->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
