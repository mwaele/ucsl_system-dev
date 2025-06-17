<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showSignIn()
    {
        return view('tracking.index');
    }

    public function processSignIn(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('client')->attempt($request->only('email', 'password'))) {
            return redirect()->intended('/tracker');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function showGuest()
    {
        return view('tracking.guest');
    }

    public function processGuest(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string', 'min:3'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9+\-\s]{7,15}$/'],
        ]);

        // Save guest to session or database (optional)
        session([
            'guest' => $request->only('name', 'email', 'phone')
        ]);

        return redirect('/dashboard')->with('success', 'Welcome, guest!');
    }
}
