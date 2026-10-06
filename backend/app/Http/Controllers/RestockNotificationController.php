<?php

namespace App\Http\Controllers;

use App\Services\RestockNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RestockNotificationController extends Controller
{
    public function __construct(
        protected RestockNotificationService $restockService
    ) {}

    /**
     * Subscribe to restock notification.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $this->restockService->subscribe(
                $request->product_id,
                $request->email,
                $request->name
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('frontend.restock_notification_subscribed'),
                ]);
            }

            return back()->with('success', __('frontend.restock_notification_subscribed'));
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
     * Check if subscribed (AJAX).
     *
     * @param int $productId
     * @param Request $request
     * @return JsonResponse
     */
    public function check(int $productId, Request $request): JsonResponse
    {
        $email = $request->email;
        $isSubscribed = $email ? $this->restockService->isSubscribed($productId, $email) : false;

        return response()->json([
            'subscribed' => $isSubscribed,
        ]);
    }
}
