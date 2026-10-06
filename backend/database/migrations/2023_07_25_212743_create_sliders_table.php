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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('ad_1_825');
            $table->string('heading_1');
            $table->string('sub_heading_1');
            $table->string('description_1');
            $table->string('ad_2_825');
            $table->string('heading_2');
            $table->string('sub_heading_2');
            $table->string('description_2');
            $table->string('ad_3_825');
            $table->string('heading_3');
            $table->string('sub_heading_3');
            $table->string('description_3');

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
        Schema::dropIfExists('sliders');
    }
};
