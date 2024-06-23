<?php

use App\Http\Controllers\StockSalesController;
use Illuminate\Support\Facades\Route;

// Route::prefix('stock-sales')->name('stock-sales.')->group(function () {
    Route::get('/', [StockSalesController::class, 'index'])->name('index');
    Route::get('/show/{id}', [StockSalesController::class, 'show'])->name('show');
    Route::get('/create', [StockSalesController::class, 'create'])->name('create');
    Route::post('/store', [StockSalesController::class, 'store'])->name('store');
    Route::post('/update/{id}', [StockSalesController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [StockSalesController::class, 'destroy'])->name('destroy'); 
// });
