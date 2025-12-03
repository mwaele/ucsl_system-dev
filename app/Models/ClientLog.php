<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientLog extends Model
{
    protected $fillable = [
        'name',
        'actions',
        'url',
        'client_id',
        'reference_id',
        'table',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }   
}
