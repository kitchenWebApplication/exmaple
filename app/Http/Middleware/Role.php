<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $roleName
     * @return mixed
     */
    public function handle($request, Closure $next, string $roleName)
    {
        if (!$request->user()->hasRole($roleName)) {
            if ($request->wantsJson()) {
                return response()->jsonForbidden();
            } else {
                return;
            }
        }

        return $next($request);
    }
}
