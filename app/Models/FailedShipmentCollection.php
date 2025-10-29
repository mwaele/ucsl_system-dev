<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedShipmentCollection extends Model
{
    protected $fillable  = [
        'requestId',
        'user_id',
        'failed_collection_id',
        'remarks'
    ];
}
