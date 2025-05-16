<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentCollection extends Model
{
    protected $fillable = [
        'receiver_name',
        'receiver_contact_person',
        'receiver_id_no',
        'receiver_phone',
        'receiver_address',
        'receiver_town',
        'client_id',
        'requestId',
        'origin_id',
        'destination_id',
        'cost'
    ];

    public function clientRequest()
    {
        return $this->belongsTo(ClientRequest::class, 'requestId');
    }

    public function items()
    {
        return $this->hasMany(ShipmentItem::class, 'shipment_id');
    }
}
