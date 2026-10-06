<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateQuoteRequestBatchRequest;
use App\Services\QuoteRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteRequestController extends Controller
{
    public function __construct(
        protected QuoteRequestService $quoteRequestService
    ) {}

    /**
     * Display a listing of quote requests.
     */
    public function index(Request $request): View
    {
        $quoteRequests = $this->quoteRequestService->getPaginated(
            $request->only(['status', 'search']),
            20
        );

        return view('admin.quote-request.index', compact('quoteRequests'));
    }

    /**
     * Show a quote request.
     */
    public function show(int $id): View
    {
        $quoteRequest = $this->quoteRequestService->getById($id);
        return view('admin.quote-request.show', compact('quoteRequest'));
    }

    /**
     * Update quote request.
     */
    public function update(UpdateQuoteRequestBatchRequest $request, int $id): RedirectResponse
    {
        try {
            $this->quoteRequestService->updateBatch($id, $request->validated());
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.quote-requests.index')
            ->with('success', __('messages.quote_request_updated_successfully'));
    }
}
