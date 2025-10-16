<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoadingSheetWaybill extends Model
{
    protected $fillable = [
        "loading_sheet_id",
        "waybill_no",
        "shipment_item_id",
        "shipment_id"
    ];

    public function loading_sheet()
    {
        return $this->belongsTo(LoadingSheet::class, 'loading_sheet_id');
    }
    
    public function shipment_collection()
    {
        return $this->belongsTo(ShipmentCollection::class, 'shipment_id');
    }

    public function shipment_item()
    {
        return $this->belongsTo(ShipmentItem::class, 'shipment_item_id');
    }
    public function shipmentItems()
    {
        return $this->hasMany(ShipmentItem::class, 'shipment_id', 'shipment_id');
    }

    public function shipmentItem()
    {
        return $this->belongsTo(ShipmentItem::class, 'shipment_item_id');
    }

    public function shipmentCollection()
    {
        return $this->belongsTo(ShipmentCollection::class, 'shipment_id');
    }

    public function loadingSheet()
    {
        return $this->belongsTo(LoadingSheet::class, 'loading_sheet_id');
    }
    
}
