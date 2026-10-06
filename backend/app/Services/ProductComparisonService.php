<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCompatibility;
use Illuminate\Support\Facades\Session;

class ProductComparisonService
{
    /**
     * Maximum products that can be compared.
     */
    const MAX_COMPARISON = 4;

    /**
     * Get comparison list from session.
     *
     * @return array
     */
    public function getComparisonList(): array
    {
        return Session::get('product_comparison', []);
    }

    /**
     * Add product to comparison.
     *
     * @param int $productId
     * @return bool
     */
    public function addToComparison(int $productId): bool
    {
        $comparison = $this->getComparisonList();

        // Check if already in comparison
        if (in_array($productId, $comparison)) {
            return false;
        }

        // Check max limit
        if (count($comparison) >= self::MAX_COMPARISON) {
            return false;
        }

        // Add to comparison
        $comparison[] = $productId;
        Session::put('product_comparison', $comparison);

        return true;
    }

    /**
     * Remove product from comparison.
     *
     * @param int $productId
     * @return bool
     */
    public function removeFromComparison(int $productId): bool
    {
        $comparison = $this->getComparisonList();
        $key = array_search($productId, $comparison);

        if ($key !== false) {
            unset($comparison[$key]);
            $comparison = array_values($comparison); // Re-index
            Session::put('product_comparison', $comparison);
            return true;
        }

        return false;
    }

    /**
     * Clear comparison list.
     *
     * @return void
     */
    public function clearComparison(): void
    {
        Session::forget('product_comparison');
    }

    /**
     * Get comparison products.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getComparisonProducts()
    {
        $comparison = $this->getComparisonList();

        if (empty($comparison)) {
            return collect([]);
        }

        return Product::with(['category', 'manfacturer', 'type', 'specifications'])
            ->whereIn('id', $comparison)
            ->get();
    }

    /**
     * Get comparison count.
     *
     * @return int
     */
    public function getComparisonCount(): int
    {
        return count($this->getComparisonList());
    }

    /**
     * Check if product is in comparison.
     *
     * @param int $productId
     * @return bool
     */
    public function isInComparison(int $productId): bool
    {
        return in_array($productId, $this->getComparisonList());
    }

    /**
     * Get compatible products for a product.
     *
     * @param int $productId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCompatibleProducts(int $productId)
    {
        return ProductCompatibility::with('compatibleProduct')
            ->where('product_id', $productId)
            ->orderBy('sort_order')
            ->get();
    }
}
