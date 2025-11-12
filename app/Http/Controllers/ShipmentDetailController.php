<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipmentCollection;
use App\Models\ClientRequest;
use App\Models\Track;
use Illuminate\Support\Facades\DB;  


class ShipmentDetailController extends Controller
{
    public function shipmentDetails($requestId)
    {
        // Get the main tracking info
        $track = Track::with([
                'client',
                'trackingInfos',
                'clientRequest.user',
                'clientRequest.vehicle',
                'clientRequest.serviceLevel', 
                'clientRequest.client'   
            ])
            ->where('requestId', $requestId)
            ->first();
        // Get shipment collection using the same requestId
        $shipment = DB::table('shipment_collections')
            ->where('requestId', $requestId)
            ->first();
        
        // If shipment exists, fetch origin office and destination name
        if ($shipment) {
            // Get origin office name
            $originOffice = DB::table('offices')
                ->where('id', $shipment->origin_id)
                ->value('name');
                

            // Get destination from rates
            $destinationName = DB::table('rates')
                ->where('id', $shipment->destination_id)
                ->value('destination');

            // get shipment items
            $shipment_items = DB::table('shipment_items')
                ->where('shipment_id', $shipment->id)
                ->get();



            // Attach them to the response
            $track->origin_office = $originOffice;
            $track->destination_name = $destinationName;
            $track->sender_name = $shipment->sender_name;
            $track->receiver_name = $shipment->receiver_name;
            $track->shipment_items = $shipment_items;
        }

        $deliveryType = $track->clientRequest->serviceLevel->sub_category_name ?? null;
        $clientType = $track->clientRequest->client->type ?? null;

        $label = '';
        if ($deliveryType && $clientType) {
            $formattedClientType = ucfirst(str_replace('_', ' ', $clientType));
            $label = "($deliveryType Delivery - $formattedClientType Client)";
        }

        $track->tracking_label = $label; 


        return view('shipments.details', compact('track'));
    }
}
