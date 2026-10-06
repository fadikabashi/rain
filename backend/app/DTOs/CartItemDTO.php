<?php

namespace App\DTOs;

class CartItemDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly float $price,
        public readonly int $quantity,
        public readonly ?string $photo,
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
            id: (string) ($data['id'] ?? ''),
            name: $data['name'] ?? '',
            price: (float) ($data['price'] ?? 0),
            quantity: (int) ($data['quantity'] ?? 1),
            photo: $data['photo'] ?? $data['options']['photo'] ?? null,
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
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'photo' => $this->photo,
        ];
    }

    /**
     * Calculate subtotal.
     *
     * @return float
     */
    public function getSubtotal(): float
    {
        return $this->price * $this->quantity;
    }
}
