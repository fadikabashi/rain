<?php

namespace App\Services;

use App\Models\QuoteRequestBatch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class QuoteRequestService
{
    public function __construct(
        protected LoggingService $loggingService
    ) {}

    public const STATUS_PENDING = 'pending';
    public const STATUS_QUOTED = 'quoted';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Allowed lifecycle transitions.
     *
     * @var array<string, array<int, string>>
     */
    protected array $allowedTransitions = [
        self::STATUS_PENDING => [self::STATUS_QUOTED, self::STATUS_REJECTED],
        self::STATUS_QUOTED => [self::STATUS_ACCEPTED, self::STATUS_REJECTED],
        self::STATUS_ACCEPTED => [],
        self::STATUS_REJECTED => [],
    ];

    public function getPaginated(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = QuoteRequestBatch::with(['items.product', 'user']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'DESC')->paginate($perPage)->withQueryString();
    }

    public function getById(int $id): QuoteRequestBatch
    {
        return QuoteRequestBatch::with(['items.product', 'user'])->findOrFail($id);
    }

    public function getForUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return QuoteRequestBatch::with(['items.product'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }

    public function getForUserById(int $id, int $userId): QuoteRequestBatch
    {
        return QuoteRequestBatch::with(['items.product'])
            ->where('user_id', $userId)
            ->findOrFail($id);
    }

    public function createBatch(array $data): QuoteRequestBatch
    {
        $batch = DB::transaction(function () use ($data) {
            $quoteBatch = QuoteRequestBatch::create([
                'user_id' => Auth::id(),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'company_name' => $data['company_name'] ?? null,
                'message' => $data['message'] ?? null,
                'status' => self::STATUS_PENDING,
            ]);

            foreach ($data['items'] as $item) {
                $quoteBatch->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'line_note' => $item['line_note'] ?? null,
                ]);
            }

            return $quoteBatch;
        });

        $this->loggingService->logProduct('info', 'Quote request batch created', [
            'quote_request_batch_id' => $batch->id,
            'items_count' => count($data['items']),
        ]);

        return $batch->load(['items.product', 'user']);
    }

    public function updateBatch(int $id, array $data): QuoteRequestBatch
    {
        $batch = $this->getById($id);
        $newStatus = $data['status'] ?? $batch->status;

        if ($newStatus !== $batch->status && !$this->canTransition($batch->status, $newStatus)) {
            throw new InvalidArgumentException('Invalid status transition.');
        }

        $quotedTotal = null;

        DB::transaction(function () use ($batch, $data, $newStatus, &$quotedTotal) {
            if (!empty($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    $item = $batch->items->firstWhere('id', (int) $itemData['id']);
                    if (!$item) {
                        throw new InvalidArgumentException('Quote line item does not belong to this batch.');
                    }

                    $item->update([
                        'quoted_price' => $itemData['quoted_price'] ?? $item->quoted_price,
                    ]);
                }
            }

            $quotedTotal = $batch->items()->get()
                ->reduce(function (float $carry, $item) {
                    $price = (float) ($item->quoted_price ?? 0);
                    return $carry + ($price * (int) $item->quantity);
                }, 0.0);

            if ($newStatus === self::STATUS_QUOTED && $quotedTotal <= 0) {
                throw new InvalidArgumentException('Quoted status requires at least one item with quoted price.');
            }

            $batch->update([
                'status' => $newStatus,
                'admin_notes' => $data['admin_notes'] ?? $batch->admin_notes,
                'quoted_total' => $quotedTotal > 0 ? $quotedTotal : null,
            ]);
        });

        $this->loggingService->logProduct('info', 'Quote request batch updated', [
            'quote_request_batch_id' => $batch->id,
            'status' => $newStatus,
        ]);

        return $this->getById($batch->id);
    }

    public function canTransition(string $fromStatus, string $toStatus): bool
    {
        if (!isset($this->allowedTransitions[$fromStatus])) {
            return false;
        }

        return in_array($toStatus, $this->allowedTransitions[$fromStatus], true);
    }

    /**
     * @return Collection<int, string>
     */
    public function statuses(): Collection
    {
        return collect([
            self::STATUS_PENDING,
            self::STATUS_QUOTED,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
        ]);
    }

    /**
     * Backward-compatible single-item creation wrapper.
     */
    public function createQuoteRequest(array $data): QuoteRequestBatch
    {
        return $this->createBatch([
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'company_name' => $data['company_name'] ?? null,
            'message' => $data['message'] ?? null,
            'items' => [
                [
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity'] ?? 1,
                    'line_note' => null,
                ],
            ],
        ]);
    }
}
