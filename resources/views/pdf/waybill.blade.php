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
      color: #000;
      margin: 0;
    }

    .waybill-container {
      width: 100%;
      padding: 8px;
      border: 1px solid #000;
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
      border: 1px solid #000;
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
      padding: 4px;
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
      border-bottom: 1px solid #000;
      display: inline-block;
      width: 100%;
      min-height: 10px;
    }

    .checkbox {
      display: inline-block;
      width: 9px;
      height: 9px;
      border: 1px solid #000;
      margin-right: 5px;
      vertical-align: middle;
    }

    .checked {
      background: #000;
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
  </style>
</head>
<body>
    <div class="waybill-container">
        <table style="width: 100%; border-collapse: collapse; font-size: 8px; color: #14489f">
            <tr valign="top">
                <!-- Left Column -->
                <td style="width: 40%; padding-right: 5px;">
                @include('pdf.partials.sender_info')
                @include('pdf.partials.goods_description')
                @include('pdf.partials.shipment_info')
                </td>

                <!-- Right Column -->
                <td style="width: 60%;">
                <!-- ROW 1: Tracking + Insurance and Office Info + Billing -->
                <table style="width: 100%; border-collapse: collapse; font-size: 8px;">
                    <tr valign="top">
                    <td style="width: 50%; padding-right: 2px;">
                        <div class="waybill-title" style="font-size: 9px;">
                        <span style="color: #FF0000; font-size: 14px;">{{ $collection->waybill_no ?? 'TRACKING NO' }}</span><br>
                        WAYBILL/TRACKING NUMBER
                        </div>
                        @include('pdf.partials.insurance')
                        @include('pdf.partials.service_level')
                    </td>
                    <td style="width: 50%;">
                        <div class="section" style="text-align: right;">
                          <div class="logo-container">
                              <img src="{{ public_path('images/UCSLogo1.png') }}" alt="Ufanisi Courier Services Logo">
                          </div>
                          <div class="section-body" style="font-size: 4px;">
                              <b>HEAD OFFICE:</b> Pokomo Rd., off Shimo La Tewa Rd.,<br>
                              off Lusaka Rd., Industrial Area<br>
                              P.O. Box 44357 - 00100, Nairobi, Kenya<br>
                              <b>Telephone:</b> +254 758 560 560, +254 761 508 560<br>
                              <b>OUR OFFICE:</b> MOMBASA, BUNGOMA, NAKURU, ELDORET, KISUMU, KERICHO, KAKAMEGA, MACHAKOS, NYERI, NANYUKI, KILIFI, MALINDI, KITUI, BUSIA, MIGORI, HOMA BAY<br>
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
                            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 8px;">
                              <tr>
                                <td style="width: 33%; text-align: center; border-top: 1px solid #000; padding-top: 4px;">
                                  Sender Signature<br>
                                  Date: {{ now()->format('d/m/Y') }}<br>
                                  Time: {{ now()->format('H:i') }}
                                </td>
                                <td style="width: 33%; text-align: center; border-top: 1px solid #000; padding-top: 4px;">
                                  Receiver Signature<br>
                                  Date: ____________<br>
                                  Time: ____________
                                </td>
                                <td style="width: 33%; text-align: center; border-top: 1px solid #000; padding-top: 4px;">
                                  Ufanisi Officer: {{ auth()->user()->name ?? '________' }}<br>
                                  Date: {{ now()->format('d/m/Y') }}<br>
                                  Time: {{ now()->format('H:i') }}
                                </td>
                              </tr>
                            </table>

                            <div class="footer">
                            PAYBILL: 820214 &nbsp;|&nbsp; WAYBILL NO: {{ $collection->waybill_no ?? '' }}<br>
                            SUBJECT TO TERMS AND CONDITIONS
                            </div>
                        </td>
                    </tr>
                </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
