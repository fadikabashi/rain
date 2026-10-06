<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'price' => 'float',
        'discount' => 'float',
    ];

    protected $fillable = [
        'name_en',
        'name_ar',
        'quantity',
        'description_en',
        'description_ar',
        'photo',
        'is_available',
        'discount',
        'price',
        'category_id',
        'type_id',
        'manfacturer_id'
    ];

    /**
     * Get the manufacturer that owns the product.
     */
    public function manfacturer()
    {
        return $this->belongsTo(Manfacturer::class, 'manfacturer_id');
    }

    /**
     * Get the type that owns the product (includes soft-deleted types for fallback display).
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id')->withTrashed();
    }

    /**
     * Get the type name in Arabic, or a fallback when type is missing or soft-deleted.
     */
    public function getTypeNameArAttribute(): string
    {
        $type = $this->type;
        if (!$type) {
            return '—';
        }
        return $type->trashed() ? $type->name_ar . ' (محذوف)' : $type->name_ar;
    }

    /**
     * Get the type name in English, or a fallback when type is missing or soft-deleted.
     */
    public function getTypeNameEnAttribute(): string
    {
        $type = $this->type;
        if (!$type) {
            return '—';
        }
        return $type->trashed() ? $type->name_en . ' (deleted)' : $type->name_en;
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the order items for the product.
     */
    public function orderitems()
    {
        return $this->hasMany(Orderitem::class, 'product_id');
    }

    /**
     * Get the product images.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Get the primary product image.
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get the product specifications.
     */
    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('group')->orderBy('sort_order');
    }

    /**
     * Get the product videos.
     */
    public function videos()
    {
        return $this->hasMany(ProductVideo::class)->orderBy('sort_order');
    }

    /**
     * Get the featured product video.
     */
    public function featuredVideo()
    {
        return $this->hasOne(ProductVideo::class)->where('is_featured', true);
    }

    /**
     * Get the product documents.
     */
    public function documents()
    {
        return $this->hasMany(ProductDocument::class)->orderBy('sort_order');
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get approved reviews for the product.
     */
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Get compatible products.
     */
    public function compatibleProducts()
    {
        return $this->hasMany(ProductCompatibility::class, 'product_id')->orderBy('sort_order');
    }

    /**
     * Get products that this product is compatible with.
     */
    public function compatibleWith()
    {
        return $this->hasMany(ProductCompatibility::class, 'compatible_product_id');
    }

    /**
     * Get average rating for the product.
     */
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count.
     */
    public function getTotalReviewsAttribute()
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Scope a query to only include available products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('quantity', '>', 0);
    }

    /**
     * Scope a query to only include products with low stock.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $threshold
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowStock($query, int $threshold = 10)
    {
        return $query->where('quantity', '<=', $threshold)->where('is_available', true);
    }

    /**
     * Scope a query to order by newest first.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNewest($query)
    {
        return $query->orderBy('id', 'DESC');
    }

    /**
     * Scope a query to order by best discount.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBestDiscount($query)
    {
        return $query->orderBy('discount', 'DESC');
    }

    /**
     * Scope a query to filter by category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Get normalized discount rate between 0 and 1.
     * Supports both fraction (0.2) and percentage (20) inputs.
     */
    public function getDiscountRateAttribute(): float
    {
        $discount = (float) ($this->discount ?? 0);

        if ($discount <= 0) {
            return 0.0;
        }

        // If admin enters "20", treat as 20%.
        if ($discount > 1) {
            $discount = $discount / 100;
        }

        return min(1.0, $discount);
    }

    /**
     * Get discount percentage value (e.g. 20 for 20%).
     */
    public function getDiscountPercentageAttribute(): float
    {
        return round($this->discount_rate * 100, 2);
    }

    /**
     * Get price after discount.
     */
    public function getPriceAfterDiscountAttribute(): float
    {
        $price = (float) ($this->price ?? 0);
        $priceAfterDiscount = $price * (1 - $this->discount_rate);

        return round(max(0, $priceAfterDiscount), 2);
    }
}
