<?php

namespace Breuermarcel\MarketsDashboard\Http\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Breuermarcel\MarketsDashboard\Models\Stock;

class APIController
{
    public string $chart_url = "https://query2.finance.yahoo.com/v8/finance/chart/?symbol=";

    public string $finance_url = "https://query2.finance.yahoo.com/v10/finance/quoteSummary/?symbol=";

    public function load()
    {
        $validator = Validator::make(request()->all(), [
            "symbol" => "required|string|max:10",
            "module" => "required|string"
        ]);

        if ($validator->fails()) {
            abort(500);
        }

        if (request()->has("module") && request()->has("symbol")) {
            $stock = Stock::findOrFail(request()->get("symbol"));

            switch (request()->get("module")) {
                case "chart":
                    return request()->has("period") ? View::make("markets-dashboard::stocks.components.graph")->with(["history" => $this->getChart(request()->get("symbol"), intval(request()->get("period"))), "stock" => $stock]) : View::make("markets-dashboard::stocks.components.graph")->with(["history" => $this->getChart(request()->get("symbol"), 30), "stock" => $stock]);

                case "profile":
                    return request()->has("html") ? View::make("markets-dashboard::stocks.components.profile")->with(["profile" => $this->getAssetProfile(request()->get("symbol")), "stock" => $stock]) : $this->getAssetProfile(request()->get("symbol"));

                case "esg":
                    return request()->has("html") ? View::make("markets-dashboard::stocks.components.esg")->with(["esg" => $this->getEsgScore(request()->get("symbol")), "stock" => $stock]) : $this->getEsgScore(request()->get("symbol"));

                case "income":
                    return request()->has("html") ? View::make("markets-dashboard::stocks.components.income")->with(["income" => $this->getIncome(request()->get("symbol")), "stock" => $stock]) : $this->getIncome(request()->get("symbol"));

                case "cashflow":
                    return request()->has("html") ? View::make("markets-dashboard::stocks.components.cashflow")->with(["cashflow" => $this->getCashflow(request()->get("symbol")), "stock" => $stock]) : $this->getCashflow(request()->get("symbol"));

                case "balance_sheet":
                    return request()->has("html") ? View::make("markets-dashboard::stocks.components.balance_sheet")->with(["balance_sheet" => $this->getBalanceSheet(request()->get("symbol")), "stock" => $stock]) : $this->getBalanceSheet(request()->get("symbol"));

                case "recommendations":
                    return request()->has("html") ? View::make("markets-dashboard::stocks.components.recommendations")->with(["recommendations" => $this->getRecommendations(request()->get("symbol")), "stock" => $stock]) : $this->getRecommendations(request()->get("symbol"));
            }
        }

        return false;
    }

    protected function getChart(string $symbol, int $period): array
    {
        $chart = [];

        $from_date = Carbon::now()->subDays($period)->format("U");
        $interval = "1d";

        $url = $this->chart_url . $symbol . "&period1=" . $from_date . "&period2=9999999999&interval=" . $interval;
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (($data["chart"]["error"] === null) && array_key_exists("adjclose", $data["chart"]["result"][0]["indicators"]["adjclose"][0])) {
            $chart = $this->validateChart($data["chart"]["result"][0]);
        }

        return $chart;
    }

    private function validateChart(array $data): array
    {
        $chart = [];

        foreach ($data["timestamp"] as $d_key => $date) {
            foreach ($data["indicators"]["adjclose"][0]["adjclose"] as $v_key => $value) {
                if (($value !== null) && $d_key === $v_key) {
                    $chart[] = [
                        "adj_close" => $value,
                        "date" => Carbon::parse($date)->format("d.m.Y"),
                        "currency" => $data["meta"]["currency"]
                    ];
                }
            }
        }

        return $chart;
    }

    public function getAssetProfile(string $symbol): array
    {
        $asset = [];

        $url = $this->finance_url . $symbol . "&modules=assetProfile";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("assetProfile", $data["quoteSummary"]["result"][0])) {
                $asset = $this->validateAssetProfile($data["quoteSummary"]["result"][0]["assetProfile"]);
            }
        }

        return $asset;
    }

    private function validateAssetProfile(array $data): array
    {
        $profile = [
            "address" => null,
            "city" => null,
            "state" => null,
            "zip" => null,
            "country" => null,
            "website" => null,
            "industry" => null,
            "sector" => null,
            "business_summary" => null,
            "employees" => null,
            "company_officiers" => null,
        ];

        if (array_key_exists("address1", $data)) {
            $profile["address"] = $data["address1"];
        }

        if (array_key_exists("city", $data)) {
            $profile["city"] = $data["city"];
        }

        if (array_key_exists("state", $data)) {
            $profile["state"] = $data["state"];
        }

        if (array_key_exists("zip", $data)) {
            $profile["zip"] = $data["zip"];
        }

        if (array_key_exists("country", $data)) {
            $profile["country"] = $data["country"];
        }

        if (array_key_exists("website", $data)) {
            $profile["website"] = $data["website"];
        }

        if (array_key_exists("industry", $data)) {
            $profile["industry"] = $data["industry"];
        }

        if (array_key_exists("sector", $data)) {
            $profile["sector"] = $data["sector"];
        }

        if (array_key_exists("longBusinessSummary", $data)) {
            $profile["business_summary"] = $data["longBusinessSummary"];
        }

        if (array_key_exists("fullTimeEmployees", $data)) {
            $profile["employees"] = $data["fullTimeEmployees"];
        }

        if (array_key_exists("companyOfficers", $data)) {
            foreach ($data["companyOfficers"] as $index => $officer) {
                if (array_key_exists("name", $officer)) {
                    $profile["company_officiers"][$index]["name"] = $officer["name"];
                }

                if (array_key_exists("age", $officer)) {
                    $profile["company_officiers"][$index]["age"] = $officer["age"];
                }

                if (array_key_exists("title", $officer)) {
                    $profile["company_officiers"][$index]["job_title"] = $officer["title"];
                }

                if (array_key_exists("totalPay", $officer)) {
                    if (array_key_exists("raw", $officer["totalPay"])) {
                        $profile["company_officiers"][$index]["total_pay"] = $officer["totalPay"]["raw"];
                    }
                }
            }
        }

        return $profile;
    }

    public function getEsgScore(string $symbol): array
    {
        $esg = [];

        $url = $this->finance_url . $symbol . "&modules=esgScores";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("esgScores", $data["quoteSummary"]["result"][0])) {
                $esg = $this->validateEsgScore($data["quoteSummary"]["result"][0]["esgScores"]);
            }
        }

        return $esg;
    }

    private function validateEsgScore(array $data): array
    {
        $esg = [
            "total" => null,
            "environment" => null,
            "social" => null,
            "governance" => null,
            "year" => null,
            "performance" => null,
            "peer_total" => null,
            "peer_environment" => null,
            "peer_social" => null,
            "peer_governance" => null,
        ];

        if (array_key_exists("totalEsg", $data)) {
            if (array_key_exists("raw", $data["totalEsg"])) {
                $esg["total"] = $data["totalEsg"]["raw"];
            }
        }

        if (array_key_exists("environmentScore", $data)) {
            if (array_key_exists("raw", $data["environmentScore"])) {
                $esg["environment"] = $data["environmentScore"]["raw"];
            }
        }

        if (array_key_exists("socialScore", $data)) {
            if (array_key_exists("raw", $data["socialScore"])) {
                $esg["social"] = $data["socialScore"]["raw"];
            }
        }

        if (array_key_exists("governanceScore", $data)) {
            if (array_key_exists("raw", $data["governanceScore"])) {
                $esg["governance"] = $data["governanceScore"]["raw"];
            }
        }

        if (array_key_exists("ratingYear", $data)) {
            $esg["year"] = $data["ratingYear"];
        }

        if (array_key_exists("esgPerformance", $data)) {
            $esg["performance"] = $data["esgPerformance"];
        }

        if (array_key_exists("peerEsgScorePerformance", $data)) {
            if (array_key_exists("avg", $data["peerEsgScorePerformance"])) {
                $esg["peer_total"] = $data["peerEsgScorePerformance"]["avg"];
            }
        }

        if (array_key_exists("peerEnvironmentPerformance", $data)) {
            if (array_key_exists("avg", $data["peerEnvironmentPerformance"])) {
                $esg["peer_environment"] = $data["peerEnvironmentPerformance"]["avg"];
            }
        }

        if (array_key_exists("peerSocialPerformance", $data)) {
            if (array_key_exists("avg", $data["peerSocialPerformance"])) {
                $esg["peer_social"] = $data["peerSocialPerformance"]["avg"];
            }
        }

        if (array_key_exists("peerGovernancePerformance", $data)) {
            if (array_key_exists("avg", $data["peerGovernancePerformance"])) {
                $esg["peer_governance"] = $data["peerGovernancePerformance"]["avg"];
            }
        }

        return $esg;
    }

    public function getIncome(string $symbol): array
    {
        $income = [];

        $url = $this->finance_url . $symbol . "&modules=incomeStatementHistory";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("incomeStatementHistory", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("incomeStatementHistory", $data["quoteSummary"]["result"][0]["incomeStatementHistory"])) {
                    $income = $this->validateIncome($data["quoteSummary"]["result"][0]["incomeStatementHistory"]);
                }
            }
        }

        return $income;
    }

    private function validateIncome(array $data): array
    {
        $income = [
            "date" => null,
            "total_revenue" => null,
            "cost_of_revenue" => null,
            "gross_profit" => null,
            "research_and_development" => null,
            "selling_general_administrative" => null,
            "total_operating_expanses" => null,
            "ebit" => null,
            "interest_expense" => null,
            "income_before_tax" => null,
            "income_tax_expense" => null,
            "net_income" => null
        ];

        if (array_key_exists("endDate", $data)) {
            if (array_key_exists("raw", $data["endDate"])) {
                $income["date"] = $data["endDate"]["raw"];
            }
        }

        if (array_key_exists("totalRevenue", $data)) {
            if (array_key_exists("raw", $data["totalRevenue"])) {
                $income["total_revenue"] = $data["totalRevenue"]["raw"];
            }
        }

        if (array_key_exists("costOfRevenue", $data)) {
            if (array_key_exists("raw", $data["costOfRevenue"])) {
                $income["cost_of_revenue"] = $data["costOfRevenue"]["raw"];
            }
        }

        if (array_key_exists("grossProfit", $data)) {
            if (array_key_exists("raw", $data["grossProfit"])) {
                $income["gross_profit"] = $data["grossProfit"]["raw"];
            }
        }

        if (array_key_exists("researchDevelopment", $data)) {
            if (array_key_exists("raw", $data["researchDevelopment"])) {
                $income["research_and_development"] = $data["researchDevelopment"]["raw"];
            }
        }

        if (array_key_exists("sellingGeneralAdministrative", $data)) {
            if (array_key_exists("raw", $data["sellingGeneralAdministrative"])) {
                $income["selling_general_administrative"] = $data["sellingGeneralAdministrative"]["raw"];
            }
        }

        if (array_key_exists("ebit", $data)) {
            if (array_key_exists("raw", $data["ebit"])) {
                $income["ebit"] = $data["ebit"]["raw"];
            }
        }

        if (array_key_exists("interestExpense", $data)) {
            if (array_key_exists("raw", $data["interestExpense"])) {
                $income["interest_expense"] = $data["interestExpense"]["raw"];
            }
        }

        if (array_key_exists("incomeBeforeTax", $data)) {
            if (array_key_exists("raw", $data["incomeBeforeTax"])) {
                $income["income_before_tax"] = $data["incomeBeforeTax"]["raw"];
            }
        }

        if (array_key_exists("incomeTaxExpense", $data)) {
            if (array_key_exists("raw", $data["incomeTaxExpense"])) {
                $income["income_tax_expense"] = $data["incomeTaxExpense"]["raw"];
            }
        }

        if (array_key_exists("netIncome", $data)) {
            if (array_key_exists("raw", $data["netIncome"])) {
                $income["net_income"] = $data["netIncome"]["raw"];
            }
        }

        $income["total_operating_expanses"] = $income["research_and_development"] + $income["selling_general_administrative"];

        return $income;
    }

    public function getCashflow(string $symbol): array
    {
        $cashflow = [];

        $url = $this->finance_url . $symbol . "&modules=cashflowStatementHistory";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("cashflowStatementHistory", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("cashflowStatements", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"])) {
                    $cashflow = $this->validateCashflow($data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]);
                }
            }
        }

        return $cashflow;
    }

    private function validateCashflow(array $data): array
    {
        $cashflow = [
            "date" => null,
            "net_income" => null,
            "depreciation" => null,
            "change_to_net_income" => null,
            "change_to_account_receivables" => null,
            "change_to_liabilities" => null,
            "change_to_inventory" => null,
            "change_to_operating_activities" => null,
            "capital_expenditures" => null,
            "investments" => null,
            "dividends_paid" => null,
            "net_borrowings" => null,
            "change_in_cash" => null,
            "repurchase_of_stock" => null,
            "total_cash_from_operating_activities" => null,
            "total_cashflows_from_investing_activities" => null,
            "total_cash_from_financing_activities" => null,
        ];

        if (array_key_exists("endDate", $data)) {
            if (array_key_exists("raw", $data["endDate"])) {
                $cashflow["date"] = $data["endDate"]["raw"]; //@todo proof if this is correct
            }
        }

        if (array_key_exists("netIncome", $data)) {
            if (array_key_exists("raw", $data["netIncome"])) {
                $cashflow["net_income"] = $data["netIncome"]["raw"];
            }
        }

        if (array_key_exists("depreciation", $data)) {
            if (array_key_exists("raw", $data["depreciation"])) {
                $cashflow["depreciation"] = $data["depreciation"]["raw"];
            }
        }

        if (array_key_exists("changeToNetincome", $data)) {
            if (array_key_exists("raw", $data["changeToNetincome"])) {
                $cashflow["change_to_net_income"] = $data["changeToNetincome"]["raw"];
            }
        }

        if (array_key_exists("changeToAccountReceivables", $data)) {
            if (array_key_exists("raw", $data["changeToAccountReceivables"])) {
                $cashflow["change_to_account_receivables"] = $data["changeToAccountReceivables"]["raw"];
            }
        }

        if (array_key_exists("changeToLiabilities", $data)) {
            if (array_key_exists("raw", $data["changeToLiabilities"])) {
                $cashflow["change_to_liabilities"] = $data["changeToLiabilities"]["raw"];
            }
        }

        if (array_key_exists("changeToInventory", $data)) {
            if (array_key_exists("raw", $data["changeToInventory"])) {
                $cashflow["change_to_inventory"] = $data["changeToInventory"]["raw"];
            }
        }

        if (array_key_exists("changeToOperatingActivities", $data)) {
            if (array_key_exists("raw", $data["changeToOperatingActivities"])) {
                $cashflow["change_to_operating_activities"] = $data["changeToOperatingActivities"]["raw"];
            }
        }

        if (array_key_exists("capitalExpenditures", $data)) {
            if (array_key_exists("raw", $data["capitalExpenditures"])) {
                $cashflow["capital_expenditures"] = $data["capitalExpenditures"]["raw"];
            }
        }

        if (array_key_exists("investments", $data)) {
            if (array_key_exists("raw", $data["investments"])) {
                $cashflow["investments"] = $data["investments"]["raw"];
            }
        }

        if (array_key_exists("dividendsPaid", $data)) {
            if (array_key_exists("raw", $data["dividendsPaid"])) {
                $cashflow["dividends_paid"] = $data["dividendsPaid"]["raw"];
            }
        }

        if (array_key_exists("netBorrowings", $data)) {
            if (array_key_exists("raw", $data["netBorrowings"])) {
                $cashflow["net_borrowings"] = $data["netBorrowings"]["raw"];
            }
        }

        if (array_key_exists("changeInCash", $data)) {
            if (array_key_exists("raw", $data["changeInCash"])) {
                $cashflow["change_in_cash"] = $data["changeInCash"]["raw"];
            }
        }

        if (array_key_exists("repurchaseOfStock", $data)) {
            if (array_key_exists("raw", $data["repurchaseOfStock"])) {
                $cashflow["repurchase_of_stock"] = $data["repurchaseOfStock"]["raw"];
            }
        }

        if (array_key_exists("totalCashFromOperatingActivities", $data)) {
            if (array_key_exists("raw", $data["totalCashFromOperatingActivities"])) {
                $cashflow["total_cash_from_operating_activities"] = $data["totalCashFromOperatingActivities"]["raw"];
            }
        }

        if (array_key_exists("totalCashflowsFromInvestingActivities", $data)) {
            if (array_key_exists("raw", $data["totalCashflowsFromInvestingActivities"])) {
                $cashflow["total_cashflows_from_investing_activities"] = $data["totalCashflowsFromInvestingActivities"]["raw"];
            }
        }

        if (array_key_exists("totalCashFromFinancingActivities", $data)) {
            if (array_key_exists("raw", $data["totalCashFromFinancingActivities"])) {
                $cashflow["total_cash_from_financing_activities"] = $data["totalCashFromFinancingActivities"]["raw"];
            }
        }

        return $cashflow;
    }

    public function getBalanceSheet(string $symbol): array
    {
        $balance_sheet = [];

        $url = $this->finance_url . $symbol . "&modules=balanceSheetHistory";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("balanceSheetHistory", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("balanceSheetStatements", $data["quoteSummary"]["result"][0]["balanceSheetHistory"])) {
                    $balance_sheet = $this->validateBalanceSheet($data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]);
                }
            }
        }

        return $balance_sheet;
    }

    private function validateBalanceSheet(array $data): array
    {
        $balance_sheet = [
            "date" => null,
            "cash" => null,
            "short_term_investments" => null,
            "cash_total" => null,
            "net_receivables" => null,
            "inventory" => null,
            "other_current_assets" => null,
            "total_current_assets" => null,
            "long_term_investments" => null,
            "property_plant_equipment" => null,
            "other_assets" => null,
            "non_current_assets" => null,
            "total_assets" => null,
            "accounts_payable" => null,
            "short_long_term_debt" => null,
            "other_current_liab" => null,
            "long_term_liab" => null,
            "long_term_debt" => null,
            "other_liab" => null,
            "total_current_liabilities" => null,
            "total_liab" => null,
            "common_stock" => null,
            "retained_earnings" => null,
            "treasury_stock" => null,
            "other_stockholder_equity" => null,
            "total_stockholder_equity" => null,
            "net_tangible_assets" => null,
            "total_non_current_assets" => null,
        ];

        if (array_key_exists("endDate", $data)) {
            if (array_key_exists("raw", $data["endDate"])) {
                $balance_sheet["date"] = $data["endDate"]["raw"];
            }
        }

        if (array_key_exists("cash", $data)) {
            if (array_key_exists("raw", $data["cash"])) {
                $balance_sheet["cash"] = $data["cash"]["raw"];
            }
        }

        if (array_key_exists("shortTermInvestments", $data)) {
            if (array_key_exists("raw", $data["shortTermInvestments"])) {
                $balance_sheet["short_term_investments"] = $data["shortTermInvestments"]["raw"];
            }
        }

        if (array_key_exists("netReceivables", $data)) {
            if (array_key_exists("raw", $data["netReceivables"])) {
                $balance_sheet["net_receivables"] = $data["netReceivables"]["raw"];
            }
        }

        if (array_key_exists("inventory", $data)) {
            if (array_key_exists("raw", $data["inventory"])) {
                $balance_sheet["inventory"] = $data["inventory"]["raw"];
            }
        }

        if (array_key_exists("otherCurrentAssets", $data)) {
            if (array_key_exists("raw", $data["otherCurrentAssets"])) {
                $balance_sheet["other_current_assets"] = $data["otherCurrentAssets"]["raw"];
            }
        }

        if (array_key_exists("totalCurrentAssets", $data)) {
            if (array_key_exists("raw", $data["totalCurrentAssets"])) {
                $balance_sheet["total_current_assets"] = $data["totalCurrentAssets"]["raw"];
            }
        }

        if (array_key_exists("longTermInvestments", $data)) {
            if (array_key_exists("raw", $data["longTermInvestments"])) {
                $balance_sheet["long_term_investments"] = $data["longTermInvestments"]["raw"];
            }
        }

        if (array_key_exists("propertyPlantEquipment", $data)) {
            if (array_key_exists("raw", $data["propertyPlantEquipment"])) {
                $balance_sheet["property_plant_equipment"] = $data["propertyPlantEquipment"]["raw"];
            }
        }

        if (array_key_exists("otherAssets", $data)) {
            if (array_key_exists("raw", $data["otherAssets"])) {
                $balance_sheet["other_assets"] = $data["otherAssets"]["raw"];
            }
        }

        if (array_key_exists("totalAssets", $data)) {
            if (array_key_exists("raw", $data["totalAssets"])) {
                $balance_sheet["total_assets"] = $data["totalAssets"]["raw"];
            }
        }

        if (array_key_exists("accountsPayable", $data)) {
            if (array_key_exists("raw", $data["accountsPayable"])) {
                $balance_sheet["accounts_payable"] = $data["accountsPayable"]["raw"];
            }
        }

        if (array_key_exists("shortLongTermDebt", $data)) {
            if (array_key_exists("raw", $data["shortLongTermDebt"])) {
                $balance_sheet["short_long_term_debt"] = $data["shortLongTermDebt"]["raw"];
            }
        }

        if (array_key_exists("otherCurrentLiab", $data)) {
            if (array_key_exists("raw", $data["otherCurrentLiab"])) {
                $balance_sheet["other_current_liab"] = $data["otherCurrentLiab"]["raw"];
            }
        }

        if (array_key_exists("longTermDebt", $data)) {
            if (array_key_exists("raw", $data["longTermDebt"])) {
                $balance_sheet["long_term_debt"] = $data["longTermDebt"]["raw"];
            }
        }

        if (array_key_exists("otherLiab", $data)) {
            if (array_key_exists("raw", $data["otherLiab"])) {
                $balance_sheet["other_liab"] = $data["otherLiab"]["raw"];
            }
        }

        if (array_key_exists("totalCurrentLiabilities", $data)) {
            if (array_key_exists("raw", $data["totalCurrentLiabilities"])) {
                $balance_sheet["total_current_liabilities"] = $data["totalCurrentLiabilities"]["raw"];
            }
        }

        if (array_key_exists("totalLiab", $data)) {
            if (array_key_exists("raw", $data["totalLiab"])) {
                $balance_sheet["total_liab"] = $data["totalLiab"]["raw"];
            }
        }

        if (array_key_exists("commonStock", $data)) {
            if (array_key_exists("raw", $data["commonStock"])) {
                $balance_sheet["common_stock"] = $data["commonStock"]["raw"];
            }
        }

        if (array_key_exists("retainedEarnings", $data)) {
            if (array_key_exists("raw", $data["retainedEarnings"])) {
                $balance_sheet["retained_earnings"] = $data["retainedEarnings"]["raw"];
            }
        }

        if (array_key_exists("treasuryStock", $data)) {
            if (array_key_exists("raw", $data["treasuryStock"])) {
                $balance_sheet["treasury_stock"] = $data["treasuryStock"]["raw"];
            }
        }

        if (array_key_exists("otherStockholderEquity", $data)) {
            if (array_key_exists("raw", $data["otherStockholderEquity"])) {
                $balance_sheet["other_stockholder_equity"] = $data["otherStockholderEquity"]["raw"];
            }
        }

        if (array_key_exists("totalStockholderEquity", $data)) {
            if (array_key_exists("raw", $data["totalStockholderEquity"])) {
                $balance_sheet["total_stockholder_equity"] = $data["totalStockholderEquity"]["raw"];
            }
        }

        if (array_key_exists("netTangibleAssets", $data)) {
            if (array_key_exists("raw", $data["netTangibleAssets"])) {
                $balance_sheet["net_tangible_assets"] = $data["netTangibleAssets"]["raw"];
            }
        }

        $balance_sheet["cash_total"] = $balance_sheet["cash"] + $balance_sheet["short_term_investments"];
        $balance_sheet["long_term_liab"] = $balance_sheet["total_liab"] - $balance_sheet["total_current_liabilities"];
        $balance_sheet["total_non_current_assets"] = $balance_sheet["total_assets"] - $balance_sheet["total_current_assets"];

        return $balance_sheet;
    }

    public function getRecommendations(string $symbol): array
    {
        $recommendations = [];

        $url = $this->finance_url . $symbol . "&modules=recommendationTrend";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("recommendationTrend", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("trend", $data["quoteSummary"]["result"][0]["recommendationTrend"])) {
                    if (array_key_exists(0, $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"])) {
                        $recommendations = $this->validateRecommendations($data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"][0]);
                    }
                }
            }
        }

        return $recommendations;
    }

    private function validateRecommendations(array $data): array
    {
        $recommendations = [
            "strong_buy" => null,
            "buy" => null,
            "hold" => null,
            "sell" => null,
            "strong_sell" => null
        ];

        if (array_key_exists("strongBuy", $data)) {
            $recommendations["strong_buy"] = $data["strongBuy"];
        }

        if (array_key_exists("buy", $data)) {
            $recommendations["buy"] = $data["buy"];
        }

        if (array_key_exists("hold", $data)) {
            $recommendations["hold"] = $data["hold"];
        }

        if (array_key_exists("sell", $data)) {
            $recommendations["sell"] = $data["sell"];
        }

        if (array_key_exists("strongSell", $data)) {
            $recommendations["strong_sell"] = $data["strongSell"];
        }


        return $recommendations;
    }

    public function getUpgradeDowngrade(string $symbol): array
    {
        $upgrade_downgrade = [];

        $url = $this->finance_url . $symbol . "&modules=upgradeDowngradeHistory";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("upgradeDowngradeHistory", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("history", $data["quoteSummary"]["result"][0]["upgradeDowngradeHistory"])) {
                    $upgrade_downgrade = $this->validateUpgradeDowngrade($data["quoteSummary"]["result"][0]["upgradeDowngradeHistory"]["history"]);
                }
            }
        }

        return $upgrade_downgrade;
    }

    private function validateUpgradeDowngrade(array $data): array
    {
        $upgrade_downgrade = [];

        $index = 0;

        foreach ($data as $set) {
            if ($index <= 10) {
                if (array_key_exists("epochGradeDate", $set)) {
                    $upgrade_downgrade[$index]["date"] = $set["epochGradeDate"];
                }

                if (array_key_exists("firm", $set)) {
                    $upgrade_downgrade[$index]["firm"] = $set["firm"];
                }

                if (array_key_exists("fromGrade", $set)) {
                    $upgrade_downgrade[$index]["grade"]["from"] = $set["fromGrade"];
                }

                if (array_key_exists("toGrade", $set)) {
                    $upgrade_downgrade[$index]["grade"]["to"] = $set["toGrade"];
                }
            }

            $index++;
        }

        return $upgrade_downgrade;
    }
}
