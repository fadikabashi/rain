<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Product;
use App\Services\LoggingService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function __construct(
        protected LoggingService $loggingService
    ) {}

    /**
     * Get all reviews for a product.
     *
     * @param int $productId
     * @param bool $approvedOnly
     * @return Collection
     */
    public function getProductReviews(int $productId, bool $approvedOnly = true): Collection
    {
        $query = Review::where('product_id', $productId)
            ->with('user');

        if ($approvedOnly) {
            $query->where('is_approved', true);
        }

        return $query->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Get review statistics for a product.
     *
     * @param int $productId
     * @return array
     */
    public function getProductReviewStats(int $productId): array
    {
        $reviews = Review::where('product_id', $productId)
            ->where('is_approved', true)
            ->get();

        $total = $reviews->count();
        $average = $reviews->avg('rating') ?? 0;
        
        $distribution = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];

        return [
            'total' => $total,
            'average' => round($average, 1),
            'distribution' => $distribution,
        ];
    }

    /**
     * Create a new review.
     *
     * @param array $data
     * @return Review
     */
    public function createReview(array $data): Review
    {
        // Check if user has already reviewed this product
        if (Auth::check() && isset($data['product_id'])) {
            $existingReview = Review::where('product_id', $data['product_id'])
                ->where('user_id', Auth::id())
                ->first();

            if ($existingReview) {
                throw new \Exception('You have already reviewed this product.');
            }
        }

        $review = Review::create([
            'product_id' => $data['product_id'],
            'user_id' => Auth::id(),
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'rating' => $data['rating'],
            'title' => $data['title'],
            'comment' => $data['comment'],
            'verified_purchase' => $this->checkVerifiedPurchase($data['product_id']),
            'is_approved' => false, // Requires admin approval
        ]);

        $this->loggingService->logProduct('info', 'Review created', [
            'review_id' => $review->id,
            'product_id' => $data['product_id'],
            'rating' => $data['rating'],
        ]);

        return $review;
    }

    /**
     * Check if user has verified purchase.
     *
     * @param int $productId
     * @return bool
     */
    protected function checkVerifiedPurchase(int $productId): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return DB::table('orderitems')
            ->join('orders', 'orderitems.order_id', '=', 'orders.id')
            ->where('orders.user_id', Auth::id())
            ->where('orderitems.product_id', $productId)
            ->where('orders.order_status', \App\Constants\OrderStatus::DELIVERED)
            ->exists();
    }

    /**
     * Approve a review.
     *
     * @param int $reviewId
     * @return bool
     */
    public function approveReview(int $reviewId): bool
    {
        $review = Review::findOrFail($reviewId);
        $review->is_approved = true;
        $result = $review->save();

        $this->loggingService->logProduct('info', 'Review approved', [
            'review_id' => $reviewId,
            'product_id' => $review->product_id,
        ]);

        return $result;
    }

    /**
     * Reject/Delete a review.
     *
     * @param int $reviewId
     * @return bool
     */
    public function rejectReview(int $reviewId): bool
    {
        $review = Review::findOrFail($reviewId);
        $result = $review->delete();

        $this->loggingService->logProduct('info', 'Review rejected', [
            'review_id' => $reviewId,
            'product_id' => $review->product_id,
        ]);

        return $result;
    }

    /**
     * Mark review as helpful.
     *
     * @param int $reviewId
     * @return bool
     */
    public function markHelpful(int $reviewId): bool
    {
        $review = Review::findOrFail($reviewId);
        $review->increment('helpful_count');

        return true;
    }
}
