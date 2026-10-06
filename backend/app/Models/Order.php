<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'costumer_name',
        'costumer_number',
        'address',
        'order_status',
        'note',
        'total',
        'coupon_id'
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the coupon associated with the order.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    /**
     * Get the order items for the order.
     */
    public function orderitems()
    {
        return $this->hasMany(Orderitem::class, 'order_id');
    }

    /**
     * Scope a query to filter by status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, int $status)
    {
        return $query->where('order_status', $status);
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
}
