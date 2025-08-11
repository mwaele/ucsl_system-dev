<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentArrivalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'item_name',
        'actual_quantity',
        'actual_weight',
        'actual_length',
        'actual_width',
        'actual_height',
        'actual_volume',
        'remarks'
    ];

    public function arrival()
    {
        return $this->belongsTo(ShipmentArrival::class, 'shipment_arrival_id');
    }

    public function shipmentItem()
    {
        return $this->belongsTo(ShipmentItem::class, 'shipment_item_id');
    }
}
