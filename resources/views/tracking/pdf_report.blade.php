<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tracking PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background-color: #fff;
            font-size: 12px;
            margin: 20px;
        }

        h4 {
            color: #14489f;
            font-weight: bold;
            margin-bottom: 5px;
        }

        h2 {
            color: #14489f;
        }

        .timeline-with-icons {
            border-left: 2px solid #f3f2f2;
            list-style: none;
            padding-left: 20px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 5px;
            padding-left: 20px;
        }

        .timeline-icon {
            background-color: #f57f3f;
            color: #fff;
            border-radius: 50%;
            height: 24px;
            width: 24px;
            text-align: center;
            line-height: 24px;
            position: absolute;
            left: -33px;
            top: 0;
            font-size: 14px;
            font-weight: bold;
        }


        .text-muted {
            color: #000;
        }

        .fw-bold {
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        .format {
            margin: 0 !important;
            padding: 0 !important;
        }

        .mb-4 {
            margin-bottom: 0.5rem;
        }

        .h2 {
            font-size: 14px;
        }

        .text-primary {
            color: #14489f;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .header,
        .head {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .row {
            display: block;
            clear: both;
        }

        .col-md-6 {
            display: inline-block;
            width: 49%;
            vertical-align: top;
            margin-right: 1%;
        }

        .p-3 {
            padding: 16px;
            border: 1px solid #ccc;
            /* instead of box-shadow */
        }

        /* Margins and text styles */
        .mb-1 {
            margin-bottom: 4px;
        }

        .text-success {
            color: #f57f3f;
        }

        .text-dark {
            color: #212529;
        }

        .p {
            color: #14489f;
            margin-bottom: 2px;
            font-weight: bold;
        }

        .td {
            padding-left: 10;
            padding-bottom: 10;
            margin: 0;
            width: 50%;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        .td-new {
            width: 100%;
            padding-left: 10;
            padding-bottom: 10;
        }

        .pp {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td style="width: 60%;">
                <img src="{{ public_path('images/UCSLogo1.png') }}" height="80" style="width: auto;" alt="Logo">
            </td>

            <td style="width: 40%; text-align: right; vertical-align: middle;">
                <p style="font-size: 14px; color: #000; margin: 0;">
                    Tracking Date</strong>
                </p>
                <p style="font-size: 14px; color: #000; margin: 0;">
                    {{ now()->format('F j, Y, g:i a') }}
                </p>
                <p style="font-size: 14px; color: #000; margin: 0;">
                    Tracked By: {{ auth('client')->user()->name ?? auth('guest')->user()->name }}
                </p>
                {{-- <p class="fw-bold">
                    Tracking done by: {{ auth('api')->user()->name }}
                </p> --}}

            </td>
        </tr>
    </table>
    <div class="head">
        <h3 class="mb-2 mt-2 fw-bold text-primary">

            Tracking Report For Request ID: <strong>{{ $trackingData['requestId'] }}</strong>
            Client: <strong>{{ $trackingData['client']['name'] ?? 'N/A' }} for
                {{ count($shipment_items) }} {{ Str::plural('item', count($shipment_items)) }}

            </strong>
        </h3>
    </div>
    <section class="mb-3">
        <table width="100%" cellpadding="1" cellspacing="0"
            style="border-collapse: collapse; font-family: sans-serif;">
            <tr>
                <td class="td">
                    <p class="p">
                        Origin: <span style="color: #212529;">{{ $data['origin_office'] }}</span>
                    </p>
                    <p class="p">
                        Destination: <span style="color: #212529;">{{ $data['destination_name'] }}</span>
                        </p>
                    </td>
                <td class="td">
                    <p class="p">
                        Sender: <span style="color: #212529;">{{ $data['sender_name'] }}</span>
                    </p>
                    <p class="p">
                        Receiver: <span style="color: #212529;">{{ $data['receiver_name'] }}</span>
                    </p>
                </td>

            </tr>

        </table>
        <table width="100%" style="border-collapse: collapse; font-family: sans-serif;">
            <tr>
                <td class="td">
                    <p class="p">Items Description</p>
                    @foreach ($shipment_items as $index => $shipment_item)
                        <p class="pp">
                            {{ $index + 1 }}.) {{ $shipment_item->packages_no }}
                            {{ $shipment_item->item_name }}
                            Weighing
                            {{ $shipment_item->actual_weight }} Kgs</p>
                    @endforeach
                </td>
            </tr>
        </table>
    </section>

    <section class="">
        <ul class="timeline-with-icons">
            @foreach ($trackingData['tracking_infos'] as $info)
                <li class="timeline-item">
                    <span class="timeline-icon"></span>
                    <h3 class="fw-bold text-primary">{{ $info['details'] }}</h3>
                    <p class="text-muted format">{{ \Carbon\Carbon::parse($info['date'])->format('d M Y, H:i') }}
                    </p>
                    @if (!empty($info['remarks']))
                        <p class="text-muted format">{{ $info['remarks'] }}</p>
                    @endif
                    @if ($loop->last)
                        <h2 class="text-success p-0 m-0 fst-italic format"><strong>Current Status:
                                {{ $trackingData['current_status'] }}</strong></h2>
                    @endif
                </li>
            @endforeach
        </ul>

    </section>
</body>

</html>
