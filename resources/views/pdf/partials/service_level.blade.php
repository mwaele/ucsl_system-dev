<div class="section">
  <div class="section-header">5. SERVICE LEVEL</div>
  <div class="section-body">
    <table style="width: 100%; font-size: 6px;">
      <tr>
        <td style="font-weight: bold; width: 50%;">Same Day Delivery</td>
        <td>
          @if (strcasecmp(trim(optional($collection->clientRequestById->serviceLevel)->sub_category_name), 'Same Day') === 0)
            <img src="{{ public_path('images/tick.png') }}" alt="Tick" width="10">
          @else
            <img src="{{ public_path('images/box.png') }}" alt="Box" width="10">
          @endif
        </td>
      </tr>
      <tr>
        <td style="font-weight: bold;">Overnight Delivery</td>
        <td>
          @if (strcasecmp(trim(optional($collection->clientRequestById->serviceLevel)->sub_category_name), 'Overnight') === 0)
            <img src="{{ public_path('images/tick.png') }}" alt="Tick" width="10">
          @else
            <img src="{{ public_path('images/box.png') }}" alt="Box" width="10">
          @endif
        </td>
      </tr>
    </table>
  </div>
</div>
