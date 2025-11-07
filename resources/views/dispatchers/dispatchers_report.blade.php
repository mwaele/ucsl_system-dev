<!DOCTYPE html>
<html>

<head>
    <title>Dispatchers Report PDF</title>
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
                    <p><strong>Report for Dispatchers </strong></p>
                    <p><strong>Reporting Period:</strong> {{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</p>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <img src="{{ public_path('images/UCSLogo1.png') }}" alt="Logo" style="height: 70px;">
                </td>
            </tr>
        </table>

        <!-- Main Table -->
        <table class="table table-bordered text-primary bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>ID Number</th>
                    <th>Phone Number</th>
                    <th>Office</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($dispatchers as $dispatcher)
                    <tr>
                        <td> {{ $dispatcher->name }} </td>
                        <td> {{ $dispatcher->id_no }} </td>
                        <td> {{ $dispatcher->phone_no }} </td>
                        <td> {{ $dispatcher->office->name ?? 'No Station' }} </td>

                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</body>

</html>
