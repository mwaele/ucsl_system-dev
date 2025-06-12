<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
        'requestId',
        'clientId',
        'clientRequestId',
        'current_status',
    ];
    public function trackingInfos()
    {
        return $this->hasMany(TrackingInfo::class, 'trackId');
    }
    

    public function client()
    {
        return $this->belongsTo(Client::class, 'clientId');
    }

    public function clientRequest()
    {
        return $this->belongsTo(ClientRequest::class, 'requestId', 'requestId');
    }
}
