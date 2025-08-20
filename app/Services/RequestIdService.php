<?php

namespace App\Services;

use App\Models\ClientRequest;
use App\Models\ShipmentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RequestIdService
{
    /**
     * Generate a unique requestId across client_requests and shipment_collections.
     *
     * @return string
     * @throws \Exception
     */
    public function generate(): string
    {
        $castExpression = "CAST(SUBSTRING(requestId, 5) AS UNSIGNED)";

        DB::beginTransaction();
        try {
            $lastRequest = ClientRequest::latest('id')->first();

            if ($lastRequest && preg_match('/-(\d+)$/', $lastRequest->requestId, $matches)) {
                $lastNumber = (int) $matches[1];
                $nextNumber = $lastNumber + 1;
            } else {
                // Start from 10000 if no records exist
                $nextNumber = 10000;
            }

            // Generate random 3 uppercase letters
            $prefix = collect(range('A', 'Z'))
            ->random(3)                // pick 3 random letters
            ->implode('');


            // Build new requestId
            $requestId = $prefix . '-' . $nextNumber;

            DB::commit();
            return $requestId;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
