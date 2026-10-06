<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'is_active',
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    /**
     * Generate unsubscribe token.
     */
    public static function generateUnsubscribeToken(): string
    {
        return Str::random(32);
    }

    /**
     * Subscribe to newsletter.
     */
    public static function subscribe(string $email, ?string $name = null): self
    {
        return self::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribe_token' => self::generateUnsubscribeToken(),
            ]
        );
    }

    /**
     * Unsubscribe from newsletter.
     */
    public function unsubscribe(): bool
    {
        return $this->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);
    }
}
