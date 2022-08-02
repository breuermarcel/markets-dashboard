<?php

namespace Breuermarcel\FinanceDashboard\Http\Controllers;

use Breuermarcel\FinanceDashboard\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        return view("finance-dashboard::stocks.list", ["stocks" => $stocks]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $csv = fopen($request->file->getPathname(), "r");
        $firstline = true;

        while (($data = fgetcsv($csv)) !== false) {
            if (!$firstline) {
                Stock::updateOrCreate(
                    ["symbol" => strtoupper($data[0])],
                    [
                        "symbol" => $data[0],
                        "wkn" => $data[1],
                        "isin" => $data[2],
                        "name" => $data[3]
                    ]
                );
            }

            $firstline = false;
        }

        fclose($csv);

        return redirect()->back()->with("success", trans("Import war erfolgreich."));
    }

}
