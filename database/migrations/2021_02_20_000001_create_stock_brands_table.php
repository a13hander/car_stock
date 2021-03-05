<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockBrandsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_brands');
    }
}
