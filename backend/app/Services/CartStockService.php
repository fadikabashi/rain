<?php

namespace App\Services;

use App\Models\Product;
use Hnooz\LaravelCart\Facades\Cart;

class CartStockService
{
    /**
     * Current cart quantity for a product (merged line).
     */
    public static function quantityInCart(int|string $productId): int
    {
        $needle = (string) $productId;

        foreach (Cart::all() as $item) {
            if ((string) ($item['id'] ?? '') === $needle) {
                return (int) ($item['quantity'] ?? 0);
            }
        }

        return 0;
    }

    /**
     * Total line quantity after merging an add operation.
     */
    public static function totalAfterAdd(int|string $productId, int $addQuantity): int
    {
        return self::quantityInCart($productId) + $addQuantity;
    }

    /**
     * Whether adding this many units keeps the line at or below available stock.
     */
    public static function canMergeAdd(Product $product, int $addQuantity): bool
    {
        if ($addQuantity < 1) {
            return false;
        }

        if (! $product->is_available) {
            return false;
        }

        return self::totalAfterAdd($product->id, $addQuantity) <= (int) $product->quantity;
    }

    /**
     * Whether the cart line may be set to this total (e.g. cart quantity update).
     */
    public static function canSetLineQuantity(Product $product, int $desiredTotal): bool
    {
        if ($desiredTotal < 1) {
            return false;
        }

        if (! $product->is_available) {
            return false;
        }

        return $desiredTotal <= (int) $product->quantity;
    }
}
