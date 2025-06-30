<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoadingSheet extends Model
{
    protected $fillable = [
        'dispatcher_id',
        'office_id',                                 
        'station_id',                                
        'destination',
        'batch_no',
        'dispatched_by',
        'transported_by',
        'transporter_phone',
        'reg_details',
        'transporter_signature',
        'vehicle_reg_no',
        'received_by',
        'receiver_ph',
        'receiver_id_no',
        'received_date',
        'receiver_signature   ',
        'date_closed  ',
        'remarks    ',
        'description' 
    ];
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
    public function transporter()
    {
        return $this->belongsTo(Transporter::class, 'transported_by');
    }
    public function dispatcher()
    {
        return $this->belongsTo(Dispatcher::class, 'dispatcher_id');
    }
    public function transporter_truck()
    {
        return $this->belongsTo(TransporterTrucks::class, 'vehicle_reg_no');
    }
    public function destination()
    {
        return $this->belongsTo(Rate::class, 'destination');
    }
    public function special_destination()
    {
        return $this->belongsTo(SpecialRate::class, 'destination');
    }
}
