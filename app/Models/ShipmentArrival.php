<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentArrival extends Model
{
    //
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
