<?php

namespace App\Http\Middleware;

use Closure;

class TokenCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $service)
    {
        if(is_array(config('security.' . $service))){
            $values = config('security.' . $service);

            if(!in_array($request->header('token'), $values) && !in_array($request->get('token'), $values)){
                abort(403);
            }

        } else if (($request->header('token') !== config('security.' . $service)) && ($request->get('token') !== config('security.' . $service))) {
            abort(403);
        }

        return $next($request);
    }
}
