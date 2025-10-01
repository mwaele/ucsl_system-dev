<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>UCSL Waybill</title>
    <style>
        @page {
            size: A5;
            margin: 5mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            color: #14489F;
            margin: 0;
        }

        .two-columns {
            display: flex;
            gap: 5px;
        }

        .left-column,
        .right-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .right-first-row {
            display: flex;
            gap: 4px;
        }

        .right-first-left,
        .right-first-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .section {
            border: 1px solid #14489F;
        }

        .logo-container img {
            max-height: 30px;
            max-width: 100px;
            object-fit: contain;
        }

        .section-header {
            background: #14489F;
            color: #fff;
            padding: 2px;
            font-weight: bold;
            font-size: 8px;
        }

        .section-body {
            font-size: 7px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 4px;
        }

        .col {
            flex: 1;
            padding: 1px;
        }

        .col label {
            font-weight: bold;
        }

        .value {
            border-bottom: 1px solid #14489F;
            display: inline-block;
            width: 100%;
            min-height: 10px;
            color: #000000;
        }

        .footer {
            font-size: 7px;
            text-align: center;
            margin-top: 10px;
        }

        .waybill-title {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            color: #14489F;
        }

        .field {
            display: flex;
            gap: 5px;
            margin-bottom: 4px;
            align-items: center;
        }

        .field label {
            font-weight: bold;
            min-width: 50px;
            /* adjust as needed */
        }

        .field .value {
            flex: 1;
            border-bottom: 1px solid #000;
            min-height: 10px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <table style="width: 100%; border-collapse: collapse; font-size: 8px; color: #14489f">
        <tr valign="top">
            <!-- Left Column -->
            <td style="width: 40%; padding-right: 2px;">
                @include('pdf.partials.sender_info')
                @include('pdf.partials.goods_description')
                @include('pdf.partials.shipment_info')
            </td>

            <!-- Right Column -->
            <td style="width: 60%;">
                <!-- ROW 1: Tracking + Insurance and Office Info + Billing -->
                <table style="width: 100%; border-collapse: collapse; font-size: 8px;">
                    <tr valign="top">
                        <td style="width: 40%; padding-right: 2px;">
                            <div class="waybill-title section" style="font-size: 8px;">
                                <span
                                    style="color: #FF0000; font-size: 10px;">{{ $collection->waybill_no ?? 'TRACKING NO' }}</span><br>
                                WAYBILL/TRACKING NUMBER
                            </div>
                            @include('pdf.partials.insurance')
                            @include('pdf.partials.service_level')
                        </td>
                        <td style="width: 60%;">
                            <div class="section" style="text-align: right;">
                                <div class="logo-container">
                                    @php
                                        $image_url = $isPdf
                                            ? public_path('images/UCSLogo1.png')
                                            : asset('images/UCSLogo1.png');
                                    @endphp
                                    <img src="{{ $image_url }}" alt="Ufanisi Courier Services Logo">
                                </div>
                                <div class="section-body" style="font-size: 4px;">
                                    <b>HEAD OFFICE:</b> Pokomo Rd., off Shimo La Tewa Rd.,<br>
                                    off Lusaka Rd., Industrial Area<br>
                                    P.O. Box 44357 - 00100, Nairobi, Kenya<br>
                                    <b>Telephone:</b> +254 758 560 560, +254 761 508 560<br>
                                    <b>OUR OFFICE:</b> MOMBASA, BUNGOMA, NAKURU, ELDORET, KISUMU, KERICHO, KAKAMEGA,
                                    MACHAKOS, NYERI, NANYUKI, KILIFI, MALINDI, KITUI, BUSIA, MIGORI, HOMA BAY<br>
                                    <b>Email:</b> enquiries@ufanisicourier.co.ke
                                </div>
                            </div>
                            @include('pdf.partials.billing')
                            @include('pdf.partials.payment_mode')
                        </td>
                    </tr>
                    <!-- ROW 2: Receiver Info -->
                    <tr>
                        <td colspan="2">
                            @include('pdf.partials.receiver_info')
                        </td>
                    </tr>
                    <!-- ROW 3: Signatures -->
                    <tr>
                        <td colspan="2">
                            <!-- Courier Services Use + Amount Section -->
                            <table style="width: 100%; border-collapse: collapse; font-size: 8px; color: #14489F;">
                                <tr>
                                    <!-- Left Table: Ufanisi Courier Services Use -->
                                    <td style="width: 60%; vertical-align: top; padding: 0; padding-right: 2px;">
                                        <table
                                            style="width: 100%; border-collapse: collapse; font-size: 6px; color: #14489F;">
                                            <!-- Header Row -->
                                            <tr>
                                                <td colspan="3"
                                                    style="border: 1px solid #14489F; padding: 4px; font-weight: bold; background-color: #14489F; color: #FFFFFF">
                                                    UFANISI COURIER SERVICES USE
                                                </td>
                                            </tr>

                                            <!-- Signature Row with fillable space -->
                                            <tr>
                                                <!-- Received By -->
                                                <td style="border: 1px solid #14489F; padding: 4px;">
                                                    <div style="margin-bottom: 10px; height: 30px;">RECEIVED FOR UFANISI
                                                        COURIER SERVICES BY:</div>
                                                </td>

                                                <!-- Date -->
                                                <td style="border: 1px solid #14489F; padding: 4px; width: 25%;">
                                                    <div style="margin-bottom: 10px; height: 30px;">DATE</div>
                                                </td>

                                                <!-- Time -->
                                                <td style="border: 1px solid #14489F; padding: 4px; width: 25%;">
                                                    <div style="margin-bottom: 10px; height: 30px;">TIME</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <!-- Right Table: Amount Received + Invoice/MPESA -->
                                    <td style="width: 40%; vertical-align: top; padding: 0;">
                                        <table
                                            style="width: 100%; border-collapse: collapse; font-size: 6px; color: #14489F;">
                                            <tr>
                                                <!-- Amount Received -->
                                                <td style="border: 1px solid #14489F; padding: 4px; width: 50%;">
                                                    <span>AMOUNT RECEIVED</span>
                                                    <div
                                                        style="margin-top: 8px; font-weight: bold; font-size: 8px; height: 33px;">
                                                        Kshs. {{ number_format($collection->actual_total_cost, 2) }}
                                                    </div>
                                                </td>

                                                <!-- INVOICE Checkbox -->
                                                <td style="border: 1px solid #14489F; padding: 4px; width: 25%;">
                                                    <label style="display: inline-block;">
                                                        <img src="{{ $isPdf
                                                            ? public_path($collection->payment_mode === 'Invoice' ? 'images/tick.png' : 'images/box.png')
                                                            : asset($collection->payment_mode === 'Invoice' ? 'images/tick.png' : 'images/box.png') }}"
                                                            alt="Invoice"
                                                            style="height: 15px; vertical-align: middle; margin-right: 4px;"><br><br>
                                                        INVOICE
                                                    </label>
                                                    @if ($collection->payment_mode === 'Invoice' && $collection->reference)
                                                        <div style="font-size: 6px; margin-top: 2px; color: #000000">
                                                            {{ $collection->reference }}</div>
                                                    @endif
                                                </td>

                                                <!-- MPESA CODE Checkbox -->
                                                <td style="border: 1px solid #14489F; padding: 4px; width: 25%;">
                                                    <label style="display: inline-block;">
                                                        <img src="{{ $isPdf
                                                            ? public_path($collection->payment_mode === 'M-Pesa' ? 'images/tick.png' : 'images/box.png')
                                                            : asset($collection->payment_mode === 'M-Pesa' ? 'images/tick.png' : 'images/box.png') }}"
                                                            alt="MPESA"
                                                            style="height: 15px; vertical-align: middle; margin-right: 4px;"><br><br>
                                                        MPESA CODE
                                                    </label>
                                                    @if ($collection->payment_mode === 'M-Pesa' && $collection->reference)
                                                        <div
                                                            style="font-size: 6px; margin-top: 2px; color: #000000; font-weight: bold;">
                                                            {{ $collection->reference }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- Footer row for entire page width -->
        <tr>
            <td colspan="2" style="padding-top: 2px;">
                <div style="font-size: 6px; text-align: center; color: #14489F;">
                    CARRIAGE OF THIS SHIPMENT IS SUBJECT TO THE TERMS AND
                    CONDITIONS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong style="font-size: 7px;">M-PESA PAYBILL
                        NO: 820214</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WAYBILL/TRACKING NUMBER <strong
                        style="color: #FF0000; font-size: 8px;">{{ $collection->waybill_no ?? 'TRACKING NO' }}</strong>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
