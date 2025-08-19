<?php

namespace App\Services;

use App\Models\ClientRequest;
use App\Models\ShipmentCollection;
use Illuminate\Support\Facades\DB;

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
            $lastRequestFromClient = ClientRequest::where('requestId', 'like', 'REQ-%')
                ->orderByRaw("$castExpression DESC")
                ->lockForUpdate()
                ->value('requestId');

            $lastRequestFromCollection = ShipmentCollection::where('requestId', 'like', 'REQ-%')
                ->orderByRaw("$castExpression DESC")
                ->lockForUpdate()
                ->value('requestId');

            $clientNumber = $lastRequestFromClient ? (int)substr($lastRequestFromClient, 4) : 0;
            $collectionNumber = $lastRequestFromCollection ? (int)substr($lastRequestFromCollection, 4) : 0;

            $nextNumber = max(max($clientNumber, $collectionNumber) + 1, 10000);

            $requestId = 'REQ-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            DB::commit();
            return $requestId;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
