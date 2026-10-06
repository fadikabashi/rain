<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    /**
     * Display a listing of reviews.
     */
    public function index(Request $request): View
    {
        $query = Review::with(['product', 'user']);

        // Filter by approval status
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        // Filter by rating
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('title', 'LIKE', "%{$search}%")
                  ->orWhere('comment', 'LIKE', "%{$search}%");
            });
        }

        $reviews = $query
            ->orderBy('created_at', 'DESC')
            ->paginate(20)
            ->withQueryString();

        // Get statistics
        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('is_approved', false)->count(),
            'approved' => Review::where('is_approved', true)->count(),
        ];

        return view('admin.review.index', compact('reviews', 'stats'));
    }

    /**
     * Approve a review.
     */
    public function approve(int $id): RedirectResponse
    {
        $this->reviewService->approveReview($id);

        return redirect()->route('admin.reviews.index')
            ->with('success', __('messages.review_approved_successfully'));
    }

    /**
     * Reject/Delete a review.
     */
    public function reject(int $id): RedirectResponse
    {
        $this->reviewService->rejectReview($id);

        return redirect()->route('admin.reviews.index')
            ->with('success', __('messages.review_rejected_deleted'));
    }
}
