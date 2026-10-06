<?php

namespace App\Http\Middleware;

use App\Services\LoggingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to log slow database queries.
 */
class LogSlowQueries
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
        $slowQueryThreshold = config('logging.slow_query_threshold', 0.1); // 100ms default

        DB::listen(function ($query) use ($slowQueryThreshold) {
            $duration = $query->time / 1000; // Convert to seconds

            if ($duration > $slowQueryThreshold) {
                $this->loggingService->logPerformance(
                    'Slow Query',
                    $duration,
                    [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'connection' => $query->connectionName,
                    ]
                );
            }
        });

        return $next($request);
    }
}
