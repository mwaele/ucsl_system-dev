<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCancelledShipment extends Model
{
    protected $fillable = [
        'requestId',
        'reason',
        'cancelled_by',
        'status',
        'admin_comments',
        'reviewed_at',
        'reviewed_by',
    ];
}
