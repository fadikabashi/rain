<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail
{
    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        
        // Get customer email
        $customerEmail = null;
        
        // If order has a user, use user's email
        if ($order->user_id && $order->user) {
            $customerEmail = $order->user->email;
        }
        
        // If no email found, log and skip
        if (!$customerEmail) {
            Log::warning('Order confirmation email skipped - no email found', [
                'order_id' => $order->id,
                'customer_name' => $order->costumer_name,
            ]);
            return;
        }
        
        try {
            Mail::to($customerEmail)->send(new OrderConfirmationMail($order));
            
            Log::info('Order confirmation email sent successfully', [
                'order_id' => $order->id,
                'customer_email' => $customerEmail,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'customer_email' => $customerEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
