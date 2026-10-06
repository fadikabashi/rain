<?php

namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $nameEn,
        public readonly string $nameAr,
        public readonly string $descriptionEn,
        public readonly string $descriptionAr,
        public readonly float $price,
        public readonly int $quantity,
        public readonly float $discount,
        public readonly bool $isAvailable,
        public readonly ?string $photo,
        public readonly ?int $categoryId,
        public readonly ?int $typeId,
        public readonly ?int $manfacturerId,
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
            nameEn: $data['name_en'] ?? '',
            nameAr: $data['name_ar'] ?? '',
            descriptionEn: $data['description_en'] ?? '',
            descriptionAr: $data['description_ar'] ?? '',
            price: (float) ($data['price'] ?? 0),
            quantity: (int) ($data['quantity'] ?? 0),
            discount: (float) ($data['discount'] ?? 0),
            isAvailable: (bool) ($data['is_available'] ?? false),
            photo: $data['photo'] ?? null,
            categoryId: $data['category_id'] ?? null,
            typeId: $data['type_id'] ?? null,
            manfacturerId: $data['manfacturer_id'] ?? null,
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
            'name_en' => $this->nameEn,
            'name_ar' => $this->nameAr,
            'description_en' => $this->descriptionEn,
            'description_ar' => $this->descriptionAr,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'is_available' => $this->isAvailable,
            'photo' => $this->photo,
            'category_id' => $this->categoryId,
            'type_id' => $this->typeId,
            'manfacturer_id' => $this->manfacturerId,
        ];
    }
}
