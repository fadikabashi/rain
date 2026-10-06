<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequestBatchRequest;
use App\Models\Product;
use App\Services\QuoteRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class QuoteRequestController extends Controller
{
    public function __construct(
        protected QuoteRequestService $quoteRequestService
    ) {}

    public function create(): View
    {
        $products = Product::query()
            ->orderBy('name_en')
            ->get(['id', 'name_en', 'name_ar']);

        return view('frontend.quote-requests.create', [
            'products' => $products,
            'initialProductId' => request()->integer('product_id') ?: null,
        ]);
    }

    /**
     * @return RedirectResponse|JsonResponse
     */
    public function store(StoreQuoteRequestBatchRequest $request)
    {
        try {
            $batch = $this->quoteRequestService->createBatch($request->validated());

            $pdfDataUrl = URL::temporarySignedRoute(
                'quote-request.pdf-data',
                now()->addDays(7),
                ['batch' => $batch->id]
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('frontend.quote_request_submitted_successfully'),
                    'pdf_data_url' => $pdfDataUrl,
                ]);
            }

            return redirect()
                ->route('quote-request.create')
                ->with('quote_request_success', __('frontend.quote_request_submitted_successfully'))
                ->with('quote_pdf_data_url', $pdfDataUrl);
        } catch (\Throwable $exception) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                ], 400);
            }

            return redirect()
                ->route('quote-request.create')
                ->withInput()
                ->with('error', $exception->getMessage());
        }
    }
}
