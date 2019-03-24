<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!$request->user()->hasRole($roles)) {
            // abort(401, 'Unauthorized User.');
            \Alert::error('Unauthorized User', 'Error 401');
            return redirect()->intended('/');
        }
        return $next($request);
    }
}
