<?php

namespace App\Constants;

/**
 * Stock threshold constants.
 */
class StockThreshold
{
    /** Default low stock threshold */
    public const LOW_STOCK_THRESHOLD = 10;

    /** Critical stock threshold */
    public const CRITICAL_STOCK_THRESHOLD = 5;

    /** Out of stock threshold */
    public const OUT_OF_STOCK = 0;

    /**
     * Check if stock is low.
     *
     * @param int $quantity
     * @return bool
     */
    public static function isLow(int $quantity): bool
    {
        return $quantity <= self::LOW_STOCK_THRESHOLD && $quantity > self::OUT_OF_STOCK;
    }

    /**
     * Check if stock is critical.
     *
     * @param int $quantity
     * @return bool
     */
    public static function isCritical(int $quantity): bool
    {
        return $quantity <= self::CRITICAL_STOCK_THRESHOLD && $quantity > self::OUT_OF_STOCK;
    }

    /**
     * Check if stock is out.
     *
     * @param int $quantity
     * @return bool
     */
    public static function isOut(int $quantity): bool
    {
        return $quantity <= self::OUT_OF_STOCK;
    }
}
