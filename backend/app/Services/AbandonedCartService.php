<?php

namespace App\Services;

use App\Models\AbandonedCart;
use Hnooz\LaravelCart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AbandonedCartService
{
    /**
     * Save current cart as abandoned.
     *
     * @return AbandonedCart|null
     */
    public function saveAbandonedCart(): ?AbandonedCart
    {
        $cartItems = Cart::all();

        if (empty($cartItems)) {
            return null;
        }

        $total = Cart::total();
        $userId = Auth::id();
        $sessionId = Session::getId();
        $email = Auth::check() ? Auth::user()->email : null;

        // Check if abandoned cart already exists
        $abandonedCart = AbandonedCart::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })
        ->where('is_recovered', false)
        ->first();

        if ($abandonedCart) {
            // Update existing
            $abandonedCart->update([
                'cart_data' => $cartItems,
                'total' => $total,
                'email' => $email,
            ]);
            return $abandonedCart;
        }

        // Create new
        return AbandonedCart::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'email' => $email,
            'cart_data' => $cartItems,
            'total' => $total,
            'is_recovered' => false,
        ]);
    }

    /**
     * Get abandoned carts ready for reminder.
     *
     * @param int $hoursSinceAbandoned
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCartsForReminder(int $hoursSinceAbandoned = 24)
    {
        return AbandonedCart::where('is_recovered', false)
            ->where('reminder_count', '<', 3) // Max 3 reminders
            ->where(function($query) use ($hoursSinceAbandoned) {
                $query->whereNull('last_reminder_sent_at')
                    ->orWhere('last_reminder_sent_at', '<', now()->subHours($hoursSinceAbandoned));
            })
            ->where('created_at', '<', now()->subHours($hoursSinceAbandoned))
            ->get();
    }

    /**
     * Mark cart as recovered.
     *
     * @param int $cartId
     * @return bool
     */
    public function markAsRecovered(int $cartId): bool
    {
        $cart = AbandonedCart::find($cartId);
        return $cart ? $cart->markAsRecovered() : false;
    }
}
