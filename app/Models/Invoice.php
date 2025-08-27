<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    public function shipment_collection()
    {
        return $this->belongsTo(ShipmentCollection::class, 'shipment_collection_id');
    }

    public function loading_sheet_waybills()
    {
        return $this->hasOne(LoadingSheetWaybill::class, 'shipment_id', 'shipment_collection_id');
    }

}
