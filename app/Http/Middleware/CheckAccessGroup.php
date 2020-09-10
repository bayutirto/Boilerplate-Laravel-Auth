<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\Access;

class CheckAksesGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $group = null)
    {
        $akses = new Access();

        if (is_null($group))
        {
            if (!$akses->isLogin()) return response()->json($akses->getError(), 401);
        }
        else if (!$akses->in_user($group)) return response()->json($akses->getError(), 401);

        return $next($request);
    }
}
