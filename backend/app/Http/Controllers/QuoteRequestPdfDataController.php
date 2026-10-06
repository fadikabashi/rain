<?php

namespace App\Http\Controllers;

use App\Models\QuoteRequestBatch;
use App\Services\QuotePdfDataBuilder;
use App\Services\QuoteRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class QuoteRequestPdfDataController extends Controller
{
    public function __construct(
        protected QuotePdfDataBuilder $quotePdfDataBuilder,
        protected QuoteRequestService $quoteRequestService
    ) {}

    /**
     * Signed URL (guest or anyone with the link until expiry). Use route middleware `signed`.
     */
    public function show(QuoteRequestBatch $batch): JsonResponse
    {
        return response()->json($this->quotePdfDataBuilder->build($batch));
    }

    /**
     * Authenticated customer: same access as quote request details (service layer).
     */
    public function showForAccount(int $batch): JsonResponse
    {
        $userId = Auth::id();
        if ($userId === null) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $batchModel = $this->quoteRequestService->getForUserById($batch, (int) $userId);

        return response()->json($this->quotePdfDataBuilder->build($batchModel));
    }
}
