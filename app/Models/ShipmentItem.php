<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentItem extends Model
{
    protected $fillable = [
        'shipment_id',
        'item_name',
        'packages_no',
        'weight',
        'length',
        'width',
        'height',
        'volume',
    ];

    public function getVolumeAttribute()
    {
        if ($this->length && $this->width && $this->height) {
            return $this->length * $this->width * $this->height;
        }
        return null;
    }

    public function shipment()
    {
        return $this->belongsTo(ShipmentCollection::class, 'shipment_id');
    }
}
