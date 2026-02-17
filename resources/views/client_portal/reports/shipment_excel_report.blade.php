<table border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Request ID</th>
            <th>Date</th>
            <th>Service Level</th>
            <th>Items</th>
            <th>Assigned rider & truck</th>
            <th>From</th>
            <th>To</th>
            <th>Receiver</th>
            <th>Collection Status</th>
            <th>Amount (Ksh)</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($clientRequests ?? [] as $collection)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $collection->requestId ?? '' }}</td>
                <td>
                    {{ $collection->dateRequested 
                        ? \Carbon\Carbon::parse($collection->dateRequested)->format('M d, Y') 
                        : '' }}
                </td>
                <td>{{ \Illuminate\Support\Str::title($collection->serviceLevel?->sub_category_name ?? '') }}</td>
                <td>{{ $collection->shipmentCollection?->items?->count() ?? '' }}</td>
                <td>
                    {{ optional($collection->user)->name 
                        ? optional($collection->user)->name . ' | ' . optional($collection->vehicle)->regNo 
                        : 'Pending' }}
                </td>
                <td>{{ $collection->shipmentCollection?->sender_town ?? '' }}</td>
                <td>{{ $collection->shipmentCollection?->receiver_town ?? '' }}</td>
                <td>{{ $collection->shipmentCollection?->receiver_name ?? '' }}</td>
                <td>{{ $collection->status ?? '' }}</td>
                <td>
                    {{ number_format($collection->shipmentCollection?->actual_total_cost ?? 0, 2) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11">No records found</td>
            </tr>
        @endforelse
    </tbody>
</table>
