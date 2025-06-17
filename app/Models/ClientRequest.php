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
        'collected_by',
        'consignment_no',
        'created_by'
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

    public function shipmentCollection()
    {
        return $this->hasOne(ShipmentCollection::class,  'requestId', 'requestId');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
