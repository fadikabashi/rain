<?php

namespace App\Http\Controllers;

use App\Services\ProductComparisonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductComparisonController extends Controller
{
    public function __construct(
        protected ProductComparisonService $comparisonService
    ) {}

    /**
     * Display comparison page.
     *
     * @return View
     */
    public function index(): View
    {
        $products = $this->comparisonService->getComparisonProducts();
        return view('frontend.comparison.index', compact('products'));
    }

    /**
     * Add product to comparison.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $added = $this->comparisonService->addToComparison($request->product_id);

        if ($request->ajax()) {
            return response()->json([
                'success' => $added,
                'message' => $added 
                    ? __('frontend.product_added_to_comparison')
                    : __('frontend.comparison_limit_reached'),
                'count' => $this->comparisonService->getComparisonCount(),
            ]);
        }

        return back()->with($added ? 'success' : 'error', 
            $added ? __('frontend.product_added_to_comparison') : __('frontend.comparison_limit_reached'));
    }

    /**
     * Remove product from comparison.
     *
     * @param int $productId
     * @return JsonResponse|RedirectResponse
     */
    public function remove(int $productId, Request $request)
    {
        $removed = $this->comparisonService->removeFromComparison($productId);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $removed,
                'message' => __('frontend.product_removed_from_comparison'),
                'count' => $this->comparisonService->getComparisonCount(),
            ]);
        }

        return back()->with('success', __('frontend.product_removed_from_comparison'));
    }

    /**
     * Clear comparison.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function clear(Request $request)
    {
        $this->comparisonService->clearComparison();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('frontend.comparison_cleared'),
                'count' => 0,
            ]);
        }

        return redirect()->route('comparison.index')
            ->with('success', __('frontend.comparison_cleared'));
    }

    /**
     * Get comparison count (AJAX).
     *
     * @return JsonResponse
     */
    public function count(): JsonResponse
    {
        return response()->json([
            'count' => $this->comparisonService->getComparisonCount(),
        ]);
    }

    /**
     * Check if product is in comparison (AJAX).
     *
     * @param int $productId
     * @return JsonResponse
     */
    public function check(int $productId): JsonResponse
    {
        return response()->json([
            'in_comparison' => $this->comparisonService->isInComparison($productId),
        ]);
    }
}
