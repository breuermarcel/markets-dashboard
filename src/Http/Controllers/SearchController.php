<?php

namespace Breuermarcel\FinanceDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Breuermarcel\FinanceDashboard\Models\Stock;

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
            $search_resullts = $this->do_search($validated["sword"]);

            return View::make('components.search-results')->with('search_results', $search_resullts);
        }
    }

    /**
     * Search by word in stocks.
     *
     * @param string $val
     * @return Stock
     */
    public function do_search(string $val) : Stock
    {
        return Stock::select('symbol', 'name')
            ->where('symbol', 'LIKE', '%' . $val . '%')
            ->orWhere('name', 'LIKE', '%' . $val . '%')
            ->get();
    }
}
