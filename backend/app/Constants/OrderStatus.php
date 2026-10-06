<?php

namespace App\Constants;

/**
 * Order status constants.
 */
class OrderStatus
{
    /** Order is pending/awaiting processing */
    public const PENDING = 0;

    /** Order has been received/confirmed */
    public const RECEIVED = 1;

    /** Order has been delivered */
    public const DELIVERED = 2;

    /** Order has been cancelled */
    public const CANCELLED = 3;

    /**
     * Get all status values.
     *
     * @return array<int>
     */
    public static function all(): array
    {
        return [
            self::PENDING,
            self::RECEIVED,
            self::DELIVERED,
            self::CANCELLED,
        ];
    }

    /**
     * Get status label.
     *
     * @param int $status
     * @return string
     */
    public static function label(int $status): string
    {
        return match ($status) {
            self::PENDING => 'Pending',
            self::RECEIVED => 'Received',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
            default => 'Unknown',
        };
    }

    /**
     * Check if status is valid.
     *
     * @param int $status
     * @return bool
     */
    public static function isValid(int $status): bool
    {
        return in_array($status, self::all(), true);
    }
}
