<div class="section">
  <div class="section-header">7. PAYMENT MODE</div>
  <div class="section-body">
    <div class="row">
      <div class="col">
        <label style="margin-right: 4px;">MPESA</label>
        <img src="{{ $isPdf 
            ? public_path($collection->payment_mode === 'M-Pesa' ? 'images/tick.png' : 'images/box.png')
            : asset($collection->payment_mode === 'M-Pesa' ? 'images/tick.png' : 'images/box.png') 
          }}" 
          alt="MPESA" style="height: 15px; vertical-align: middle; margin-right: 4px;">

        <label style="margin-right: 4px;">INVOICE</label>
        <img src="{{ $isPdf 
            ? public_path($collection->payment_mode === 'Invoice' ? 'images/tick.png' : 'images/box.png')
            : asset($collection->payment_mode === 'Invoice' ? 'images/tick.png' : 'images/box.png') 
          }}" 
          alt="INVOICE" style="height: 15px; vertical-align: middle;">
      </div>
      <div class="col">
        <label>Reference:</label> <span class="value">{{ $collection->reference ?? '' }}</span>
      </div>
    </div>
  </div>
</div>
