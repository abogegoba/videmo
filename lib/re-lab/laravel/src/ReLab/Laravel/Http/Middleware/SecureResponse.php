<?php

namespace ReLab\Laravel\Http\Middleware;

use Closure;

/**
 * Class SecureResponse
 *
 * @package ReLab\Laravel\Http\Middleware
 */
class SecureResponse
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
        $response->headers->set('X-Frame-Options', 'DENY');
        return $response;
    }
}
