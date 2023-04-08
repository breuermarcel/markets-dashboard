<?php

namespace Breuermarcel\MarketsDashboard\Http\Controllers;

use Breuermarcel\MarketsDashboard\Http\Helpers\APIController;
use Breuermarcel\MarketsDashboard\Models\Information;
use Breuermarcel\MarketsDashboard\Models\Stock;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index()
    {
        $stocks = Stock::select("symbol")->get()->toJson();

        return view("markets-dashboard::stocks.information.import", compact("stocks"));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->only("symbol"), [
            "symbol" => "required"
        ]);

        if ($validator->fails()) {
            abort(500);
        }

        $validated = $validator->validated();

        Stock::findOrFail($validated["symbol"]);

        $api = new APIController();
        $profile = $api->getAssetProfile($validated["symbol"]);
        $esg = $api->getEsgScore($validated["symbol"]);
        $income = $api->getIncome($validated["symbol"]);
        $cashflow = $api->getIncome($validated["symbol"]);
        $balanceSheet = $api->getBalanceSheet($validated["symbol"]);
        $recommendations = $api->getRecommendations($validated["symbol"]);
        $merged = array_merge($profile, $esg, $income, $cashflow, $balanceSheet, $recommendations);

        dd($merged);

        Information::updateOrCreate(["symbol" => strtoupper($validated["symbol"])], $merged);

        return response()->json($validated["symbol"]);
    }
}
