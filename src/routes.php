<?php

use Illuminate\Support\Facades\Route;
use Breuermarcel\FinanceDashboard\Http\Controllers\StockController;
use Breuermarcel\FinanceDashboard\Http\Controllers\AnalysisController;
use Breuermarcel\FinanceDashboard\Http\Controllers\SearchController;
use Breuermarcel\FinanceDashboard\Http\Helpers\APIController;

Route::get("search", [SearchController::class, "index"])->name("search");

Route::prefix("stocks")->group(function () {
    Route::get("import", [StockController::class, "importCSV"])->name("stocks.import");
    Route::post("import", [StockController::class, "doImportCSV"])->name("stocks.doImport");
    Route::get("analysis", [StockController::class, "getStocksByCriteria"])->name("stocks.analysis");
});
Route::resource("stocks", StockController::class);

Route::prefix("analysis")->group(function () {
    //
});
Route::resource("analysis", AnalysisController::class);

Route::get("api", [APIController::class, "load"])->name("api");

Route::get("/", function () {
    return redirect()->route("stocks.index");
});
