<?php

use Illuminate\Support\Facades\Route;
use Stock\Controllers\StockController;

Route::prefix('api/stock')->name('stock.')->group(function () {
    Route::get('brands', [StockController::class, 'getBrands'])->name('brands');
    Route::post('models', [StockController::class, 'getBrandModels'])->name('models');
    Route::post('cars', [StockController::class, 'getCars'])->name('cars');
    Route::post('count-cars', [StockController::class, 'getCountCars'])->name('cars');
});
