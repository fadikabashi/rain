<?php

namespace App\Http\Controllers;

use App\Services\NewsletterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __construct(
        protected NewsletterService $newsletterService
    ) {}

    /**
     * Subscribe to newsletter.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $this->newsletterService->subscribe(
                $request->email,
                $request->name
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('frontend.newsletter_subscribed_successfully'),
                ]);
            }

            return back()->with('success', __('frontend.newsletter_subscribed_successfully'));
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
     * Unsubscribe from newsletter.
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function unsubscribe(string $token)
    {
        $success = $this->newsletterService->unsubscribe($token);

        return view('frontend.newsletter.unsubscribe', [
            'success' => $success,
        ]);
    }
}
