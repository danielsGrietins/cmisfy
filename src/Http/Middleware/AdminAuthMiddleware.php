<?php

namespace Cmsify\Cmsify\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuthMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth('cmsify-api')->user()) {
            return abort(401);
        }

        return $next($request);
    }
}
