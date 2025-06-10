<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SentMessage extends Model
{
    protected $table = 'sent_messages';

    protected $fillable = [
        'request_id',
        'client_id',
        'rider_id',
        'recipient_type',
        'recipient_name',
        'phone_number',
        'subject',
        'message',
    ];

    /**
     * Get the client associated with the message.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the rider (user) associated with the message.
     */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
