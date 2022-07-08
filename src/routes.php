<?php

use Breuermarcel\FinanceDashboard\FinanceDashboard;
use Breuermarcel\FinanceDashboard\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::prefix('finance-dashboard')->middleware("web")->group(function () {
    Route::get("/", [FinanceDashboard::class, "welcome"]);

    Route::resource('stocks', StockController::class);

    Route::prefix('analysis')->group(function () {

    });

    Route::get('/', function () {
        return redirect()->route('stocks.index');
    });
});
