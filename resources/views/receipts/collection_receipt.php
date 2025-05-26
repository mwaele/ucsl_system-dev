<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 75mm;
            padding: 5px;
        }

        .center {
            text-align: center;
        }

        .item {
            display: flex;
            justify-content: space-between;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="center">
            <strong>Ufanisi Freighters (K) Ltd</strong><br>
            Mombasa, Kenya<br>
            Phone: +254 20 2038303<br>
            ------------------------------
        </div>

        <p>Date: {{ $shipment_collections->created_at->format('Y-m-d H:i') }}</p>
        <p>Receipt #: {{ 'UFKL-00'.$shipment_collections->id }}</p>
        <hr>

        @foreach($shipment_collections->items as $item)
            <div class="item">
                <span>{{ $item->item_name }} x{{ $item->packages_no }}</span>
                <span>KES {{ number_format($item->cost * $item->packages_no, 2) }}</span>
            </div>
        @endforeach

        <hr>

        <div class="item">
            <strong>Total</strong>
            <strong>KES {{ number_format($order->total_cost, 2) }}</strong>
        </div>

        <div class="center">
            ------------------------------<br>
            Thank you for sending parcel with us!<br>
        </div>
    </div>
</body>
</html>
