<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = [
        'name',
        'actions',
        'url',
        'reference_id',
        'table',
    ];
}
