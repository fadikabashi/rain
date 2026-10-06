<?php

namespace App\Services;

use App\Models\Product;
use App\Models\RestockNotification;
use App\Services\LoggingService;
use Illuminate\Support\Facades\Log;

class RestockNotificationService
{
    public function __construct(
        protected LoggingService $loggingService
    ) {}

    /**
     * Subscribe to restock notification.
     *
     * @param int $productId
     * @param string $email
     * @param string|null $name
     * @return RestockNotification
     */
    public function subscribe(int $productId, string $email, ?string $name = null): RestockNotification
    {
        $notification = RestockNotification::updateOrCreate(
            [
                'product_id' => $productId,
                'email' => $email,
            ],
            [
                'name' => $name,
                'is_notified' => false,
            ]
        );

        $this->loggingService->logProduct('info', 'Restock notification subscription', [
            'product_id' => $productId,
            'email' => $email,
        ]);

        return $notification;
    }

    /**
     * Get notifications for a product.
     *
     * @param int $productId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNotifications(int $productId)
    {
        return RestockNotification::where('product_id', $productId)
            ->where('is_notified', false)
            ->get();
    }

    /**
     * Notify subscribers when product is restocked.
     *
     * @param int $productId
     * @return int Number of notifications sent
     */
    public function notifySubscribers(int $productId): int
    {
        $notifications = $this->getNotifications($productId);
        $count = 0;

        foreach ($notifications as $notification) {
            // TODO: Send email notification
            // For now, just mark as notified
            $notification->markAsNotified();
            $count++;
        }

        return $count;
    }

    /**
     * Check if email is subscribed for product.
     *
     * @param int $productId
     * @param string $email
     * @return bool
     */
    public function isSubscribed(int $productId, string $email): bool
    {
        return RestockNotification::where('product_id', $productId)
            ->where('email', $email)
            ->where('is_notified', false)
            ->exists();
    }
}
