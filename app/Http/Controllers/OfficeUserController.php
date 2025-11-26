<?php

namespace App\Http\Controllers;

use App\Models\OfficeUser;
use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $office_users = OfficeUser::all();
        $users = User::all();
        $offices = Office::all();
        return view('office_users.index', with(['office_users'=>$office_users, 'users'=>$users, 'offices'=>$offices]));
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
        $request->validate([
            'office_id' => 'required|exists:offices,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|max:255',
        ]);

        OfficeUser::create([
            'office_id' => $request->office_id,
            'user_id' => $request->user_id,
            'status' => $request->status,
        ]);

        return redirect()->route('office_users.index')->with('success', 'Office User assigned successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OfficeUser $officeUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfficeUser $officeUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfficeUser $officeUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfficeUser $officeUser)
    {
        //
    }
}
