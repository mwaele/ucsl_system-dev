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
        'created_by',
        'category_id',
        'sub_category_id',
        'priority_level',
        'deadline_date',
        'rate_id',
        'office_id',
        'status',
        'delivery_rider_id',
        'fragile_item',
        'priority_level_amount',
        'fragile_item_amount',
        'source', 
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

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function serviceLevel()
    {
        return $this->hasOne(SubCategory::class, 'id', 'sub_category_id');
    }

    public function agent()
    {
        return $this->hasOne(Agent::class, 'request_id', 'requestId');
    }

}
