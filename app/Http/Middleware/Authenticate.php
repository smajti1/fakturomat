<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        dd($guard);

        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

        return $next($request);
    }
}