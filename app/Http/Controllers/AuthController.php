<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\UserLog;

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
        if(auth('client')->user()->name ?? ''){
            $table = 'clients';
            $id = auth('client')->user()->id;
        }else if(auth('guest')->user()->name){
            $table = 'guests';
            $id = auth('guest')->user()->id;
        }
        UserLog::create([
        'name' => auth('client')->user()->name ?? auth('guest')->user()->name,
        'actions' => 'Logged in the tracking app',
        'url' => $request->fullUrl(),
        'reference_id' => $id,
        'table' => $table,
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

         if(auth('client')->user()->name ?? ''){
            $table = 'clients';
            $id = auth('client')->user()->id;
        }else if(auth('guest')->user()->name){
            $table = 'guests';
            $id = auth('guest')->user()->id;
        }
        UserLog::create([
        'name' => auth('client')->user()->name ?? auth('guest')->user()->name,
        'actions' => 'Logged in the tracking app',
        'url' => $request->fullUrl(),
        'reference_id' => $id,
        'table' => $table,
    ]);

        return redirect('/dashboard')->with('success', 'Welcome, guest!');
    }

    public function logout(Request $request)
    {
         if(auth('client')->user()->name ?? ''){
            $table = 'clients';
            $id = auth('client')->user()->id;
        }else if(auth('guest')->user()->name){
            $table = 'guests';
            $id = auth('guest')->user()->id;
        }
        UserLog::create([
        'name' => auth('client')->user()->name ?? auth('guest')->user()->name,
        'actions' => 'Logged out the tracking app',
        'url' => $request->fullUrl(),
        'reference_id' => $id,
        'table' => $table,
    ]);
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }
}
