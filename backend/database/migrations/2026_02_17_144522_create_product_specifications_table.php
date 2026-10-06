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
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('spec_key_en');
            $table->string('spec_key_ar')->nullable();
            $table->text('spec_value_en');
            $table->text('spec_value_ar')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('group')->nullable(); // e.g., 'General', 'Technical', 'Dimensions'
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('sort_order');
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
    }
};
