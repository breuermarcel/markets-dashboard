<?php

namespace Breuermarcel\FinanceDashboard\Http\Controllers;

use Breuermarcel\FinanceDashboard\Http\Helpers\APIController;
use Breuermarcel\FinanceDashboard\Models\Information;
use Breuermarcel\FinanceDashboard\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InformationController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->only("symbol"), [
            "symbol" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([], 500);
        }

        $validated = $validator->validated();

        $stock = Stock::find($validated["symbol"]);

        if ($stock->count() <= 0) {
            return response()->json([], 500);
        }

        $api = new APIController();
        $profile = $api->getAssetProfile($validated["symbol"]);
        $esg = $api->getEsgScore($validated["symbol"]);
        $income = $api->getIncome($validated["symbol"]);
        $cashflow = $api->getIncome($validated["symbol"]);
        $balanceSheet = $api->getBalanceSheet($validated["symbol"]);
        $recommendations = $api->getRecommendations($validated["symbol"]);
        $merged = array_merge($profile, $esg, $income, $cashflow, $balanceSheet, $recommendations);

        Information::updateOrCreate(["symbol" => strtoupper($validated["symbol"])], $merged);

        return response()->json($validated["symbol"]);
    }
}
