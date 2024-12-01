<?php

namespace App\Http\Middleware;

use App\Enum\DebugHeaders as EnumDebugHeaders;
use App\Enum\Units;
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
            ->header(EnumDebugHeaders::TIME->value, round($executionTime, 2) . ' ' . Units::MILLISECOND->value)
            ->header(EnumDebugHeaders::MEMORY->value, round($memoryUsage, 2) . ' ' . Units::KILOBYTE->value);
    }
}
