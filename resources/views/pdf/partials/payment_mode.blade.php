<div class="section">
  <div class="section-header">7. PAYMENT MODE</div>
  <div class="section-body">
    <div class="row">
      <div class="col">
        <label>MPESA</label>
        <span class="checkbox {{ $collection->payment_mode === 'M-Pesa' ? 'checked' : '' }}"></span>
        <label>INVOICE</label>
        <span class="checkbox {{ $collection->payment_mode === 'Invoice' ? 'checked' : '' }}"></span>
      </div>
      <div class="col">
        <label>Reference:</label> <span class="value">{{ $collection->payment_reference ?? '' }}</span>
      </div>
    </div>
  </div>
</div>
