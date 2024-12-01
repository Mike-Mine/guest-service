<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = config('app.api_token');
        $requestToken = $request->header('Authorization');

        if (!$requestToken || $requestToken !== "Bearer $token") {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Invalid or missing API token'
            ], 401);
        }

        return $next($request);
    }
}
