<!DOCTYPE html>
<html>
<head>
    <title>Receipt - {{ $request->requestId }}</title>
    <style>
        body {
            font-family: monospace;
            max-width: 300px;
            margin: auto;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print();">

    <h4 style="text-align: center;">Ufanisi Courier Services</h4>
    <p style="text-align: center;">Parcel Dispatch Receipt</p>
    <hr>

    <strong>Date:</strong> {{ now()->format('F j, Y g:iA') }}<br>
    <strong>Receipt No:</strong> {{ $request->requestId ?? 'N/A' }}
    <hr>

    <strong>Sender:</strong><br>
    Name: {{ $request->sender_name }}<br>
    ID: {{ $request->sender_id_no }}<br>
    Phone: {{ $request->sender_contact }}<br>
    Town: {{ $request->sender_town }}<br>
    Address: {{ $request->sender_address }}<br>
    <hr>

    <strong>Receiver:</strong><br>
    Name: {{ $request->receiver_contact_person }}<br>
    ID: {{ $request->receiver_id_no }}<br>
    Phone: {{ $request->receiver_phone }}<br>
    Town: {{ $request->receiver_town }}<br>
    Address: {{ $request->receiver_address }}
    <hr>

    <strong>Parcel(s):</strong><br>
    @forelse ($request->shipmentCollection->items as $item)
        {{ $item->item_name }}<br>
        Qty: {{ $item->packages_no }}, Weight: {{ $item->weight }}kg<br>
        <hr>
    @empty
        <p>No shipment items found.</p>
    @endforelse



    <strong>Charges:</strong><br>
    Base: KES {{ number_format($request->base_cost, 2) }}<br>
    VAT: KES {{ number_format($request->vat, 2) }}<br>
    <strong>Total: KES {{ number_format($request->total_cost, 2) }}</strong>

    <hr>
    <p style="text-align: center;">Thank you for choosing Ufanisi Courier!</p>

</body>
</html>
