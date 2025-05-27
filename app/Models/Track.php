<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
        'requestId',
        'clientId',
        'clientRequestId'
    ];
    public function trackingInfos()
    {
        return $this->hasMany(TrackingInfo::class, 'trackId');
    }
}
