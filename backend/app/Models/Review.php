<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'customer_name',
        'customer_email',
        'rating',
        'title',
        'comment',
        'is_approved',
        'verified_purchase',
        'helpful_count',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'verified_purchase' => 'boolean',
        'rating' => 'integer',
        'helpful_count' => 'integer',
    ];

    /**
     * Get the product that owns the review.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope a query to order by helpful count.
     */
    public function scopeMostHelpful($query)
    {
        return $query->orderBy('helpful_count', 'DESC');
    }

    /**
     * Scope a query to order by newest first.
     */
    public function scopeNewest($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }
}
