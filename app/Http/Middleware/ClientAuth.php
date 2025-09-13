<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Allow access if authenticated as client
        if (Auth::guard('client')->check()) {
            return $next($request);
        }

        // If you want to also allow "guest" logins
        if (Auth::guard('guest')->check()) {
            return $next($request);
        }

        // Otherwise redirect to client login
        \Log::info('Redirecting unauthenticated client from: ' . $request->path());

        return redirect()
            ->route('client_login')
            ->withErrors(['message' => 'You must be logged in to access this page.']);
    }
}

