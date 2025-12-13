<?php

namespace App\Services;

use App\Models\ShipmentCollection;
use Illuminate\Support\Facades\DB;

class ConsignmentNumberService
{
    /**
     * Generate the next consignment number (CN-xxxxx)
     *
     * @return string
     * @throws \Exception
     */
    public function generate(): string
    {
        DB::beginTransaction();

        try {
            $latestConsignment = ShipmentCollection::where('consignment_no', 'LIKE', 'CN-%')
                ->orderByDesc('id')
                ->lockForUpdate() // prevents race conditions
                ->first();

            if ($latestConsignment && preg_match('/CN-(\d+)/', $latestConsignment->consignment_no, $matches)) {
                $nextNumber = ((int) $matches[1]) + 1;
            } else {
                $nextNumber = 10000; // Start from CN-10000
            }

            DB::commit();

            return 'CN-' . $nextNumber;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
