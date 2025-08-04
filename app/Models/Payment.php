<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
            'type', //invoice/cash/mpesa/cheque
            'amount',
            'reference_no',
            'date_paid',
            'client_id',
            'shipment_collection_id',
            'status',
            'paid_by',
            'received_by',
            'verified_by',
    ];
}
