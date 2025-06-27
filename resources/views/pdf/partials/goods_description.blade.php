<div class="section">
  <div class="section-header">2. DESCRIPTION OF GOODS</div>
  <div class="section-body">
    @foreach ($collection->items as $item)
      • {{ $item->item_name }}<br>
    @endforeach
    <div class="row">
      <div class="col"><label>FROM:</label> <span class="value">{{ $collection->office->name ?? '' }}</span></div>
      <div class="col"><label>TO:</label> <span class="value">{{ $collection->destination->destination ?? '' }}</span></div>
    </div>
  </div>
</div>
