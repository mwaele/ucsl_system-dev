<div class="section">
  <div class="section-header">2. DESCRIPTION OF GOODS</div>
  <div class="section-body value">
    @foreach ($collection->items as $item)
      • {{ $item->item_name }}<br>
    @endforeach
    <table style="width: 100%; font-size: 8px; margin-top: 4px;">
      <tr>
        <td style="width: 50%;"><strong style="color: #14489F;">FROM:</strong> <span style="border-bottom: 1px solid #000; display: inline-block; width: 70%;" class="value">{{ $collection->office->name ?? '' }}</span></td>
        <td style="width: 50%;"><strong style="color: #14489F;">TO:</strong> <span style="border-bottom: 1px solid #000; display: inline-block; width: 70%;" class="value">{{ $collection->destination->destination ?? '' }}</span></td>
      </tr>
    </table>
  </div>
</div>
