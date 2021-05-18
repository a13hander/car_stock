<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginalImageToStockCarImages extends Migration
{
    public function up()
    {
        Schema::table('stock_car_images', function (Blueprint $table) {
            $table->string('original_image');
        });
    }

    public function down()
    {
        Schema::table('stock_car_images', function (Blueprint $table) {
            $table->dropColumn('original_image');
        });
    }
}
