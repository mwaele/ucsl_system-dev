<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SameDayRate extends Model
{
    protected $fillable = [
        'office_id',
        'bands',
        'additional_kg',
        'intercity_additional_kg',
        'rate',
        'destination',
        'applicableFrom',
        'applicableTo',
        'status',
        'approvalStatus',
        'dateApproved',
        'approvedBy',
        'added_by',
                
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
