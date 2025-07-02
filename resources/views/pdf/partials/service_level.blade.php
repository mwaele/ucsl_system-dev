<div class="section">
  <div class="section-header">5. SERVICE LEVEL</div>
  <div class="section-body">
    @php
        $service = optional($collection->clientRequestById->serviceLevel)->sub_category_name;
        $isSameDay = strcasecmp(trim($service), 'Same Day') === 0;
        $isOvernight = strcasecmp(trim($service), 'Overnight') === 0;

        $tick = $isPdf ? public_path('images/tick.png') : asset('images/tick.png');
        $box = $isPdf ? public_path('images/box.png') : asset('images/box.png');
    @endphp

    <table style="width: 100%; font-size: 6px;">
      <tr>
        <td style="font-weight: bold; width: 50%;">Same Day Delivery</td>
        <td>
          <img src="{{ $isSameDay ? $tick : $box }}" alt="{{ $isSameDay ? 'Tick' : 'Box' }}" width="15">
        </td>
      </tr>
      <tr>
        <td style="font-weight: bold;">Overnight Delivery</td>
        <td>
          <img src="{{ $isOvernight ? $tick : $box }}" alt="{{ $isOvernight ? 'Tick' : 'Box' }}" width="15">
        </td>
      </tr>
    </table>
  </div>
</div>
