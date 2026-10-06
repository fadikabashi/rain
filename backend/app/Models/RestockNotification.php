<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'email',
        'name',
        'is_notified',
        'notified_at',
    ];

    protected $casts = [
        'is_notified' => 'boolean',
        'notified_at' => 'datetime',
    ];

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mark as notified.
     */
    public function markAsNotified(): bool
    {
        return $this->update([
            'is_notified' => true,
            'notified_at' => now(),
        ]);
    }
}
