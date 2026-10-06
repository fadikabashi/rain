<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'company_name',
        'quantity',
        'message',
        'status',
        'quoted_price',
        'admin_notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quoted_price' => 'decimal:2',
    ];

    /**
     * Get the product for the quote request.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that made the quote request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to order by newest first.
     */
    public function scopeNewest($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }
}
