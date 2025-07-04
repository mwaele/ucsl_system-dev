<div class="section">
  <div class="section-header">3. SHIPMENT INFORMATION</div>
  <div class="section-body">
    <table style="width: 100%; border-collapse: collapse; font-size: 8px;" border="1" cellpadding="2">
      <thead>
        <tr>
          <th>Packages</th>
          <th>Weight (Kg)</th>
          <th>Length</th>
          <th>Width</th>
          <th>Height</th>
        </tr>
      </thead>
      <tbody>
        @php
          $rows = $collection->items->take(10);
          $blankRows = 10 - $rows->count();
        @endphp

        @foreach ($rows as $item)
        <tr>
          <td style="height: 7px; min-height: 7px; color: #000; text-align: right;">{{ $item->actual_quantity }}</td>
          <td style="height: 7px; min-height: 7px; color: #000; text-align: right;">{{ $item->actual_weight }}</td>
          <td style="height: 7px; min-height: 7px; color: #000; text-align: right;">{{ $item->actual_length }}</td>
          <td style="height: 7px; min-height: 7px; color: #000; text-align: right;">{{ $item->actual_width }}</td>
          <td style="height: 7px; min-height: 7px; color: #000; text-align: right;">{{ $item->actual_height }}</td>
        </tr>
        @endforeach

        @for ($i = 0; $i < $blankRows; $i++)
        <tr>
          @for ($j = 0; $j < 5; $j++)
          <td style="position: relative; height: 7px;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
              <svg width="100%" height="100%">
                <line x1="0" y1="100%" x2="100%" y2="0" stroke="#555" stroke-width="1" />
              </svg>
            </div>
          </td>
          @endfor
        </tr>
        @endfor

        <tr style="font-weight: bold;">
          <td>Total</td>
          <td style="color: #000; text-align: right;">{{ $collection->items->sum('actual_weight') ?? '' }}</td>
          <td style="color: #000; text-align: right;">{{ $collection->items->sum('actual_length') ?? '' }}</td>
          <td style="color: #000; text-align: right;">{{ $collection->items->sum('actual_width') ?? '' }}</td>
          <td style="color: #000; text-align: right;">{{ $collection->items->sum('actual_height') ?? '' }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
