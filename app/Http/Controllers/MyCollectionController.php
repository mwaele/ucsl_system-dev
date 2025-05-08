<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientRequest;
use Auth;

class MyCollectionController extends Controller
{
    public function show(){
        $loggedInUserId = Auth::user()->id;
        $collections = ClientRequest::where('userId',$loggedInUserId)->orderBy('created_at','desc')->get();
        return view('client-request.show')->with('collections', $collections);
    }
}
