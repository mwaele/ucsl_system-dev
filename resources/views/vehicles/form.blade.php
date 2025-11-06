<div class="row">
    <div class="col-md-4">
        <div class="form-group"><label>Reg No.</label>
            <input type="text" name="regNo" class="form-control" value="{{ $vehicle->regNo ?? '' }}" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group"><label>Type</label>
            <input type="text" name="type" class="form-control" value="{{ $vehicle->type ?? '' }}" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group"><label>Color</label>
            <input type="text" name="color" class="form-control" value="{{ $vehicle->color ?? '' }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group"><label>Tonnage</label>
            <input type="text" name="tonnage" class="form-control" value="{{ $vehicle->tonnage ?? '' }}" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group"><label>Status</label>
            <select name="status" class="form-control">
                <option value="">Select Status</option>
                @foreach (['available', 'intransit', 'garage'] as $status)
                    <option value="{{ $status }}" {{ (isset($vehicle) && $vehicle->status == $status) ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group"><label>Description</label>
            <input type="text" name="description" class="form-control" value="{{ $vehicle->description ?? '' }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group"><label>Default Driver</label>
            <select name="user_id" class="form-control">
                <option value="">Select Driver</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ (isset($vehicle) && $vehicle->user_id == $user->id) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group"><label>Owned By</label>
            <select name="ownedBy" class="form-control">
                <option value="">Select Company</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ (isset($vehicle) && $vehicle->ownedBy == $company->id) ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
