<?php

namespace App\Http\Controllers;
use App\Models\Client;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function clients()
    {
        return response()->json(Client::select('contact', 'kraPin', 'city','name','address')->get());
    }
}
