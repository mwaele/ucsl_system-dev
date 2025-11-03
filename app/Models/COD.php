<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class COD extends Model
{

    protected $fillable = [
        'requestId',
        'collectedBy',
        'dateCollected',
        'amountCollected',
        'expectedAmount',
        'collectionBalance',
        'riderRemarks',
        'amountReceived',
        'receivedBy',
        'dateReceived',
        'receicedBalance',
        'receiverRemarks',
        'status',
    ];

    public function shipment()
    {
        return $this->belongsTo(ShipmentCollection::class, 'requestId', 'requestId');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collectedBy');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receivedBy');
    }
    
}
