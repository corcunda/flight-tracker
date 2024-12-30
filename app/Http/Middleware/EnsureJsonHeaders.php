<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureJsonHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\Response|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only check Content-Type and Accept headers for non-GET requests
        if ($request->method() !== 'GET') {
            // Check if the Content-Type is application/json or contains application/json
            if (($request->header('Content-Type') !== 'application/json' && strpos($request->header('Content-Type'), 'application/json') === false) 
                || $request->header('Accept') !== 'application/json') {
                return response()->json([
                    'message' => 'Invalid headers. Please check the documentation.'
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        return $next($request);
    }
}
