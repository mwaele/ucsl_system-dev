<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentSubItem extends Model
{
    protected $fillable = [
        'shipment_item_id', 'item_name', 'quantity', 'weight', 'length', 'width', 'height', 'volume', 'cost', 'remarks'
    ];

    public function shipmentItem()
    {
        return $this->belongsTo(ShipmentItem::class);
    }
}
