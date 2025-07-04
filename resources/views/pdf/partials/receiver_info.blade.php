<div class="section">
  <div class="section-header">8. RECEIVER'S NAME AND PHYSICAL ADDRESS</div>
  <div class="section-body">
    <div class="row"><div class="col"><label>NAME:</label> <span class="value">{{ $collection->receiver_name ?? '' }}</span></div></div>
    <div class="row"><div class="col"><label>ADDRESS:</label> <span class="value">{{ $collection->receiver_address ?? '' }}</span></div></div>
    <div class="row"><div class="col"><label>CITY:</label> <span class="value">{{ $collection->receiver_town ?? '' }}</span></div></div>
    <table style="width: 100%; font-size: 7px;">
      <tr>
        <td style="width: 60%; color: #000000"><strong style="color: #14489F;">NAME OF CONTACT PERSON:</strong> {{ $collection->receiver_name ?? '' }} </td>
        <td style="width: 40%; color: #000000"><strong style="color: #14489F;">ID NUMBER:</strong> {{ $collection->receiver_id_no ?? '' }} </td>
      </tr>
    </table>
    <table style="width: 100%; font-size: 7px;">
      <tr>
        <td style="width: 50%; color: #000000"><strong style="color: #14489F;">TELEPHONE NO:</strong> {{ $collection->receiver_phone ?? '' }} </td>
        <td style="width: 50%;" ><strong style="color: #14489F;">SIGNATURE:</strong> </td>
      </tr>
    </table>
  </div>
</div>
