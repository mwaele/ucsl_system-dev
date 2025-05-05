<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    protected $fillable = [
        'company_name',
        'website',
        'location',
        'address',
        'pin',
        'contact',
        'email',
        'slogan',
        'logo'
    ];
}
