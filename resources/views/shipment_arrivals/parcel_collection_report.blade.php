<!DOCTYPE html>
<html>

<head>
    <title>Parcel Collection Report PDF</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin-left: 50px;
            margin-right: 20px;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.bordered th,
        table.bordered td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            margin-bottom: 0;
        }

        @page {
            margin: 50px 30px;
        }

        .content {
            margin-bottom: 50px;
        }
    </style>
</head>

<body>
    <div class="content">
        <!-- Report Header -->
        <table>
            <tr>
                <td style="text-align: left;">
                    <p><strong>Report for All Parcel Collection</strong></p>
                    <p><strong>Reporting Period:</strong> {{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</p>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <img src="{{ public_path('images/UCSLogo1.png') }}" alt="Logo" style="height: 70px;">
                </td>
            </tr>
        </table>

        <!-- Main Table -->
        <table class="bordered">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 20%;">Request ID</th>
                    <th style="width: 15%;">Waybill No.</th>
                    <th style="width: 15%;">Parcel Details</th>
                    <th style="width: 15%;">Cost</th>
                    <th style="width: 15%;">Payment Status</th>
                    <th style="width: 15%;">Collection Status</th>
                    <th style="width: 15%;">Collected By</th>
                    <th style="width: 15%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shipmentArrivals as $i => $arrival)
                    <tr>
                        <td> {{ $loop->iteration }}. </td>
                        <td> {{ $arrival->requestId }} </td>
                        <td> {{ $arrival->shipmentCollection->waybill_no }} </td>
                        <td> {{ $arrival->shipmentCollection->items->pluck('item_name')->join(', ') }} </td>
                        <td>Ksh {{ number_format($arrival->total_cost, 2) }}</td>
                        <td>
                            @php
                                $payment = $arrival->payment; // e.g. latest payment; may be null
                                $totalCost = $arrival->shipmentCollection->total_cost ?? 0;

                                // If we have any payment model, use its computed accessors (which aggregate all)
                                if ($payment) {
                                    $totalPaid = $payment->total_paid;
                                    $balance = $payment->balance;
                                } else {
                                    // No single payment loaded; compute directly to keep badges accurate
                                    $totalPaid = \App\Models\Payment::where(
                                        'shipment_collection_id',
                                        $arrival->shipment_collection_id,
                                    )->sum('amount');
                                    $balance = max(0, $totalCost - $totalPaid);
                                }
                            @endphp

                            @if ($totalPaid > 0 && $balance > 0)
                                <span class="badge bg-info text-white">
                                    Paid: Ksh. {{ number_format($totalPaid, 0) }}
                                </span>
                                <br>
                                <span class="badge bg-primary text-white">
                                    Balance: Ksh. {{ number_format($balance, 0) }}
                                </span>
                            @elseif($totalPaid >= $totalCost && $totalCost > 0)
                                <span class="badge bg-success text-white">Fully Paid</span>
                            @else
                                <span class="badge bg-danger text-white">Unpaid</span>
                            @endif
                        </td>
                        <td> {{ $arrival->status_label }} </td>
                        <td> {{ $arrival->shipmentCollection->receiver_name ?? null }} </td>
                        <td> </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
