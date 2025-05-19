<?php

namespace App\Http\Controllers;
use App\Models\Client;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function clients()
    {
        return response()->json(Client::select('contact', 'kraPin','contact_person_id_no', 'city','name','address')->get());
    }
}
