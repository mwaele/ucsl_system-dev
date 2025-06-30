<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransporterTrucks extends Model
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
}
