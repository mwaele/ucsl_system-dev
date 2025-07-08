<!DOCTYPE html>
<html>

<head>
    <title>Rate Report PDF</title>
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
                    <p><strong>Report for All Rates</strong></p>
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
                    <th style="width: 20%;">Origin</th>
                    <th style="width: 15%;">Zone</th>
                    <th style="width: 15%;">Destination</th>
                    <th style="width: 15%;">Rate</th>
                    <th style="width: 15%;">From</th>
                    <th style="width: 15%;">To</th>
                    <th style="width: 15%;">Approval Status</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rates as $i => $rate)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td> {{ $rate->office->name }} </td>
                        <td> {{ $rate->zone }} </td>
                        <td> {{ $rate->destination }} </td>
                        <td> {{ $rate->rate }} </td>
                        <td> {{ $rate->applicableFrom }} </td>
                        <td> {{ $rate->applicableTo }} </td>
                        <td> {{ $rate->approvalStatus }} </td>
                        <td> {{ $rate->status }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
