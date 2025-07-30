<!DOCTYPE html>
<html>

<head>
    <title>Clients Report PDF</title>
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
                    <p><strong>Parcel Receipts at {{ Auth::user()->office->name }} </strong></p>
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
                    <th>Manifest #</th>
                    <th>Dispatch date</th>
                    <th>Office of Origin</th>
                    <th>Destination</th>
                    <th>Vehicle Number</th>
                    <th>Transporter</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sheets as $sheet)
                    <tr>
                        <td class="text-danger sized">{{ str_pad($sheet->batch_no, 5, '0', STR_PAD_LEFT) }}</td>
                        <td> {{ $sheet->dispatch_date ?? 'Pending Dispatch' }} </td>
                        <td> {{ $sheet->office->name }} </td>
                        <td> {{ $sheet->rate->destination ?? '' }} @if ($sheet->destination_id == '0')
                                {{ 'Various' }}
                            @endif
                        </td>
                        <td> {{ $sheet->transporter_truck->reg_no }} </td>
                        <td> {{ $sheet->transporter->name }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
