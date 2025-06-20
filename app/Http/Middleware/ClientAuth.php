<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('client')->check() && !Auth::guard('guest')->check()) {
        \Log::info('Redirecting unauthenticated client from: ' . $request->path());
        return redirect()->route('client_login')->withErrors(['message' => 'You must be logged in to access this page.']);
    }


        return $next($request);
    }
}

