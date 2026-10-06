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
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('company_name')->nullable();
            $table->integer('quantity');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'quoted', 'accepted', 'rejected'])->default('pending');
            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
