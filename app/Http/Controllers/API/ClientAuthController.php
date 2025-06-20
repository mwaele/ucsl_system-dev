<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class ClientAuthController extends Controller
{
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email'    => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $client = Client::where('email', $request->email)->first();

    //     if (!$client || !Hash::check($request->password, $client->password)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     $token = $client->createToken('client-token')->plainTextToken;

    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'Bearer',
    //         'client'       => $client,
    //     ]);
    // }

    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::guard('client')->attempt([
        'email' => $request->email,
        'password' => $request->password,
    ])) {
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'redirect' => '/tracker',
        ]);
    }

    return response()->json(['message' => 'Invalid credentials'], 401);
}
}
