<div class="section">
  <div class="section-header">1. SENDER'S NAME AND PHYSICAL ADDRESS</div>
  <div class="section-body">
    <div class="row"><div class="col"><label>NAME:</label> <span class="value">{{ $collection->sender_name ?? '' }}</span></div></div>
    <div class="row"><div class="col"><label>ADDRESS:</label> <span class="value">{{ $collection->sender_address ?? '' }}</span></div></div>
    <div class="row"><div class="col"><label>CITY:</label> <span class="value">{{ $collection->sender_town ?? '' }}</span></div></div>
    <div class="row">
      <div class="col"><label>CONTACT PERSON:</label> <span class="value">{{ $collection->sender_contact ?? '' }}</span></div>
      <div class="col"><label>ID NUMBER:</label> <span class="value">{{ $collection->sender_id_no ?? '' }}</span></div>
    </div>
    <div class="row">
      <div class="col"><label>TELEPHONE NO:</label> <span class="value">{{ $collection->sender_phone ?? '' }}</span></div>
      <div class="col"><label>SIGNATURE:</label> <span class="value">____________</span></div>
    </div>
  </div>
</div>
