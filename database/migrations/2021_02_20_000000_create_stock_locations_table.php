<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_locations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->nullable();

            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('full_address')->nullable();
            $table->string('time_open')->nullable();
            $table->string('time_close')->nullable();
            $table->string('coord_n')->nullable();
            $table->string('coord_e')->nullable();
            $table->json('navigator')->nullable();
            $table->boolean('active')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_locations');
    }
}
