<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingInfo extends Model
{
    protected $fillable = [
        'trackId',
        'date',
        'details',
        'qty',
        'weight',
        'volume',
        'remarks'
    ];

    public function track()
    {
        return $this->belongsTo(Track::class, 'trackId');
    }
}
