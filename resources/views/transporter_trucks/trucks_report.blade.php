<!DOCTYPE html>
<html>

<head>
    <title>Transporters PDF</title>
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
                    <h1><strong>Report for Trucks for {{ $name }}</strong></h1>
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
                    <th style="width: 20%;">Reg No.</th>
                    <th style="width: 20%;">Driver Name</th>
                    <th style="width: 20%;">Driver Contact</th>
                    <th style="width: 20%;">Truck Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trucks as $i => $truck)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td> {{ $truck->reg_no }} </td>
                        <td> {{ $truck->driver_name }} </td>
                        <td>{{ $truck->driver_contact }}</td>
                        <td>{{ $truck->truck_type }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
