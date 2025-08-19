<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'type', // invoice/cash/mpesa/cheque
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

    // If you want these auto-included on JSON/array:
    // protected $appends = ['total_paid', 'balance'];

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

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * Sum of ALL payments tied to this shipment_collection_id
     */
    public function getTotalPaidAttribute(): float|int
    {
        // If the collection + its payments are eager-loaded, use them (no extra query)
        if ($this->relationLoaded('shipment_collection') &&
            method_exists($this->shipment_collection, 'payments') &&
            $this->shipment_collection->relationLoaded('payments')) {
            return (float) $this->shipment_collection->payments->sum('amount');
        }

        // Fallback: sum directly from DB
        return (float) static::where('shipment_collection_id', $this->shipment_collection_id)->sum('amount');
    }

    /**
     * Balance = total_cost - total_paid (never below zero)
     */
    public function getBalanceAttribute(): float|int
    {
        $totalCost = (float) ($this->shipment_collection?->total_cost ?? 0);
        $balance = $totalCost - $this->total_paid;

        return $balance > 0 ? $balance : 0;
    }
}
