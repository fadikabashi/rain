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
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->string('email')->nullable();
            $table->text('cart_data'); // JSON encoded cart items
            $table->decimal('total', 10, 2)->default(0);
            $table->integer('reminder_count')->default(0);
            $table->timestamp('last_reminder_sent_at')->nullable();
            $table->timestamp('recovered_at')->nullable();
            $table->boolean('is_recovered')->default(false);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('session_id');
            $table->index('email');
            $table->index('is_recovered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abandoned_carts');
    }
};
