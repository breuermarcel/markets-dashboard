<?php

use Illuminate\Support\Facades\Route;
use Breuermarcel\FinanceDashboard\FinanceDashboard;
use Breuermarcel\FinanceDashboard\Http\Controllers\StockController;
use Breuermarcel\FinanceDashboard\Http\Controllers\AnalysisController;

Route::prefix("stocks")->group(function(){
    Route::get("import", [StockController::class, "import_csv"])->name("stocks.import");
    Route::post("import", [StockController::class, "do_import_csv"])->name("stocks.do_import");
    Route::get("analysis", [StockController::class, "getStocksByCriteria"])->name("stocks.analysis");
});
Route::resource("stocks", StockController::class);

Route::prefix("analysis")->group(function () {
    //
});
Route::resource("analysis", AnalysisController::class);

Route::get("/", function () {
    return redirect(route("stocks.index"));
});
