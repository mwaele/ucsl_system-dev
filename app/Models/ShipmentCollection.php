<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentCollection extends Model
{
    protected $fillable = [
        'receiver_name',
        'receiver_id_no',
        'receiver_phone',
        'receiver_address',
        'receiver_town',
        'client_id',
        'requestId',
        'origin_id',
        'destination_id',
        'cost',
        'sender_type',
        'sender_name',
        'sender_contact',
        'sender_address',
        'sender_town',
        'sender_id_no',
        'total_cost',
        'vat',
    ];

    public function clientRequest()
    {
        return $this->belongsTo(ClientRequest::class, 'requestId');
    }

    public function items()
    {
        return $this->hasMany(ShipmentItem::class, 'shipment_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'origin_id');
    }

    public function destination()
    {
        return $this->belongsTo(Rate::class, 'destination_id');
    }
}
