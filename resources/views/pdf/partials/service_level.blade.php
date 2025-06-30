<div class="section">
  <div class="section-header">5. SERVICE LEVEL</div>
  <div class="section-body">
    <div class="row">
      <div class="col">
        <label>Same Day Delivery</label>
        <span class="checkbox {{ $collection->service_level === 'Same Day' ? 'checked' : '' }}"></span>
      </div>
      <div class="col">
        <label>Overnight Delivery</label>
        <span class="checkbox {{ $collection->service_level === 'Overnight' ? 'checked' : '' }}"></span>
      </div>
    </div>
  </div>
</div>
