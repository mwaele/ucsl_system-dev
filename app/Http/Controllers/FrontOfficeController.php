<?php

namespace App\Http\Controllers;

use App\Models\FrontOffice;
use App\Models\ShipmentCollection;
use App\Models\ShipmentItem;
use Illuminate\Http\Request;

class FrontOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $collections = ShipmentCollection::orderBy('created_at', 'desc')->get();
        $items = ShipmentItem::all();
        $client_requests = ClientRequest::all();              
        $clients = Client::all();
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        return view('front-office.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FrontOffice $frontOffice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FrontOffice $frontOffice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FrontOffice $frontOffice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FrontOffice $frontOffice)
    {
        //
    }
}
