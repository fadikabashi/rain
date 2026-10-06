<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbandonedCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'email',
        'cart_data',
        'total',
        'reminder_count',
        'last_reminder_sent_at',
        'recovered_at',
        'is_recovered',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'total' => 'decimal:2',
        'is_recovered' => 'boolean',
        'last_reminder_sent_at' => 'datetime',
        'recovered_at' => 'datetime',
    ];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark cart as recovered.
     */
    public function markAsRecovered(): bool
    {
        return $this->update([
            'is_recovered' => true,
            'recovered_at' => now(),
        ]);
    }

    /**
     * Increment reminder count.
     */
    public function incrementReminder(): bool
    {
        return $this->update([
            'reminder_count' => $this->reminder_count + 1,
            'last_reminder_sent_at' => now(),
        ]);
    }
}
