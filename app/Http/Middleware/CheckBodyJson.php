<?php

namespace App\Http\Middleware;

use Closure;

class CheckBodyJson
{
    const CONTENT_TYPE_INVALID = ['message' => 'Content-Type invalid'];
    const JSON_INVALID = ['message' => 'JSON invalid'];
    const JSON_EMPTY = ['message' => 'JSON doesn`t have data'];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */

     public function handle($request, Closure $next)
     {
         if(!$request->isJson()) return response()->json(self::CONTENT_TYPE_INVALID, 400);
         $body = json_encode($request->getContent(), true);
         if(json_last_error()) return response()->json(self::JSON_INVALID, 400);
         if(empty($body)) return response()->json(self::JSON_EMPTY, 400);

         return $next($request);
     }
}
