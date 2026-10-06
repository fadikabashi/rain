<?php

namespace App\Constants;

/**
 * Product status constants.
 */
class ProductStatus
{
    /** Product is available */
    public const AVAILABLE = true;

    /** Product is unavailable */
    public const UNAVAILABLE = false;

    /**
     * Get status label.
     *
     * @param bool $status
     * @return string
     */
    public static function label(bool $status): string
    {
        return $status ? 'Available' : 'Unavailable';
    }
}
