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

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
}
