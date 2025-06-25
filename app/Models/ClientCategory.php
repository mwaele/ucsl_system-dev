<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCategory extends Model
{
    protected $fillable = [
        'client_id',
        'category_id'
    ];
}
