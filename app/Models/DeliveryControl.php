<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class DeliveryControl extends Model
{
    use HasFactory;
    protected $fillable = [
        'control_id', 'details', 'ctr_time', 'route_id',
        'ctr_days', 'ctr_months', 'ctr_years'
    ];
}
