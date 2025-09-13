<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('client.auth.login'); // create a client login blade
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('client')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended('/client/tracking');
        }

        return back()->withErrors(['email' => 'Invalid login details']);
    }

    public function logout()
    {
        Auth::guard('client')->logout();
        return redirect()->route('client.login');
    }
}