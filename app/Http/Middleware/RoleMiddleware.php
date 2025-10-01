<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  array  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (auth()->check() && in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        return abort(403, 'Unauthorized');
    }
}
