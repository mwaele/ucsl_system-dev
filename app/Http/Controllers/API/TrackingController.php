<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Track;

class TrackingController extends Controller
{
     public function getTrackingByRequestId($requestId)
        {
            $track = Track::with('trackingInfos')
                ->where('requestId', $requestId)
                ->first();

            if (!$track) {
                return response()->json(['message' => 'Tracking not found'], 404);
            }

            return response()->json([
                'requestId' => $track->requestId,
                'tracking_history' => $track->trackingInfos
            ]);
        }
}
