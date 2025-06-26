<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ufanisi Courier Services - Waybill</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.2;
            background: white;
            color: black;
        }

        .waybill-container {
            max-width: 1200px;
            margin: 20px auto;
            border: 2px solid #000;
            background: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px;
            border-bottom: 1px solid #000;
        }

        .tracking-number {
            text-align: center;
            flex-grow: 1;
        }

        .tracking-number h1 {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 5px;
        }

        .tracking-subtitle {
            font-size: 14px;
            font-weight: bold;
        }

        .logo-section {
            text-align: right;
            max-width: 300px;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 5px;
        }

        .company-info {
            font-size: 10px;
            line-height: 1.3;
        }

        .waybill-footer {
            background: #3498db;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            text-align: center;
        }

        .form-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            grid-template-areas: 
                "left-col right-col"
                "receiver receiver";
        }

        .left-column {
            grid-area: left-col;
            padding: 0;
        }

        .right-column {
            grid-area: right-col;
            padding: 0;
        }

        .receiver-section {
            grid-area: receiver;
            width: 100%;
        }

        .section {
            border: 1px solid #000;
            margin: 0;
        }

        .section-header {
            background: #3498db;
            color: white;
            padding: 8px;
            font-weight: bold;
            font-size: 11px;
        }

        .section-content {
            padding: 10px;
        }

        .form-row {
            display: flex;
            margin-bottom: 8px;
            align-items: center;
        }

        .form-label {
            font-weight: bold;
            margin-right: 10px;
            min-width: 80px;
        }

        .form-input {
            flex: 1;
            border: none;
            border-bottom: 1px solid #000;
            padding: 2px 5px;
            font-size: 12px;
        }

        .description-box {
            width: 100%;
            height: 80px;
            border: 1px solid #000;
            padding: 5px;
            resize: none;
            font-family: inherit;
            font-size: 12px;
        }

        .shipment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .shipment-table th,
        .shipment-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 10px;
        }

        .shipment-table th {
            background: #f0f0f0;
            font-weight: bold;
        }

        .from-to-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
        }

        .from-to-box {
            border: 1px solid #000;
            padding: 10px;
            height: 60px;
        }

        .from-to-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .insurance-section {
            background: #f39c12;
            color: white;
        }

        .insurance-options {
            display: flex;
            gap: 20px;
            margin: 10px 0;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .checkbox {
            width: 15px;
            height: 15px;
            border: 2px solid #000;
            background: white;
        }

        .insurance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .insurance-table th,
        .insurance-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 10px;
            background: white;
            color: black;
        }

        .service-level {
            padding: 10px;
        }

        .service-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .billing-section {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .bottom-section {
            grid-column: 1 / -1;
            border-top: 1px solid #000;
        }

        .ufanisi-use-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 0;
            margin-top: 10px;
        }

        .ufanisi-left, .ufanisi-right {
            border: 1px solid #000;
            padding: 10px;
        }

        .ufanisi-header {
            background: #3498db;
            color: white;
            padding: 5px;
            font-weight: bold;
            margin: -10px -10px 10px -10px;
        }

        .payment-options {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .footer-info {
            text-align: center;
            padding: 10px;
            font-size: 10px;
            border-top: 1px solid #000;
        }

        .footer-tracking {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-top: 1px solid #000;
        }

        .footer-tracking-number {
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
        }
    </style>
    <link rel="stylesheet" href="waybill-styles.css">
</head>
<body>
    <div class="waybill-container">
        <!-- Header Section -->
        <div class="header">
            <div class="tracking-number">
                <h1>UF00096649KE</h1>
                <div class="tracking-subtitle">WAYBILL/TRACKING NUMBER</div>
            </div>
            <div class="logo-section">
                <div class="logo">Ufanisi &gt;&gt;</div>
                <div style="font-size: 14px; color: #f39c12; font-weight: bold;">COURIER SERVICES</div>
                <div class="company-info">
                    <strong>HEAD OFFICE:</strong> Pokomo Rd., off Shimo La Tewa Rd., off<br>
                    Lusaka Rd., Industrial Area<br>
                    P.O. Box 44357 - 00100, Nairobi, Kenya<br>
                    <strong>Telephone:</strong> +254 758 560 560, +254 761 508 560<br>
                    <strong>OUR OFFICE:</strong> MOMBASA, BUNGOMA, NAKURU, ELDORET,<br>
                    KISUMU, KERICHO, KAKAMEGA, MACHAKOS, NYERI,<br>
                    NANYUKI, KILIFI, MALINDI, KITUI, HOMABAY<br>
                    <strong>Email:</strong> enquiries@ufanisicourier.co.ke
                </div>
            </div>
        </div>

        <div class="waybill-footer">
            WAYBILL (Non-Negotiable)
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Section 1: Sender's Information -->
                <div class="section">
                    <div class="section-header">1. SENDER'S NAME AND PHYSICAL ADDRESS</div>
                    <div class="section-content">
                        <div class="form-row">
                            <span class="form-label">NAME:</span>
                            <input type="text" class="form-input">
                        </div>
                        <div class="form-row">
                            <span class="form-label">ADDRESS:</span>
                            <input type="text" class="form-input">
                        </div>
                        <div class="form-row">
                            <span class="form-label">CITY:</span>
                            <input type="text" class="form-input">
                        </div>
                        <div class="form-row">
                            <span class="form-label">NAME OF CONTACT PERSON:</span>
                            <input type="text" class="form-input" style="flex: 2;">
                            <span class="form-label" style="margin-left: 20px;">ID NUMBER:</span>
                            <input type="text" class="form-input">
                        </div>
                        <div class="form-row">
                            <span class="form-label">TELEPHONE NO:</span>
                            <input type="text" class="form-input">
                            <span class="form-label" style="margin-left: 20px;">STAMP/SIGNATURE OF SENDER:</span>
                            <input type="text" class="form-input">
                        </div>
                        <div class="form-row">
                            <span class="form-label">DATE:</span>
                            <input type="text" class="form-input">
                            <span class="form-label" style="margin-left: 20px;">TIME:</span>
                            <input type="text" class="form-input">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Description of Goods -->
                <div class="section">
                    <div class="section-header">2. DESCRIPTION OF GOODS</div>
                    <div class="section-content">
                        <textarea class="description-box"></textarea>
                        <div class="from-to-section">
                            <div class="from-to-box">
                                <div class="from-to-label">FROM:</div>
                            </div>
                            <div class="from-to-box">
                                <div class="from-to-label">TO:</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Shipment Information -->
                <div class="section">
                    <div class="section-header">3. SHIPMENT INFORMATION</div>
                    <div class="section-content">
                        <table class="shipment-table">
                            <thead>
                                <tr>
                                    <th rowspan="2">NO. OF<br>PACKAGES</th>
                                    <th colspan="2">TOTAL ACTUAL WEIGHT</th>
                                    <th colspan="3">DIMENSIONAL WEIGHT</th>
                                </tr>
                                <tr>
                                    <th>Kilos</th>
                                    <th>Grams</th>
                                    <th>Length</th>
                                    <th>Width</th>
                                    <th>Height</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                <tr style="font-weight: bold;"><td>TOTAL</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Section 4: Insurance -->
                <div class="section">
                    <div class="section-header insurance-section">4. CONSENT TO PAY FOR INSURANCE</div>
                    <div class="section-content">
                        <div class="insurance-options">
                            <div class="checkbox-group">
                                <span style="font-weight: bold;">YES</span>
                                <div class="checkbox"></div>
                            </div>
                            <div class="checkbox-group">
                                <span style="font-weight: bold;">NO</span>
                                <div class="checkbox"></div>
                            </div>
                        </div>
                        <div style="font-size: 10px; margin: 10px 0;">
                            <strong>IF YES, DECLARED VALUE FOR CARRIAGE - ADDITIONAL CHARGES APPLIES</strong><br>
                            <strong>(SEE TERMS AND CONDITIONS OVERLEAF CLAUSE 7)</strong>
                        </div>
                        <table class="insurance-table">
                            <thead>
                                <tr>
                                    <th>Currency</th>
                                    <th>Value</th>
                                    <th>Insurance @1%</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section 5: Service Level -->
                <div class="section">
                    <div class="section-header">5. SERVICE LEVEL</div>
                    <div class="service-level">
                        <div class="service-options">
                            <div class="checkbox-group">
                                <span>Same Day Delivery</span>
                                <div class="checkbox"></div>
                            </div>
                            <div class="checkbox-group">
                                <span>Overnight Delivery</span>
                                <div class="checkbox"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 6: Billing Instructions -->
                <div class="section">
                    <div class="section-header">6. BILLING INSTRUCTIONS</div>
                    <div class="section-content">
                        <div class="billing-section">
                            <div class="checkbox-group">
                                <span style="font-weight: bold;">RECEIVER TO PAY</span>
                                <div class="checkbox"></div>
                            </div>
                            <div class="checkbox-group">
                                <span style="font-weight: bold;">SENDER TO PAY</span>
                                <div class="checkbox"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 7: Special Instructions -->
                <div class="section">
                    <div class="section-header">7. SPECIAL INSTRUCTIONS</div>
                    <div class="section-content" style="height: 60px;">
                        <!-- Empty space for special instructions -->
                    </div>
                </div>
            </div>

            <!-- Section 8: Receiver's Information (Full Width) -->
            <div class="receiver-section">
                <div class="section">
                    <div class="section-header">8. RECEIVER'S NAME AND PHYSICAL ADDRESS</div>
                    <div class="section-content">
                        <div class="form-row">
                            <span class="form-label">NAME:</span>
                            <input type="text" class="form-input">
                        </div>
                        <div class="form-row">
                            <span class="form-label">ADDRESS:</span>
                            <input type="text" class="form-input">
                        </div>
                        <div class="form-row">
                            <span class="form-label">CITY:</span>
                            <input type="text" class="form-input">
                        </div>
                        <div class="form-row">
                            <span class="form-label">NAME OF CONTACT PERSON:</span>
                            <input type="text" class="form-input" style="flex: 2;">
                            <span class="form-label" style="margin-left: 20px;">ID NUMBER:</span>
                            <input type="text" class="form-input">
                        </div>
                        <div class="form-row">
                            <span class="form-label">TELEPHONE NO:</span>
                            <input type="text" class="form-input">
                            <span class="form-label" style="margin-left: 20px;">STAMP/SIGNATURE OF SENDER:</span>
                            <input type="text" class="form-input">
                        </div>
                        <div class="form-row">
                            <span class="form-label">DATE:</span>
                            <input type="text" class="form-input">
                            <span class="form-label" style="margin-left: 20px;">TIME:</span>
                            <input type="text" class="form-input">
                        </div>

                        <!-- Ufanisi Use Section -->
                        <div class="ufanisi-use-section">
                            <div class="ufanisi-left">
                                <div class="ufanisi-header">UFANISI COURIER SERVICES USE</div>
                                <div class="form-row">
                                    <span class="form-label">RECEIVED FOR UFANISI COURIER SERVICES BY</span>
                                </div>
                                <div class="form-row" style="margin-top: 20px;">
                                    <span class="form-label">DATE</span>
                                    <span class="form-label" style="margin-left: 40px;">TIME</span>
                                </div>
                            </div>
                            <div class="ufanisi-right">
                                <div class="ufanisi-header">AMOUNT RECEIVED</div>
                                <div class="payment-options">
                                    <div class="checkbox-group">
                                        <div class="checkbox"></div>
                                        <span>INVOICE</span>
                                    </div>
                                    <div class="checkbox-group">
                                        <div class="checkbox"></div>
                                        <span>MPESA CODE</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Information -->
        <div class="footer-info">
            CARRIAGE OF THIS SHIPMENT IS SUBJECT TO THE TERMS AND CONDITIONS OVERLEAF.
        </div>

        <!-- Footer Tracking -->
        <div class="footer-tracking">
            <div><strong>PAYBILL NO:</strong> 820214</div>
            <div>
                <strong>WAYBILL/TRACKING NUMBER</strong>
                <span class="footer-tracking-number">UF00096649KE</span>
            </div>
        </div>
    </div>
</body>
</html>
