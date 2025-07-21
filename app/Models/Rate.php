<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'approvedBy',
        'added_by',
        'routeFrom',
        'origin',
        'destination',
        'rate',
        'type',
        'applicableFrom',
        'applicableTo',
        'status',
        'approvalStatus',
        'dateApproved',
        'office_id',
        'zone_id',
        'zone'
    ];

    
    public function office()
    {
        return $this->belongsTo(Office::class);
    }
    
    public function zone_name()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }


}
