<?php

namespace App\Http\Middleware;

use App\Services\LoggingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to log request performance.
 */
class LogPerformance
{
    public function __construct(
        protected LoggingService $loggingService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $response = $next($request);

        $duration = microtime(true) - $startTime;
        $memoryUsed = memory_get_usage() - $startMemory;
        $memoryUsedMB = round($memoryUsed / 1024 / 1024, 2);

        // Only log slow requests (> 500ms) or high memory usage (> 50MB)
        if ($duration > 0.5 || $memoryUsedMB > 50) {
            $this->loggingService->logPerformance(
                $request->method() . ' ' . $request->path(),
                $duration,
                [
                    'method' => $request->method(),
                    'path' => $request->path(),
                    'status_code' => $response->getStatusCode(),
                    'memory_used_mb' => $memoryUsedMB,
                    'memory_peak_mb' => round(memory_get_peak_usage() / 1024 / 1024, 2),
                ]
            );
        }

        return $response;
    }
}
