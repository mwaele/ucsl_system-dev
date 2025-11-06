@csrf
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Transporter Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required value="{{ $transporter->name ?? '' }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Phone Number (+254 ...) <span class="text-danger">*</span></label>
            <input type="text" name="phone_no" class="form-control" value="{{ $transporter->phone_no ?? '' }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Registration Details</label>
            <input type="text" name="reg_details" class="form-control" required value="{{ $transporter->reg_details ?? '' }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input type="text" name="email" class="form-control" required value="{{ $transporter->email ?? '' }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>CBV Number</label>
            <input type="text" name="cbv_no" class="form-control" value="{{ $transporter->cbv_no ?? '' }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Account No <span class="text-danger">*</span></label>
            <input type="text" name="account_no" class="form-control" readonly value="{{ $account_no ?? ($transporter->account_no ?? '') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Signature <span class="text-danger">*</span></label>
            <input type="file" name="signature" class="form-control">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Type <span class="text-danger">*</span></label>
            <select name="transporter_type" class="form-control">
                <option value="">Select Transporter Type</option>
                <option value="self" {{ (isset($transporter) && $transporter->transporter_type == 'self') ? 'selected' : '' }}>Self</option>
                <option value="third_party" {{ (isset($transporter) && $transporter->transporter_type == 'third_party') ? 'selected' : '' }}>Third Party</option>
            </select>
        </div>
    </div>
</div>
