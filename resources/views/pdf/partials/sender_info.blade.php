<div class="section">
  <div class="section-header">1. SENDER'S NAME AND PHYSICAL ADDRESS</div>
  <div class="section-body">
    <div class="row"><div class="col"><label>NAME:</label> <span class="value">{{ $collection->sender_name ?? '' }}</span></div></div>
    <div class="row"><div class="col"><label>ADDRESS:</label> <span class="value">{{ $collection->sender_address ?? '' }}</span></div></div>
    <div class="row"><div class="col"><label>CITY:</label> <span class="value">{{ $collection->sender_town ?? '' }}</span></div></div>
    <table style="width: 100%; font-size: 6px;">
      <tr style="border-right: 1px solid #14489F;">
        <td style="width: 60%;"><strong>NAME OF CONTACT PERSON:</strong> <span style="border-bottom: 1px solid #000; font-size: 7px; display: inline-block; width: 70%;">{{ $collection->sender_name ?? '' }}</span></td>
        <td style="width: 40%;"><strong>ID NUMBER:</strong> <span style="border-bottom: 1px solid #000; display: inline-block; width: 70%;">{{ $collection->sender_id_no ?? '' }}</span></td>
      </tr>
    </table>
    <table style="width: 100%; font-size: 6px;">
      <tr>
        <td style="width: 50%;"><strong>TELEPHONE NO:</strong> <span style="border-bottom: 1px solid #000; display: inline-block; width: 70%;">{{ $collection->sender_contact ?? '' }}</span></td>
        <td style="width: 50%;"><strong>SIGNATURE:</strong><br><span style="border-bottom: 1px solid #000; display: inline-block; width: 70%;"></span></td>
      </tr>
    </table>
  </div>
</div>
