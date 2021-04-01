<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockCarModelsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_car_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->unsignedBigInteger('brand_id');

            $table->unique(['slug', 'brand_id']);
            $table->foreign('brand_id')->on('stock_brands')->references('id')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_car_models');
    }
}
