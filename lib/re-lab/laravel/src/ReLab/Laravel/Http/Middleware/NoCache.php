<?php

namespace ReLab\Laravel\Http\Middleware;

use Closure;

/**
 * Class NoCache
 *
 * @package ReLab\Laravel\Http\Middleware
 */
class NoCache
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!$response->isRedirect()) {
            $response->header('Pragma', 'no-cache');
            $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->header('Expires', 'Fri, 1 Jan 2010 00:00:00 GMT');
        }

        return $response;
    }
}
