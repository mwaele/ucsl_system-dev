<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class FrontOffice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Ensure user is logged in and has role rider or admin
        if (Auth::check() && (Auth::user()->role === 'staff' )) {
            return $next($request);
        }

        // Redirect if unauthorized
        return redirect()
            ->route('login')
            ->withErrors(['message' => 'Access denied to this page. Contact Admin for more information']);
    }
}
