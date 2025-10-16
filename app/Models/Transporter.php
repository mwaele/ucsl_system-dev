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
        'signature',
        'transporter_type'
    ];
    public function trucks()
    {
        return $this->hasMany(TransporterTrucks::class);
    }
}
