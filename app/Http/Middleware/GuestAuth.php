<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class GuestAuth
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('guest')->check()) {
            return redirect('/guest/login')->withErrors(['message' => 'You must login as a guest']);
        }

        return $next($request);
    }
}
