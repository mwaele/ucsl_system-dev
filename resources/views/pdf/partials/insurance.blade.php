<div class="section">
  <div class="section-header">5. CONSENT TO PAY FOR INSURANCE</div>
  <div class="section-body">
    <div class="row">
      <div class="col">
        <span class="checkbox {{ $collection->insurance_consent === 'Yes' ? 'checked' : '' }}"></span> YES
        <span class="checkbox {{ $collection->insurance_consent === 'No' ? 'checked' : '' }}"></span> NO
      </div>
    </div>
    <div class="row">
      <div class="col"><label>Declared Value:</label> <span class="value">{{ $collection->declared_value ?? '' }}</span></div>
      <div class="col"><label>Insurance Amount:</label> <span class="value">{{ $collection->insurance_amount ?? '' }}</span></div>
    </div>
  </div>
</div>
