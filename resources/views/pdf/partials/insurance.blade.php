<div class="section">
    <div class="section-header">4. CONSENT TO PAY FOR INSURANCE</div>
    <div class="section-body">
        <div class="row">
            <div class="col d-flex">
                <table>
                    <tr>
                        <td><input type="checkbox" name="insurance_consent" value="Yes"
                                {{ $collection->insurance_consent === 'Yes' ? 'checked' : '' }} class="me-3"></td>
                        <td><span>YES</span></td>
                        <td><input type="checkbox" name="insurance_consent" value="No"
                                {{ $collection->insurance_consent === 'No' ? 'checked' : '' }} class="me-3"></td>
                        <td><span>NO</span></td>
                    </tr>
                </table>
            </div>



        </div>
        <div class="row">
            <div class="col"><label>Declared Value:</label> <span
                    class="value">{{ $collection->declared_value ?? '' }}</span></div>
            <div class="col"><label>Insurance Amount:</label> <span
                    class="value">{{ $collection->insurance_amount ?? '' }}</span></div>
        </div>
    </div>
</div>
