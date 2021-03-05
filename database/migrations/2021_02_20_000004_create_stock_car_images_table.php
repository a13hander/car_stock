<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockCarImagesTable extends Migration
{
    public function up()
    {
        Schema::create('stock_car_images', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->boolean('is_main')->default(0);
            $table->integer('pos')->default(0);

            $table->string('car_id');
            $table->foreign('car_id')->on('stock_cars')->references('id')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_car_images');
    }
}
