<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientRequest extends Model
{
    protected $fillable = [
        'clientId',
        'collectionLocation',
        'parcelDetails',
        'dateRequested',
        'userId',
        'vehicleId',
        'requestId',
    ];
}
