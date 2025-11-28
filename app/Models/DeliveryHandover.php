<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryHandover extends Model
{
    protected $table = 'delivery_handovers';

    protected $fillable = [
        'requestId',
        'from_user_id ',
        'to_user_id ',
        'handover_time',
        'status ',
        'remarks ',
        'approved_at',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
