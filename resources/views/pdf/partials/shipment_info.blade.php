<div class="section">
  <div class="section-header">3. SHIPMENT INFORMATION</div>
  <div class="section-body">
    <table style="width: 100%; border-collapse: collapse;" border="1" cellpadding="2">
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
        <tr>
          <td>{{ $collection->items->sum('packages_no') ?? '' }}</td>
          <td>{{ $collection->items->sum('weight') ?? '' }}</td>
          <td>{{ $collection->items->sum('length') ?? '' }}</td>
          <td>{{ $collection->items->sum('width') ?? '' }}</td>
          <td>{{ $collection->items->sum('height') ?? '' }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
