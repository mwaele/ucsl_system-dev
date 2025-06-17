<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // $validatedData = $request->validate([
        //     'name' => 'required|string|max:50',
        //     'phone' => 'required|digits:12',
        //     'email' => 'required|email',
        // ]);

        // // Store the guest
        // $guest = Guest::create($validatedData);

        // $token = $guest->createToken('client-token')->plainTextToken;

        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        //     'client'       => $client,
        // ]);

        // return redirect()->route('tracker')->with('Success', 'Guest Saved Successfully');

         $validator = Validator::make($request->all(), [
        'name' => 'required|string|min:3|max:50',
        'phone' => 'required|string|size:12',
        'email' => 'required|email',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $guest = Guest::create($request->only('name', 'phone', 'email'));

    // Generate access token using Laravel Sanctum
    $token = $guest->createToken('guest-token')->plainTextToken;

    return response()->json([
        'message' => 'Guest created successfully.',
        'token' => $token,
        'guest' => $guest
    ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Guest $guest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guest $guest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
        //
    }
}
