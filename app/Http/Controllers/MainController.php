<?php

namespace App\Http\Controllers;
use App\Models\Client;

use Illuminate\Http\Request;

class MainController extends Controller
{
public function clients($id)
{
    $client = Client::select('contact', 'kraPin', 'contact_person_id_no', 'city', 'name', 'address','special_rates_status')
        ->where('id', $id)
        ->first();

    if (!$client) {
        return response()->json(['message' => 'Client not found'], 404);
    }

    return response()->json($client);
}

}
