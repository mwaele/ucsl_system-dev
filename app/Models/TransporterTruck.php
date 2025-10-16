<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransporterTruck extends Model
{
  protected $fillable = [
    'reg_no',
    'driver_name',
    'driver_contact',
    'driver_id_no',
    'truck_type',
    'transporter_id',
    'status'
  ];
  public function transporter(){
    return $this->belongsTO('App\Models\Transporter');
  }

    public function loadingSheets()
    {
        return $this->hasMany(LoadingSheet::class, 'vehicle_reg_no', 'reg_no');
    }
}
