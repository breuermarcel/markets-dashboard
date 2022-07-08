<?php

use Illuminate\Support\Facades\Route;

Route::prefix('finance-dashboard')->group(function () {
    Route::prefix('stocks')->group(function () {

    });

    Route::prefix('analysis')->group(function () {

    });
});
