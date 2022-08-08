<?php

namespace Breuermarcel\FinanceDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Breuermarcel\FinanceDashboard\Models\Stock;
use Illuminate\Database\Eloquent\Collection;

class SearchController extends Controller
{
    /**
     * Handle handle.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->only("sword"), [
                "sword" => "required|string|max:50",
            ]);

            if ($validator->fails()) {
                return redirect()->route("stocks.create")->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();
            $search_results = $this->do_search($validated["sword"]);

            return View::make("finance-dashboard::components.search-results")->with("search_results", $search_results);
        }
    }

    /**
     * Search by word for stock.
     *
     * @param string $val
     * @return Collection
     */
    private function doSearch(string $val) : Collection
    {
        return Stock::select('symbol', 'name')
            ->where('symbol', 'LIKE', '%' . $val . '%')
            ->orWhere('name', 'LIKE', '%' . $val . '%')
            ->get();
    }
}
