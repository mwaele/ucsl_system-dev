@forelse ($clientRequests as $collection)
    <tr>
        <td>{{ $loop->iteration }}.</td>
        <td>{{ $collection->requestId }}</td>
        <td>{{ $collection->client->name ?? '' }}</td>
        <td>{{ $collection->client->type ?? '' }}</td>
        <td>{{ $collection->shipmentCollection->receiver_name ?? '' }}</td>
        <td>{{ $collection->serviceLevel->sub_category_name }}</td>
        <td>{{ $collection->shipmentCollection?->items?->count() ?? '' }}</td>
        <td>{{ $collection->shipmentCollection->packages_no ?? '' }}</td>
        <td>{{ $collection->user->name ?? '—' }} | {{ $collection->vehicle->regNo ?? '—' }}</td>
        <td>{{ $collection->shipmentCollection->sender_town ?? '' }}</td>
        <td>{{ $collection->shipmentCollection->receiver_town ?? '' }}</td>
        <td>{{ $collection->status ?? '' }}</td>
        <td>{{ $collection->createdBy->name ?? 'N/A' }}</td>
    </tr>
@empty
    <tr><td colspan="13" class="text-center">No records found</td></tr>
@endforelse
