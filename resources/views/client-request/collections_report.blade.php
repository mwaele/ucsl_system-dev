<!DOCTYPE html>
<html>

<head>
    <title>Collections Report PDF</title>
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
                    <p><strong>Report for Collections </strong></p>
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
                    <th>#</th>
                    <th>Req ID</th>
                    <th>Client Name</th>
                    <th>Client Telephone Number</th>
                    <th>Date Allocated</th>
                    <th>Physical Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($collections as $collection)
                    <tr>
                        <td> {{ $loop->iteration }}.</td>
                        <td> {{ $collection->requestId }} </td>
                        <td> {{ $collection->client->name }} </td>
                        <td> {{ $collection->client->contactPersonPhone }} </td>
                        <td> {{ $collection->created_at }} </td>
                        <td> {{ $collection->client->address }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
