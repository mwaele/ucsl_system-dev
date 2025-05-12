<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientRequest;
use App\Models\Rate;
use App\Models\Office;

use Auth;

class MyCollectionController extends Controller
{
    public function show(){
        $offices = Office::all();
        $loggedInUserId = Auth::user()->id;
        $destinations = Rate::all();

        $collections = ClientRequest::where('userId',$loggedInUserId)->orderBy('created_at','desc')->get();
        return view('client-request.show')->with(['collections'=>$collections,'offices'=>$offices,'destinations'=>$destinations]);
    }
}
