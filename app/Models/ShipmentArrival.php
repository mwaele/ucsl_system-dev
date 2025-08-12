<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentArrival extends Model
{
    protected $fillable = [
        'shipment_collection_id',
        'requestId',
        'date_received',
        'verified_by',
        'cost',
        'vat_cost',
        'total_cost',
        'status',
        'driver_name',
        'vehicle_reg_no',
        'remarks'
    ];
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function clientRequest()
    {
        return $this->belongsTo(clientRequest::class, 'requestId');
    }

    public function shipmentCollection()
    {
        return $this->belongsTo(ShipmentCollection::class, 'shipment_collection_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'shipment_collection_id', 'shipment_collection_id');
    }
    public function transporter()
    {
        return $this->belongsTo(Transporter::class, 'transported_by');
    }
    public function transporter_truck()
    {
        return $this->belongsTo(TransporterTrucks::class, 'vehicle_reg_no');
    }


}
