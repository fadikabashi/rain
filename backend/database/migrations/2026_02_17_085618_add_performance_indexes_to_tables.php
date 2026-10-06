<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to products table
        Schema::table('products', function (Blueprint $table) {
            // Index for category filtering (frequently queried)
            if (!$this->hasIndex('products', 'products_category_id_index')) {
                $table->index('category_id', 'products_category_id_index');
            }

            // Index for type filtering
            if (!$this->hasIndex('products', 'products_type_id_index')) {
                $table->index('type_id', 'products_type_id_index');
            }

            // Index for manufacturer filtering
            if (!$this->hasIndex('products', 'products_manfacturer_id_index')) {
                $table->index('manfacturer_id', 'products_manfacturer_id_index');
            }

            // Composite index for available products query (is_available + quantity)
            if (!$this->hasIndex('products', 'products_available_index')) {
                $table->index(['is_available', 'quantity'], 'products_available_index');
            }

            // Index for sorting by discount
            if (!$this->hasIndex('products', 'products_discount_index')) {
                $table->index('discount', 'products_discount_index');
            }
        });

        // Add indexes to orders table
        Schema::table('orders', function (Blueprint $table) {
            // Index for status filtering (frequently queried)
            if (!$this->hasIndex('orders', 'orders_order_status_index')) {
                $table->index('order_status', 'orders_order_status_index');
            }

            // Index for coupon filtering
            if (!$this->hasIndex('orders', 'orders_coupon_id_index')) {
                $table->index('coupon_id', 'orders_coupon_id_index');
            }
        });

        // Add indexes to orderitems table
        Schema::table('orderitems', function (Blueprint $table) {
            // Index for order filtering (frequently queried)
            if (!$this->hasIndex('orderitems', 'orderitems_order_id_index')) {
                $table->index('order_id', 'orderitems_order_id_index');
            }

            // Index for product filtering
            if (!$this->hasIndex('orderitems', 'orderitems_product_id_index')) {
                $table->index('product_id', 'orderitems_product_id_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_category_id_index');
            $table->dropIndex('products_type_id_index');
            $table->dropIndex('products_manfacturer_id_index');
            $table->dropIndex('products_available_index');
            $table->dropIndex('products_discount_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_order_status_index');
            $table->dropIndex('orders_coupon_id_index');
        });

        Schema::table('orderitems', function (Blueprint $table) {
            $table->dropIndex('orderitems_order_id_index');
            $table->dropIndex('orderitems_product_id_index');
        });
    }

    /**
     * Check if an index exists on a table.
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        try {
            $connection = Schema::getConnection();
            $indexes = $connection->getDoctrineSchemaManager()->listTableIndexes($table);
            return isset($indexes[$indexName]);
        } catch (\Exception $e) {
            // If table doesn't exist or error occurs, assume index doesn't exist
            return false;
        }
    }
};
