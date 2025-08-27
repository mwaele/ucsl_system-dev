<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentCollection extends Model
{
    protected $fillable = [
        'receiver_name',
        'receiver_id_no',
        'receiver_phone',
        'receiver_address',
        'receiver_town',
        'client_id',
        'requestId',
        'origin_id',
        'destination_id',
        'cost',
        'sender_type',
        'sender_name',
        'sender_contact',
        'sender_address',
        'sender_town',
        'sender_id_no',
        'total_cost',
        'vat',
        'collected_by',
        'consignment_no',
        'waybill_no',
        'base_cost',
        'actual_cost',
        'actual_vat',
        'actual_total_cost',
        'verified_by',
        'verified_at',
        'sender_email',
        'receiver_email',
        'special_rates_status',
        'total_weight',
        'total_quantity',
        'manifest_generated_status',
        'status',
        'billing_party',
        'payment_mode',
        'reference',
        'priority_level',
        'fragile_item',
        'priority_level_amount',
        'fragile_item_amount',  
    ];

    public function clientRequest()
    {
        return $this->belongsTo(ClientRequest::class, 'requestId');
    }

    public function clientRequestById()
    {
        return $this->hasOne(ClientRequest::class, 'requestId', 'requestId');
    }

    public function items()
    {
        return $this->hasMany(ShipmentItem::class, 'shipment_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'origin_id');
    }

    public function destination()
    {
        return $this->belongsTo(Rate::class, 'destination_id');
    }
    public function special_destination()
    {
        return $this->belongsTo(SpecialRate::class, 'destination_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    
    public function track()
    {
        return $this->hasOne(Track::class, 'requestId', 'requestId');
    }


    public function collectedBy()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'shipment_collection_id');
    }

    public function loadingSheetWaybills()
    {
        return $this->hasOne(LoadingSheetWaybill::class, 'shipment_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'shipment_collection_id');
    }

}
