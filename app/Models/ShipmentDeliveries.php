<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentDeliveries extends Model
{
    protected $fillable = [
        'requestId',
        'client_id',
        'receiver_name',
        'receiver_phone',
        'receiver_id_no',
        'receiver_type',
        'agent_name',
        'agent_phone',
        'agent_id_no',
        'delivery_location',
        'delivery_datetime',
        'delivered_by',
        'remarks'
    ];
}
