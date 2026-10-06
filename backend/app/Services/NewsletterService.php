<?php

namespace App\Services;

use App\Models\NewsletterSubscription;
use App\Services\LoggingService;
use Illuminate\Support\Facades\Log;

class NewsletterService
{
    public function __construct(
        protected LoggingService $loggingService
    ) {}

    /**
     * Subscribe to newsletter.
     *
     * @param string $email
     * @param string|null $name
     * @return NewsletterSubscription
     */
    public function subscribe(string $email, ?string $name = null): NewsletterSubscription
    {
        $subscription = NewsletterSubscription::subscribe($email, $name);

        $this->loggingService->logProduct('info', 'Newsletter subscription', [
            'email' => $email,
            'subscription_id' => $subscription->id,
        ]);

        return $subscription;
    }

    /**
     * Unsubscribe from newsletter.
     *
     * @param string $token
     * @return bool
     */
    public function unsubscribe(string $token): bool
    {
        $subscription = NewsletterSubscription::where('unsubscribe_token', $token)->first();

        if (!$subscription) {
            return false;
        }

        return $subscription->unsubscribe();
    }

    /**
     * Get all active subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveSubscriptions()
    {
        return NewsletterSubscription::where('is_active', true)->get();
    }
}
