<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DebugHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $timeBeforeHandling = microtime(true);

        $response = $next($request);

        $executionTime = (microtime(true) - $timeBeforeHandling) * 1000;
        $memoryUsage = memory_get_peak_usage(true) / 1024;

        return $response
            ->header('X-Debug-Time', round($executionTime, 2) . ' ms')
            ->header('X-Debug-Memory', round($memoryUsage, 2) . ' KB');
    }
}
