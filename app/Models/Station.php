<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [ 
        'station_name',
        'station_prefix',
        'description',
        'status'
    ];
}
