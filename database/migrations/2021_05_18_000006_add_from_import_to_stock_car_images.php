<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromImportToStockCarImages extends Migration
{
    public function up()
    {
        Schema::table('stock_car_images', function (Blueprint $table) {
            $table->boolean('from_import')->default(0);
        });
    }

    public function down()
    {
        Schema::table('stock_car_images', function (Blueprint $table) {
            $table->dropColumn('from_import');
        });
    }
}
