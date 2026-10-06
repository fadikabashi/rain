<?php

namespace App\Listeners;

use App\Events\ProductStockLow;
use Illuminate\Support\Facades\Log;

class NotifyAdminLowStock
{
    /**
     * Handle the event.
     */
    public function handle(ProductStockLow $event): void
    {
        $product = $event->product;
        $currentStock = $event->currentStock;
        
        // TODO: Implement admin notification (email, notification, etc.)
        // For now, just log the event
        Log::warning('Product stock is low', [
            'product_id' => $product->id,
            'product_name' => $product->name_en,
            'current_stock' => $currentStock,
        ]);
        
        // Example: Notification::send(Admin::all(), new LowStockNotification($product));
    }
}
