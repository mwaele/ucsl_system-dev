<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'amount',
        'due_date',
        'client_id',
        'shipment_collection_id',
        'invoiced_by',
        'status',
    ];

    public function shipment_collection_id()
    {
        return $this->belongsTo(ShipmentCollection::class, 'shipment_collection_id');
    }
}
