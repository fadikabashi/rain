<?php

namespace App\Listeners;

use App\Constants\OrderStatus;
use App\Events\OrderStatusChanged;
use App\Mail\OrderStatusUpdateMail;
use App\Mail\ShippingNotificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusUpdateEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order;
        $oldStatus = OrderStatus::label($event->oldStatus);
        $newStatus = OrderStatus::label($event->newStatus);
        
        // Get customer email
        $customerEmail = null;
        if ($order->user_id && $order->user) {
            $customerEmail = $order->user->email;
        }
        
        if (!$customerEmail) {
            Log::warning('Order status update email skipped - no email found', [
                'order_id' => $order->id,
            ]);
            return;
        }
        
        try {
            // Send status update email
            Mail::to($customerEmail)->send(new OrderStatusUpdateMail($order, $oldStatus, $newStatus));
            
            // If status is DELIVERED, also send shipping notification
            if ($event->newStatus == OrderStatus::DELIVERED) {
                Mail::to($customerEmail)->send(new ShippingNotificationMail($order));
            }
            
            Log::info('Order status update email sent successfully', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send order status update email', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
