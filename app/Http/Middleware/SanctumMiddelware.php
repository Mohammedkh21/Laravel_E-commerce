<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanctumMiddelware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$guard)
    {
        $user_guard = explode('\\',$request->user()->currentAccessToken()->tokenable_type);
        if( strcasecmp(end($user_guard) , $guard) ==0 ){
            return $next($request);
        }
        return abort(404);
    }
}
