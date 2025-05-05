<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;
use Auth;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offices = Office::all();
        return view('offices.index')->with('offices',$offices);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('offices.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name'=>'required',                        
            'shortName'=>'required',                   
            'country'=>'required',                     
            'city'=>'required',                        
            'longitude'=>'nullable|string',                   
            'latitude'=>'nullable|string',                    
            'type'=>'required',                        
            'mpesaTill'=>'required',                   
            'mpesaPaybill'=>'nullable|string',
        ]);

        $office = new Office();
        $office->name = $validateData['name'];
        $office->createdBy = Auth::user()->id;
        $office->shortName = $validateData['shortName'];
        $office->country = $validateData['country'];
        $office->city = $validateData['city'];
        $office->longitude = $validateData['longitude'];
        $office->latitude = $validateData['latitude'];
        $office->type = $validateData['type'];
        $office->mpesaTill = $validateData['mpesaTill'];
        $office->mpesaPaybill = $validateData['mpesaPaybill'];

        $office->save();
        return redirect()->route('offices.index')->with('Success', 'Office saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Office $office)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Office $office)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Office $office)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $office = Office::find($id);
        $office->delete();
        return redirect()->route('offices.index')->with('Success', 'Office info deleted successfully.');
    }
}
