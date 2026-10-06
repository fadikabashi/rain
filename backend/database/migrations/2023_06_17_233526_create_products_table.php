<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->foreignId('type_id')->constrained('types');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('manfacturer_id')->constrained('manfacturers');
            $table->longText('description_en');
            $table->longText('description_ar');
            $table->string('photo');
            $table->integer('quantity');
            $table->boolean('is_available')->default(0);
            $table->double('price', 8, 2);
            $table->double('discount', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
