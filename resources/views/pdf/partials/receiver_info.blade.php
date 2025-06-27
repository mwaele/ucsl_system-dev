<div class="section">
  <div class="section-header">8. RECEIVER'S NAME AND PHYSICAL ADDRESS</div>
  <div class="section-body">
    <div class="row"><div class="col"><label>NAME:</label> <span class="value">{{ $collection->receiver_name ?? '' }}</span></div></div>
    <div class="row"><div class="col"><label>ADDRESS:</label> <span class="value">{{ $collection->receiver_address ?? '' }}</span></div></div>
    <div class="row"><div class="col"><label>CITY:</label> <span class="value">{{ $collection->receiver_town ?? '' }}</span></div></div>
    <div class="row">
      <div class="col"><label>CONTACT PERSON:</label> <span class="value">{{ $collection->receiver_contact_person ?? '' }}</span></div>
      <div class="col"><label>ID NUMBER:</label> <span class="value">{{ $collection->receiver_id_no ?? '' }}</span></div>
    </div>
    <div class="row">
      <div class="col"><label>TELEPHONE NO:</label> <span class="value">{{ $collection->receiver_phone ?? '' }}</span></div>
      <div class="col"><label>SIGNATURE:</label> <span class="value">____________</span></div>
    </div>
  </div>
</div>
