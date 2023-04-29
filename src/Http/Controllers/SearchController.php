<?php

namespace Breuermarcel\MarketsDashboard\Http\Controllers;

use Breuermarcel\MarketsDashboard\Models\Stock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

class SearchController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|RedirectResponse|void
     * @throws ValidationException
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->only("sword"), [
                "sword" => "required|string|max:50",
            ]);

            $validated = $validator->validated();
            $search_results = $this->doSearch($validated["sword"]);

            return View::make("markets-dashboard::components.search-results")->with("search_results", $search_results);
        }
    }

    /**
     * Search by word for stock.
     *
     * @param string $val
     * @return Collection
     */
    private function doSearch(string $val): Collection
    {
        return Stock::select('symbol', 'name')
            ->where('symbol', 'LIKE', '%' . $val . '%')
            ->orWhere('name', 'LIKE', '%' . $val . '%')
            ->get();
    }
}
