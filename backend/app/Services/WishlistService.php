<?php

namespace App\Services;

use App\Models\Wishlist;
use App\Models\Product;
use App\Services\LoggingService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WishlistService
{
    public function __construct(
        protected LoggingService $loggingService
    ) {}

    /**
     * Add product to wishlist.
     *
     * @param int $productId
     * @return bool
     */
    public function add(int $productId): bool
    {
        // Check if product exists
        $product = Product::find($productId);
        if (!$product) {
            return false;
        }

        $userId = Auth::id();
        $sessionId = $userId ? null : Session::getId();

        // Check if already in wishlist
        $exists = Wishlist::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->where('product_id', $productId)->exists();

        if ($exists) {
            return false; // Already in wishlist
        }

        Wishlist::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'product_id' => $productId,
        ]);

        $this->loggingService->logProduct('info', 'Product added to wishlist', [
            'product_id' => $productId,
            'user_id' => $userId,
            'session_id' => $sessionId,
        ]);

        return true;
    }

    /**
     * Remove product from wishlist.
     *
     * @param int $productId
     * @return bool
     */
    public function remove(int $productId): bool
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : Session::getId();

        $wishlist = Wishlist::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->where('product_id', $productId)->first();

        if ($wishlist) {
            $wishlist->delete();
            
            $this->loggingService->logProduct('info', 'Product removed from wishlist', [
                'product_id' => $productId,
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);
            
            return true;
        }

        return false;
    }

    /**
     * Check if product is in wishlist.
     *
     * @param int $productId
     * @return bool
     */
    public function isInWishlist(int $productId): bool
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : Session::getId();

        return Wishlist::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->where('product_id', $productId)->exists();
    }

    /**
     * Get all wishlist items.
     *
     * @param array $relations
     * @return Collection
     */
    public function getAll(array $relations = ['product.category', 'product.type', 'product.manfacturer']): Collection
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : Session::getId();

        $query = Wishlist::with($relations);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Get wishlist count.
     *
     * @return int
     */
    public function getCount(): int
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : Session::getId();

        $query = Wishlist::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query->count();
    }

    /**
     * Merge session wishlist with user wishlist after login.
     *
     * @param int $userId
     * @return void
     */
    public function mergeSessionWishlist(int $userId): void
    {
        $sessionId = Session::getId();
        
        // Get session wishlist items
        $sessionWishlist = Wishlist::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->get();

        foreach ($sessionWishlist as $item) {
            // Check if user already has this product in wishlist
            $exists = Wishlist::where('user_id', $userId)
                ->where('product_id', $item->product_id)
                ->exists();

            if (!$exists) {
                // Transfer to user
                $item->update([
                    'user_id' => $userId,
                    'session_id' => null,
                ]);
            } else {
                // Remove duplicate session item
                $item->delete();
            }
        }
    }
}
