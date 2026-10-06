<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring Settings
    |--------------------------------------------------------------------------
    |
    | Configure performance monitoring thresholds and settings.
    |
    */

    'slow_query_threshold' => env('SLOW_QUERY_THRESHOLD', 0.1), // seconds
    'enable_performance_logging' => env('ENABLE_PERFORMANCE_LOGGING', false),
    'enable_slow_query_logging' => env('ENABLE_SLOW_QUERY_LOGGING', false),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

        // Custom channels for application-specific logging
        'orders' => [
            'driver' => 'daily',
            'path' => storage_path('logs/orders.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30, // Keep order logs for 30 days
        ],

        'products' => [
            'driver' => 'daily',
            'path' => storage_path('logs/products.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30,
        ],

        'cart' => [
            'driver' => 'daily',
            'path' => storage_path('logs/cart.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14, // Keep cart logs for 14 days
        ],

        'inventory' => [
            'driver' => 'daily',
            'path' => storage_path('logs/inventory.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30,
        ],

        'performance' => [
            'driver' => 'daily',
            'path' => storage_path('logs/performance.log'),
            'level' => env('LOG_LEVEL', 'info'),
            'days' => 7, // Keep performance logs for 7 days
        ],

        'security' => [
            'driver' => 'daily',
            'level' => env('LOG_LEVEL', 'warning'),
            'path' => storage_path('logs/security.log'),
            'days' => 90, // Keep security logs for 90 days
        ],
    ],

];
