<?php

use Breuermarcel\FinanceDashboard\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::prefix('finance-dashboard')->middleware("web")->group(function () {
    Route::resource('stocks', StockController::class);

    Route::prefix('analysis')->group(function () {

    });
});
