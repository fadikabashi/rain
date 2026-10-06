<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    /**
     * Store a new review.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'string', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:255'],
            'comment' => ['required', 'string', 'min:10'],
        ]);

        try {
            $review = $this->reviewService->createReview($request->all());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('frontend.review_submitted_successfully'),
                ]);
            }

            return back()->with('success', __('frontend.review_submitted_successfully'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark review as helpful.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function markHelpful(int $id)
    {
        $this->reviewService->markHelpful($id);

        return response()->json([
            'success' => true,
            'message' => __('frontend.review_marked_helpful'),
        ]);
    }

    /**
     * Get reviews for a product (AJAX).
     *
     * @param int $productId
     * @return JsonResponse
     */
    public function getProductReviews(int $productId)
    {
        $reviews = $this->reviewService->getProductReviews($productId);
        $stats = $this->reviewService->getProductReviewStats($productId);

        return response()->json([
            'reviews' => $reviews,
            'stats' => $stats,
        ]);
    }
}
