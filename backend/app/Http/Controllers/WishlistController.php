<?php

namespace App\Http\Controllers;

use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct(
        protected WishlistService $wishlistService
    ) {}

    /**
     * Add product to wishlist.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $added = $this->wishlistService->add($request->product_id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => $added,
                'message' => $added 
                    ? __('frontend.product_added_to_wishlist') 
                    : __('frontend.product_already_in_wishlist'),
                'count' => $this->wishlistService->getCount(),
            ]);
        }

        return back()->with(
            $added ? 'success' : 'info',
            $added 
                ? __('frontend.product_added_to_wishlist') 
                : __('frontend.product_already_in_wishlist')
        );
    }

    /**
     * Remove product from wishlist.
     *
     * @param Request $request
     * @param int $productId
     * @return JsonResponse|RedirectResponse
     */
    public function remove(Request $request, int $productId)
    {
        $removed = $this->wishlistService->remove($productId);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => $removed,
                'message' => $removed 
                    ? __('frontend.product_removed_from_wishlist') 
                    : __('frontend.product_not_in_wishlist'),
                'count' => $this->wishlistService->getCount(),
            ]);
        }

        return back()->with(
            $removed ? 'success' : 'error',
            $removed 
                ? __('frontend.product_removed_from_wishlist') 
                : __('frontend.product_not_in_wishlist')
        );
    }

    /**
     * Get wishlist count (for AJAX requests).
     *
     * @return JsonResponse
     */
    public function count()
    {
        return response()->json([
            'count' => $this->wishlistService->getCount(),
        ]);
    }

    /**
     * Check if product is in wishlist.
     *
     * @param int $productId
     * @return JsonResponse
     */
    public function check(int $productId)
    {
        return response()->json([
            'in_wishlist' => $this->wishlistService->isInWishlist($productId),
        ]);
    }
}
