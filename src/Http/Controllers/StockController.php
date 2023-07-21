<?php

namespace Breuermarcel\MarketsDashboard\Http\Controllers;

use Breuermarcel\MarketsDashboard\Http\Requests\CsvStoreRequest;
use Breuermarcel\MarketsDashboard\Http\Requests\StockStoreRequest;
use Breuermarcel\MarketsDashboard\Http\Requests\StockUpdateRequest;
use Breuermarcel\MarketsDashboard\Models\Stock;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class StockController extends Controller
{
    /**
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $stocks = Stock::simplePaginate(10);

        return view("markets-dashboard::stocks.list", compact("stocks"));
    }


    /**
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view("markets-dashboard::stocks.create");
    }

    /**
     * @param StockStoreRequest $request
     * @return mixed
     */
    public function store(StockStoreRequest $request)
    {
        $validated = $request->validated();

        Stock::updateOrCreate(
            ["symbol" => strtoupper($validated["symbol"])],
            [
                "symbol" => $validated["symbol"],
                "wkn" => $validated["wkn"],
                "isin" => $validated["isin"],
                "name" => $validated["name"]
            ]
        );

        return to_route(config("markets-dashboard.routing.as") . "stocks.index")->withSuccess(trans("Aktie erfolgreich hinzugefügt."));
    }

    /**
     * @param Stock $stock
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function show(Stock $stock): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view("markets-dashboard::stocks.detail", compact("stock"));
    }

    /**
     * @param Stock $stock
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function edit(Stock $stock): Application|View|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view("markets-dashboard::stocks.edit", compact("stock"));
    }

    /**
     * @param StockUpdateRequest $request
     * @param Stock $stock
     * @return mixed
     */
    public function update(StockUpdateRequest $request, Stock $stock): mixed
    {
        $validated = $request->safe()->only([
            "wkn", "isin", "name"
        ]);

        $stock->update($validated);

        return to_route(config("markets-dashboard.routing.as") . "stocks.index")->withSuccess(trans("Aktie erfolgreich aktualisiert."));
    }

    /**
     * @param Stock $stock
     * @return mixed
     */
    public function destroy(Stock $stock): mixed
    {
        $stock->delete();

        return to_route(config("markets-dashboard.routing.as") . "stocks.index")->withSuccess(trans("Aktie erfolgreich gelöscht."));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function importCSV(): Application|View|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view("markets-dashboard::stocks.import");
    }

    /**
     * @param CsvStoreRequest $request
     * @return mixed
     */
    public function storeCSV(CsvStoreRequest $request): mixed
    {
        $validated = $request->validated();
        $csv = fopen($request->file->getPathname(), "r");
        $firstline = true;

        while (($data = fgetcsv($csv)) !== false) {
            if (!$firstline) {
                Stock::updateOrCreate(
                    [
                        // search for item
                        "symbol" => strtoupper($data[0])
                    ],
                    [
                        // replace/insert with
                        "symbol" => strtoupper($data[0]),
                        "name" => $data[1]
                    ]
                );
            }

            $firstline = false;
        }

        fclose($csv);

        return to_route(config("markets-dashboard.routing.as") . "stocks.import")->withSuccess(trans("Import war erfolgreich."));
    }

    public function getStocksByCriteria()
    {
        //
    }
}
