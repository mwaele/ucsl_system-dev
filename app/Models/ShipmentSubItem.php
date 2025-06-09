<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentSubItem extends Model
{
    protected $fillable = [
        'shipment_item_id', 'item_name','quantity', 'weight', 'remarks', 'length', 'width', 'height'
    ];

    public function shipmentItem()
    {
        return $this->belongsTo(ShipmentItem::class, 'shipment_item_id');
    }
}
