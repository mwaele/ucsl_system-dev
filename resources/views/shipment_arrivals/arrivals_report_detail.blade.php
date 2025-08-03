<!DOCTYPE html>
<html>

<head>
    <title>Parcel Arrival Details Report PDF</title>
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
                    <h2><strong>{{ $title }}Parcel Receipts at {{ Auth::user()->office->name }} </strong></h2>
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
                    <th>WAYBILL NO.</th>
                    <th>CLIENT</th>
                    <th>DESCRIPTION</th>
                    <th>DESTINATION</th>
                    <th>QTY</th>
                    <th>WEIGHT</th>
                    <th>AMOUNT</th>
                    <th>ACCOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->waybill_no }}</td>
                        <td>{{ $item->client_name }}</td>
                        <td>{{ $item->item_names }}</td>
                        <td>{{ $item->destination ?? '' }}</td>
                        <td>{{ $item->total_quantity }}</td>
                        <td>{{ $item->total_weight }}</td>
                        <td>{{ number_format($item->total_cost, 2) }}</td>
                        <td>{{ $item->payment_mode }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
