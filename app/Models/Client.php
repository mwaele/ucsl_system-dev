<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'accountNo',
        'name',
        'email',
        'password',
        'contact',
        'address',
        'city',
        'building',
        'country',
        'category',
        'contactPerson',
        'contactPersonPhone',
        'contactPersonEmail',
        'type',
        'industry',
        'kraPin',
        'postalCode',
        'status',
        'verificationCode',
    ];
}
