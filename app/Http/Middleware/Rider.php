<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Rider
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Ensure user is logged in and has role rider or admin
        if (Auth::check() && (Auth::user()->role === 'driver' )) {
            return $next($request);
        }

        // Redirect if unauthorized
        return redirect()
            ->route('login')
            ->with('error', 'You must be logged in as a rider or admin to access this page.');
    }
}
