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

    public function client()
    {
        return $this->belongsTo(Client::class, 'clientId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicleId');
    }
}
