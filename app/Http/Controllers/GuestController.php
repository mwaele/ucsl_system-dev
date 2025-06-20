<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|min:3|max:50',
        'phone' => 'required|string|size:12',
        'email' => 'required|email',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $guest = Guest::create($request->only('name', 'phone', 'email'));

    // 🔐 Log in guest using session
    Auth::guard('guest')->login($guest);
    $request->session()->regenerate();

    return response()->json([
        'message' => 'Guest logged in successfully.',
        'guest' => $guest
    ], 201);
}

    // public function store(Request $request)
    // {
    //      $validator = Validator::make($request->all(), [
    //     'name' => 'required|string|min:3|max:50',
    //     'phone' => 'required|string|size:12',
    //     'email' => 'required|email',
    // ]);

    // if ($validator->fails()) {
    //     return response()->json(['errors' => $validator->errors()], 422);
    // }

    // $guest = Guest::create($request->only('name', 'phone', 'email'));

    // // Generate access token using Laravel Sanctum
    // $token = $guest->createToken('guest-token')->plainTextToken;

    // return response()->json([
    //     'message' => 'Guest created successfully.',
    //     'token' => $token,
    //     'guest' => $guest
    // ], 201);
    // }

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
