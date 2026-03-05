<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class WaybillNumberService
{
    protected $prefix = 'UCSL';
    protected $suffix = 'KE';
    protected $padLength = 10;

    public function generate()
    {
        return DB::transaction(function () {

            $latestWaybill = DB::table('shipment_collections')
                ->whereNotNull('waybill_no')
                ->lockForUpdate()
                ->orderByDesc('waybill_no')
                ->value('waybill_no');

            $nextNumber = $latestWaybill
                ? (int) substr($latestWaybill, strlen($this->prefix), -strlen($this->suffix)) + 1
                : 1;

            return $this->prefix .
                str_pad($nextNumber, $this->padLength, '0', STR_PAD_LEFT) .
                $this->suffix;
        });
    }
}