<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transporter extends Model
{
    protected $fillable = [
        'name',
        'phone_no',
        'email',
        'reg_details',
        'account_no',
        'cbv_no',
        'signature'
    ];
}
