<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontOffice extends Model
{
    public function item()
    {
        return $this->belongsTo(ShipmentItem::class, 'item_name', 'packages_no', 'weight', 'volume');
    }

    public function request()
    {
        return $this->belongsTo(ClientRequest::class, 'dateRequested', 'clientId', 'collectionLocation');
    }

    public function shipmentCollection()
    {
        return $this->belongsTo(ShipmentCollection::class, 'status');
    }
}
