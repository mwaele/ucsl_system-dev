<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = [
        'sub_category_name',
        'description',
        'category_id'
    ];

}
