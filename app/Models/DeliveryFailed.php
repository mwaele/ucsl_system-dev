<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryFailed extends Model
{
    protected $fillable  = [
        'reason',
        'reference_code',
        'description'
    ];
}
