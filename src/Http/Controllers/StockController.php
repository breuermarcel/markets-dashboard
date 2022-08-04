<?php

namespace Breuermarcel\FinanceDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Breuermarcel\FinanceDashboard\Models\Stock;
use Breuermarcel\FinanceDashboard\Http\Helpers\APIController;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::simplePaginate(15);

        return view("finance-dashboard::stocks.list", compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("finance-dashboard::stocks.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //@todo use config for table
        $validator = Validator::make($request->all(), [
            "symbol" => "required|string|max:10",
            "wkn" => "nullable|string|max:25",
            "isin" => "nullable|string|max:25",
            "name" => "nullable|string|max:150"
        ]);

        if ($validator->fails()) {
            return redirect()->route("stocks.create")->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        Stock::updateOrCreate(
            ["symbol" => strtoupper($validated["symbol"])],
            [
                "symbol" => $validated["symbol"],
                "wkn" => $validated["wkn"],
                "isin" => $validated["isin"],
                "name" => $validated["name"]
            ]
        );

        return redirect()->route("stocks.index")->withSuccess(trans("Aktie erfolgreich hinzugefügt."));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        $information = $stock->information();

        if (request()->has("period")) {
            $history = APIController::getChart($stock->symbol, intval(request()->get("period")));
        } else {
            $history = APIController::getChart($stock->symbol, 30);
        }

        return view("finance-dashboard::stocks.detail", compact('stock', 'history'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        return view("finance-dashboard::stocks.edit", compact("stock"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        $validator = Validator::make($request->only(["wkn", "isin", "name"]), [
            "wkn" => "nullable|string|max:25",
            "isin" => "nullable|string|max:25",
            "name" => "nullable|string|max:150"
        ]);

        if ($validator->fails()) {
            return redirect()->route("stocks.edit")->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $stock->wkn = $validated["wkn"];
        $stock->isin = $validated["isin"];
        $stock->name = $validated["name"];
        $stock->save();

        return redirect()->route("stocks.index")->withSuccess(trans("Aktie erfolgreich aktualisiert."));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route("stocks.index")->withSuccess(trans("Aktie erfolgreich gelöscht."));
    }

    /**
     * Display the form to post csv-file.
     *
     * @return \Illuminate\Http\Response
     */
    public function import_csv()
    {
        return view("finance-dashboard::stocks.import");
    }

    /**
     * Import csv-file to stocks.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function do_import_csv(Request $request)
    {
        $validator = Validator::make($request->only("file"),
            ["file" => "required|mimes:csv,txt|max:2048"]
        );

        if ($validator->fails()) {
            return redirect()->route("stocks.import")->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

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
                        "wkn" => $data[1],
                        "isin" => $data[2],
                        "name" => $data[3]
                    ]
                );
            }

            $firstline = false;
        }

        fclose($csv);

        return redirect()->route("stocks.import")->withSuccess(trans("Import war erfolgreich."));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStocksByCriteria()
    {
        //
    }
}
