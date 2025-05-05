<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'approvedBy',
        'added_by',
        'routeFrom',
        'zone',
        'origin',
        'destination',
        'rate',
        'applicableFrom',
        'applicableTo',
        'status',
        'approvalStatus',
        'dateApproved',
    ];
}
