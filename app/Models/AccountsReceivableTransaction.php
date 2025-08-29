<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountsReceivableTransaction extends Model
{
    protected $table = 'accounts_receivables_transactions';

    protected $fillable = [
        'client_id', 'request_id', 'batch_no', 'waybill_no', 'reference',
        'details', 'date', 'datetime', 'posted_by', 'amount',
        'vat', 'total', 'dr', 'cr', 'type_of_transaction'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function request()
    {
        return $this->belongsTo(ClientRequest::class, 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function main()
    {
        return $this->belongsTo(AccountsReceivableMain::class, 'client_id', 'client_id');
    }
}
