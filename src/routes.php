<?php

use Breuermarcel\FinanceDashboard\Http\{
    Controllers\SearchController,
    Controllers\StockController,
    Helpers\APIController
};
use Illuminate\Support\Facades\Route;

Route::get("search", [SearchController::class, "index"])->name("search");

Route::prefix("stocks")->group(function () {
    Route::get("import", [StockController::class, "importCSV"])->name("stocks.import");
    Route::post("import", [StockController::class, "doImportCSV"])->name("stocks.doImport");
    Route::get("analysis", [StockController::class, "getStocksByCriteria"])->name("stocks.analysis");
});
Route::resource("stocks", StockController::class);

Route::get("api", [APIController::class, "load"])->name("api");

Route::get("/", function () {
    return redirect()->route("stocks.index");
});
