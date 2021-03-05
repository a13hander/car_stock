<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockCarsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_cars', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->string('type');
            $table->string('vin')->unique()->nullable();

            $table->string('equipment')->nullable();
            $table->string('modification')->nullable();

            $table->string('body_type');
            $table->string('fuel_type');
            $table->string('drive_type');
            $table->string('gearbox_type');
            $table->string('wheel_type');
            $table->string('engine_power');
            $table->string('engine_volume');

            $table->integer('doors');
            $table->year('year')->nullable();
            $table->unsignedInteger('kilometrage')->default(0);
            $table->string('color')->nullable();
            $table->string('color_hex')->nullable();

            $table->integer('price');
            $table->decimal('credit_percent')->default(0);
            $table->integer('discount')->default(0);

            $table->text('description')->nullable();
            $table->string('accident')->nullable();
            $table->integer('owners')->default(0);
            $table->date('expiry_date')->nullable();

            $table->json('original_images')->nullable();
            $table->json('marketing_labels')->nullable();

            $table->unsignedBigInteger('location_id')->nullable();

            $table->softDeletes();

            $table->foreign('location_id')->on('stock_locations')->references('id')->cascadeOnDelete();
            $table->foreignId('model_id')->constrained('stock_car_models')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_cars');
    }
}
