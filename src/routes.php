<?php

use Breuermarcel\MarketsDashboard\Http\{
    Controllers\SearchController,
    Controllers\StockController,
    Controllers\InformationController,
    Helpers\APIController
};

Route::get("search", [SearchController::class, "index"])->name("search");

Route::prefix("stocks")->group(function () {
    Route::prefix("import")->group(function () {
        Route::get("/", [StockController::class, "importCSV"])->name("stocks.import.show");
        Route::post("/", [StockController::class, "storeCSV"])->name("stocks.import.store");
    });

    Route::get("/information", [InformationController::class, "index"])->name("stocks.information.index");

    Route::get("/", [StockController::class, "index"])->name("stocks.index");
    Route::get("/create", [StockController::class, "create"])->name("stocks.create");
    Route::post("/store", [StockController::class, "store"])->name("stocks.store");
    Route::get("/{stock}", [StockController::class, "show"])->name("stocks.show");
    Route::get("/{stock}/edit", [StockController::class, "edit"])->name("stocks.edit");
    Route::put("/{stock}", [StockController::class, "update"])->name("stocks.update");
    Route::delete("/{stock}", [StockController::class, "destroy"])->name("stocks.destroy");
});

Route::prefix("api")->group(function () {
    Route::get("/", [InformationController::class, "store"])->name("api.store");
    Route::get("/{stock}", [APIController::class, "load"])->name("api.show");
});

Route::get("/", function () {
    return to_route(config("markets-dashboard.routing.as") . "stocks.index");
})->name("dashboard");
