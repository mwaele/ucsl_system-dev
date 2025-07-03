<div class="section">
  <div class="section-header">2. DESCRIPTION OF GOODS</div>
  <div class="section-body value" style="padding: 4px;">
    @foreach ($collection->items as $item)
      • {{ $item->item_name }}<br>
    @endforeach
    <table style="width: 100%; font-size: 8px; margin-top: 4px;">
      <tr style>
        <td style="width: 60%;"><strong style="color: #14489F;">FROM:</strong> {{ $collection->office->name ?? '' }}</td>
        <td style="width: 40%;"><strong style="color: #14489F;">TO:</strong> {{ $collection->destination->destination ?? '' }}</td>
      </tr>
    </table>
  </div>
</div>
