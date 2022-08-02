<?php

use Illuminate\Support\Facades\Route;
use Breuermarcel\FinanceDashboard\FinanceDashboard;
use Breuermarcel\FinanceDashboard\Http\Controllers\StockController;

Route::get("/", [FinanceDashboard::class, "welcome"]);

Route::prefix("stocks")->group(function(){
    Route::get("import", [StockController::class, "import_csv"])->name("stocks.import");
    Route::post("import", [StockController::class, "do_import_csv"])->name("stocks.do_import");
});
Route::resource("stocks", StockController::class);



Route::prefix("analysis")->group(function () {
});

Route::get("/", function () {
    return redirect(route("stocks.index"));
});
