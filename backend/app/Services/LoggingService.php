<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Service for structured logging with context.
 */
class LoggingService
{
    /**
     * Log order-related events.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function logOrder(string $level, string $message, array $context = []): void
    {
        Log::channel('orders')->{$level}($message, $this->enrichContext($context, 'order'));
    }

    /**
     * Log product-related events.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function logProduct(string $level, string $message, array $context = []): void
    {
        Log::channel('products')->{$level}($message, $this->enrichContext($context, 'product'));
    }

    /**
     * Log cart-related events.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function logCart(string $level, string $message, array $context = []): void
    {
        Log::channel('cart')->{$level}($message, $this->enrichContext($context, 'cart'));
    }

    /**
     * Log inventory-related events.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function logInventory(string $level, string $message, array $context = []): void
    {
        Log::channel('inventory')->{$level}($message, $this->enrichContext($context, 'inventory'));
    }

    /**
     * Log performance metrics.
     *
     * @param string $operation
     * @param float $duration
     * @param array $context
     * @return void
     */
    public function logPerformance(string $operation, float $duration, array $context = []): void
    {
        $context['operation'] = $operation;
        $context['duration_ms'] = round($duration * 1000, 2);
        $context['duration_seconds'] = round($duration, 4);

        Log::channel('performance')->info("Performance: {$operation}", $this->enrichContext($context, 'performance'));
    }

    /**
     * Log security-related events.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function logSecurity(string $level, string $message, array $context = []): void
    {
        Log::channel('security')->{$level}($message, $this->enrichContext($context, 'security'));
    }

    /**
     * Enrich context with common fields.
     *
     * @param array $context
     * @param string $category
     * @return array
     */
    protected function enrichContext(array $context, string $category): array
    {
        return array_merge([
            'category' => $category,
            'timestamp' => now()->toIso8601String(),
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ], $context);
    }
}
