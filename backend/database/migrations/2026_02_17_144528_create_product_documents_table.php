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
        Schema::create('product_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type')->default('pdf'); // pdf, doc, docx, etc.
            $table->integer('file_size')->nullable(); // in bytes
            $table->string('document_type')->default('manual'); // manual, datasheet, installation_guide, wiring_diagram
            $table->string('title_en');
            $table->string('title_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('document_type');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_documents');
    }
};
