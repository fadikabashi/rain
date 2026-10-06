<?php

namespace App\Services\Helpers;

use Illuminate\Support\Facades\Cache;

/**
 * Helper class for cache management.
 */
class CacheHelper
{
    /**
     * Cache keys used throughout the application.
     */
    public const CATEGORIES = 'categories';
    public const TYPES = 'types';
    public const MANFACTURERS = 'manfacturers';
    public const SLIDERS = 'sliders';
    public const ADS = 'ads';
    public const PRODUCT_STATISTICS = 'product_statistics';

    /**
     * Cache TTL in seconds.
     */
    public const TTL_SHORT = 300;   // 5 minutes
    public const TTL_MEDIUM = 1800; // 30 minutes
    public const TTL_LONG = 3600;   // 1 hour

    /**
     * Clear all application caches.
     *
     * @return void
     */
    public static function clearAll(): void
    {
        Cache::forget(self::CATEGORIES);
        Cache::forget(self::TYPES);
        Cache::forget(self::MANFACTURERS);
        Cache::forget(self::SLIDERS);
        Cache::forget(self::ADS);
        Cache::forget(self::PRODUCT_STATISTICS);
    }

    /**
     * Clear product-related caches.
     *
     * @return void
     */
    public static function clearProductCaches(): void
    {
        Cache::forget(self::PRODUCT_STATISTICS);
        // Note: Categories cache might need clearing if product count affects it
    }

    /**
     * Clear reference data caches (categories, types, manufacturers).
     *
     * @return void
     */
    public static function clearReferenceDataCaches(): void
    {
        Cache::forget(self::CATEGORIES);
        Cache::forget(self::TYPES);
        Cache::forget(self::MANFACTURERS);
    }

    /**
     * Clear sliders cache (used when slider images or data are updated in admin).
     *
     * @return void
     */
    public static function clearSlidersCache(): void
    {
        Cache::forget(self::SLIDERS);
    }

    /**
     * Clear ads cache (used when ad images or data are updated in admin).
     *
     * @return void
     */
    public static function clearAdsCache(): void
    {
        Cache::forget(self::ADS);
    }
}
