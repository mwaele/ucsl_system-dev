<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceRate extends Model
{
     use HasFactory;
    protected $fillable = [
        'service_name',
        'description',
        'rate',
        'client_id',
    ];

    // Relationship with Client
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
