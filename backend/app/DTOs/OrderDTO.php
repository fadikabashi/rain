<?php

namespace App\DTOs;

class OrderDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $userId,
        public readonly string $costumerName,
        public readonly int $costumerNumber,
        public readonly string $address,
        public readonly float $total,
        public readonly int $orderStatus,
        public readonly ?string $note,
        public readonly ?string $couponId,
    ) {}

    /**
     * Create from array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            userId: $data['user_id'] ?? null,
            costumerName: $data['costumer_name'] ?? '',
            costumerNumber: (int) ($data['costumer_number'] ?? 0),
            address: $data['address'] ?? '',
            total: (float) ($data['total'] ?? 0),
            orderStatus: (int) ($data['order_status'] ?? 0),
            note: $data['note'] ?? null,
            couponId: !empty($data['coupon_id']) ? (string) $data['coupon_id'] : null,
        );
    }

    /**
     * Convert to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'costumer_name' => $this->costumerName,
            'costumer_number' => $this->costumerNumber,
            'address' => $this->address,
            'total' => $this->total,
            'order_status' => $this->orderStatus,
            'note' => $this->note,
            'coupon_id' => $this->couponId,
        ];
    }
}
