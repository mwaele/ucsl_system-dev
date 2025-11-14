<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $role = Auth::user()->role;
        $article = in_array(strtolower($role[0]), ['a', 'e', 'i', 'o', 'u']) ? 'an' : 'a';

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ', ' . $article . ' ' . $role . ', logged into U-PARMS at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);
        
         if (Auth::user()->role === 'driver') {
                return redirect('/my_collections');
            }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
