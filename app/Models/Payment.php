<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
            'type', //invoice/cash/mpesa/cheque
            'amount',
            'reference_no',
            'date_paid',
            'client_id',
            'shipment_collection_id',
            'status',
            'paid_by',
            'received_by',
            'verified_by',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function shipment_collection()
    {
        return $this->belongsTo(ShipmentCollection::class, 'shipment_collection_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function getBalanceAttribute()
    {
        $totalCost = $this->shipment_collection?->total_cost ?? 0;
        return $totalCost - $this->amount;
    }
}
