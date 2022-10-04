<?php

namespace Breuermarcel\FinanceDashboard\Http\Helpers;

use Breuermarcel\FinanceDashboard\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class APIController
{
    /**
     * Yahoo finance API-url.
     *
     * @var string
     */
    private string $chart_url = "https://query2.finance.yahoo.com/v8/finance/chart/?symbol=";

    /**
     * Yahoo finance API-url.
     *
     * @var string
     */
    private string $finance_url = "https://query2.finance.yahoo.com/v10/finance/quoteSummary/?symbol=";

    /**
     * Yahoo finance modules.
     *
     * @var array
     * @todo remove when all modules added.
     */
    private array $modules = [
        "index_trend" => "indexTrend",
        "industry_trend" => "industryTrend",
        "sector_trend" => "sectorTrend"
    ];

    /**
     * @return false|\Illuminate\Contracts\View\View|null[]
     * @throws \JsonException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function load() {
        // todo validate requests

        if (request()->has("module") && request()->has("symbol")) {
            $stock = Stock::findOrFail(request()->get("symbol"));

            switch (request()->get("module")) {
                case "chart":
                    return request()->has("period") ? View::make("finance-dashboard::stocks.components.graph")->with(["history" => $this->getChart(request()->get("symbol"), intval(request()->get("period"))), "stock" => $stock]) : View::make("finance-dashboard::stocks.components.graph")->with(["history"=> $this->getChart(request()->get("symbol"), 30), "stock" => $stock]);

                case "profile":
                    return request()->has("html") ? View::make("finance-dashboard::stocks.components.profile")->with(["profile" => $this->getAssetProfile(request()->get("symbol")), "stock" => $stock]) : $this->getAssetProfile(request()->get("symbol"));

                case "esg":
                    return request()->has("html") ? View::make("finance-dashboard::stocks.components.esg")->with(["esg" => $this->getEsgScore(request()->get("symbol")), "stock" => $stock]) : $this->getEsgScore(request()->get("symbol"));

                case "income":
                    return request()->has("html") ? View::make("finance-dashboard::stocks.components.income")->with(["income" => $this->getIncome(request()->get("symbol")), "stock" => $stock]): $this->getIncome(request()->get("symbol"));

                case "cashflow":
                    return request()->has("html") ? View::make("finance-dashboard::stocks.components.cashflow")->with(["cashflow" => $this->getCashflow(request()->get("symbol")), "stock" => $stock]) : $this->getCashflow(request()->get("symbol"));

                case "balance_sheet":
                    return request()->has("html") ? View::make("finance-dashboard::stocks.components.balance_sheet")->with(["balance_sheet" => $this->getBalanceSheet(request()->get("symbol")), "stock" => $stock]) : $this->getBalanceSheet(request()->get("symbol"));

                case "recommendations":
                    return request()->has("html") ? View::make("finance-dashboard::stocks.components.recommendations")->with(["recommendations" => $this->getRecommendations(request()->get("symbol")), "stock" => $stock]) : $this->getRecommendations(request()->get("symbol"));
            }
        }

        return false;
    }

    /**
     * Get financial chart from API.
     *
     * @param string $symbol
     * @param int $period
     * @return array
     */
    private function getChart(string $symbol, int $period): array
    {
        $chart = [];

        $from_date = Carbon::now()->subDays($period)->format("U");
        $interval = "1d";

        $url = $this->chart_url . $symbol . "&period1=" . $from_date . "&period2=9999999999&interval=" . $interval;
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($data["chart"]["error"] === null) {
            if (array_key_exists("adjclose", $data["chart"]["result"][0]["indicators"]["adjclose"][0])) {
                foreach ($data["chart"]["result"][0]["timestamp"] as $d_key => $date) {
                    foreach ($data["chart"]["result"][0]["indicators"]["adjclose"][0]["adjclose"] as $v_key => $value) {
                        if (($value !== null) && $d_key === $v_key) {
                            $chart[] = [
                                "adj_close" => $value,
                                "date" => Carbon::parse($date)->format("d.m.Y"),
                                "currency" => $data["chart"]["result"][0]["meta"]["currency"]
                            ];
                        }
                    }
                }
            }
        }

        return $chart;
    }

    /**
     * Get asset profile from API.
     *
     * @param string $symbol
     * @return array
     */
    private function getAssetProfile(string $symbol): array
    {
        $asset = [
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

        $url = $this->finance_url . $symbol . "&modules=assetProfile";
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("assetProfile", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("address1", $data["quoteSummary"]["result"][0]["assetProfile"])) {
                    $asset["address"] = $data["quoteSummary"]["result"][0]["assetProfile"]["address1"];
                }

                if (array_key_exists("city", $data["quoteSummary"]["result"][0]["assetProfile"])) {
                    $asset["city"] = $data["quoteSummary"]["result"][0]["assetProfile"]["city"];
                }

                if (array_key_exists("state", $data["quoteSummary"]["result"][0]["assetProfile"])) {
                    $asset["state"] = $data["quoteSummary"]["result"][0]["assetProfile"]["state"];
                }

                if (array_key_exists("zip", $data["quoteSummary"]["result"][0]["assetProfile"])) {
                    $asset["zip"] = $data["quoteSummary"]["result"][0]["assetProfile"]["zip"];
                }

                if (array_key_exists("country", $data["quoteSummary"]["result"][0]["assetProfile"])) {
                    $asset["country"] = $data["quoteSummary"]["result"][0]["assetProfile"]["country"];
                }

                if (array_key_exists("website", $data["quoteSummary"]["result"][0]["assetProfile"])) {
                    $asset["website"] = $data["quoteSummary"]["result"][0]["assetProfile"]["website"];
                }

                if (array_key_exists("industry", $data["quoteSummary"]["result"][0]["assetProfile"])) {
                    $asset["industry"] = $data["quoteSummary"]["result"][0]["assetProfile"]["industry"];
                }

                if (array_key_exists("sector", $data["quoteSummary"]["result"][0]["assetProfile"])) {
                    $asset["sector"] = $data["quoteSummary"]["result"][0]["assetProfile"]["sector"];
                }

                if (array_key_exists("longBusinessSummary", $data["quoteSummary"]["result"][0]["assetProfile"])) {
                    $asset["business_summary"] = $data["quoteSummary"]["result"][0]["assetProfile"]["longBusinessSummary"];
                }

                if (array_key_exists("fullTimeEmployees", $data["quoteSummary"]["result"][0]["assetProfile"])) {
                    $asset["employees"] = $data["quoteSummary"]["result"][0]["assetProfile"]["fullTimeEmployees"];
                }

                if (array_key_exists("companyOfficers", $data["quoteSummary"]["result"][0]["assetProfile"])) {
                    foreach ($data["quoteSummary"]["result"][0]["assetProfile"]["companyOfficers"] as $index => $officer) {
                        if (array_key_exists("name", $officer)) {
                            $asset["company_officiers"][$index]["name"] = $officer["name"];
                        }

                        if (array_key_exists("age", $officer)) {
                            $asset["company_officiers"][$index]["age"] = $officer["age"];
                        }

                        if (array_key_exists("title", $officer)) {
                            $asset["company_officiers"][$index]["job_title"] = $officer["title"];
                        }

                        if (array_key_exists("totalPay", $officer)) {
                            if (array_key_exists("raw", $officer["totalPay"])) {
                                $asset["company_officiers"][$index]["total_pay"] = $officer["totalPay"]["raw"];
                            }
                        }
                    }
                }
            }
        }

        return $asset;
    }

    /**
     * Get esg score from API.
     *
     * @param string $symbol
     * @return array
     */
    private function getEsgScore(string $symbol): array
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

        $url = $this->finance_url . $symbol . "&modules=esgScores";
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("esgScores", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("totalEsg", $data["quoteSummary"]["result"][0]["esgScores"])) {
                    if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["esgScores"]["totalEsg"])) {
                        $esg["total"] = $data["quoteSummary"]["result"][0]["esgScores"]["totalEsg"]["raw"];
                    }
                }

                if (array_key_exists("environmentScore", $data["quoteSummary"]["result"][0]["esgScores"])) {
                    if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["esgScores"]["environmentScore"])) {
                        $esg["environment"] = $data["quoteSummary"]["result"][0]["esgScores"]["environmentScore"]["raw"];
                    }
                }

                if (array_key_exists("socialScore", $data["quoteSummary"]["result"][0]["esgScores"])) {
                    if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["esgScores"]["socialScore"])) {
                        $esg["social"] = $data["quoteSummary"]["result"][0]["esgScores"]["socialScore"]["raw"];
                    }
                }

                if (array_key_exists("governanceScore", $data["quoteSummary"]["result"][0]["esgScores"])) {
                    if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["esgScores"]["governanceScore"])) {
                        $esg["governance"] = $data["quoteSummary"]["result"][0]["esgScores"]["governanceScore"]["raw"];
                    }
                }

                if (array_key_exists("ratingYear", $data["quoteSummary"]["result"][0]["esgScores"])) {
                    $esg["year"] = $data["quoteSummary"]["result"][0]["esgScores"]["ratingYear"];
                }

                if (array_key_exists("esgPerformance", $data["quoteSummary"]["result"][0]["esgScores"])) {
                    $esg["performance"] = $data["quoteSummary"]["result"][0]["esgScores"]["esgPerformance"];
                }

                if (array_key_exists("peerEsgScorePerformance", $data["quoteSummary"]["result"][0]["esgScores"])) {
                    if (array_key_exists("avg", $data["quoteSummary"]["result"][0]["esgScores"]["peerEsgScorePerformance"])) {
                        $esg["peer_total"] = $data["quoteSummary"]["result"][0]["esgScores"]["peerEsgScorePerformance"]["avg"];
                    }
                }

                if (array_key_exists("peerEnvironmentPerformance", $data["quoteSummary"]["result"][0]["esgScores"])) {
                    if (array_key_exists("avg", $data["quoteSummary"]["result"][0]["esgScores"]["peerEnvironmentPerformance"])) {
                        $esg["peer_environment"] = $data["quoteSummary"]["result"][0]["esgScores"]["peerEnvironmentPerformance"]["avg"];
                    }
                }

                if (array_key_exists("peerSocialPerformance", $data["quoteSummary"]["result"][0]["esgScores"])) {
                    if (array_key_exists("avg", $data["quoteSummary"]["result"][0]["esgScores"]["peerSocialPerformance"])) {
                        $esg["peer_social"] = $data["quoteSummary"]["result"][0]["esgScores"]["peerSocialPerformance"]["avg"];
                    }
                }

                if (array_key_exists("peerGovernancePerformance", $data["quoteSummary"]["result"][0]["esgScores"])) {
                    if (array_key_exists("avg", $data["quoteSummary"]["result"][0]["esgScores"]["peerGovernancePerformance"])) {
                        $esg["peer_governance"] = $data["quoteSummary"]["result"][0]["esgScores"]["peerGovernancePerformance"]["avg"];
                    }
                }
            }
        }

        return $esg;
    }

    /**
     * Get income from API.
     *
     * @param string $symbol
     * @return array
     */
    private function getIncome(string $symbol): array
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

        $url = $this->finance_url . $symbol . "&modules=incomeStatementHistory";
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("incomeStatementHistory", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("incomeStatementHistory", $data["quoteSummary"]["result"][0]["incomeStatementHistory"])) {
                    if (array_key_exists("endDate", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["endDate"])) {
                            $income["date"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["endDate"]["raw"];
                        }
                    }

                    if (array_key_exists("totalRevenue", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["totalRevenue"])) {
                            $income["total_revenue"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["totalRevenue"]["raw"];
                        }
                    }

                    if (array_key_exists("costOfRevenue", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["costOfRevenue"])) {
                            $income["cost_of_revenue"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["costOfRevenue"]["raw"];
                        }
                    }

                    if (array_key_exists("grossProfit", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["grossProfit"])) {
                            $income["gross_profit"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["grossProfit"]["raw"];
                        }
                    }

                    if (array_key_exists("researchDevelopment", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["researchDevelopment"])) {
                            $income["research_and_development"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["researchDevelopment"]["raw"];
                        }
                    }

                    if (array_key_exists("sellingGeneralAdministrative", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["sellingGeneralAdministrative"])) {
                            $income["selling_general_administrative"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["sellingGeneralAdministrative"]["raw"];
                        }
                    }

                    if (array_key_exists("ebit", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["ebit"])) {
                            $income["ebit"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["ebit"]["raw"];
                        }
                    }

                    if (array_key_exists("interestExpense", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["interestExpense"])) {
                            $income["interest_expense"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["interestExpense"]["raw"];
                        }
                    }

                    if (array_key_exists("incomeBeforeTax", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["incomeBeforeTax"])) {
                            $income["income_before_tax"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["incomeBeforeTax"]["raw"];
                        }
                    }

                    if (array_key_exists("incomeTaxExpense", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["incomeTaxExpense"])) {
                            $income["income_tax_expense"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["incomeTaxExpense"]["raw"];
                        }
                    }

                    if (array_key_exists("netIncome", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["netIncome"])) {
                            $income["net_income"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["netIncome"]["raw"];
                        }
                    }
                }
            }
        }

        $income["total_operating_expanses"] = $income["research_and_development"] + $income["selling_general_administrative"];

        return $income;
    }

    /**
     * Get cashflow from API.
     *
     * @param string $symbol
     * @return array
     */
    private function getCashflow(string $symbol): array
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

        $url = $this->finance_url . $symbol . "&modules=cashflowStatementHistory";
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("cashflowStatementHistory", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("cashflowStatements", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"])) {
                    if (array_key_exists("endDate", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["endDate"])) {
                            $cashflow["date"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["endDate"]["raw"]; //@todo proof if this is correct
                        }
                    }

                    if (array_key_exists("netIncome", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["netIncome"])) {
                            $cashflow["net_income"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["netIncome"]["raw"];
                        }
                    }

                    if (array_key_exists("depreciation", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["depreciation"])) {
                            $cashflow["depreciation"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["depreciation"]["raw"];
                        }
                    }

                    if (array_key_exists("changeToNetincome", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeToNetincome"])) {
                            $cashflow["change_to_net_income"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeToNetincome"]["raw"];
                        }
                    }

                    if (array_key_exists("changeToAccountReceivables", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeToAccountReceivables"])) {
                            $cashflow["change_to_account_receivables"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeToAccountReceivables"]["raw"];
                        }
                    }

                    if (array_key_exists("changeToLiabilities", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeToLiabilities"])) {
                            $cashflow["change_to_liabilities"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeToLiabilities"]["raw"];
                        }
                    }

                    if (array_key_exists("changeToInventory", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeToInventory"])) {
                            $cashflow["change_to_inventory"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeToInventory"]["raw"];
                        }
                    }

                    if (array_key_exists("changeToOperatingActivities", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeToOperatingActivities"])) {
                            $cashflow["change_to_operating_activities"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeToOperatingActivities"]["raw"];
                        }
                    }

                    if (array_key_exists("capitalExpenditures", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["capitalExpenditures"])) {
                            $cashflow["capital_expenditures"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["capitalExpenditures"]["raw"];
                        }
                    }

                    if (array_key_exists("investments", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["investments"])) {
                            $cashflow["investments"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["investments"]["raw"];
                        }
                    }

                    if (array_key_exists("dividendsPaid", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["dividendsPaid"])) {
                            $cashflow["dividends_paid"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["dividendsPaid"]["raw"];
                        }
                    }

                    if (array_key_exists("netBorrowings", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["netBorrowings"])) {
                            $cashflow["net_borrowings"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["netBorrowings"]["raw"];
                        }
                    }

                    if (array_key_exists("changeInCash", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeInCash"])) {
                            $cashflow["change_in_cash"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["changeInCash"]["raw"];
                        }
                    }

                    if (array_key_exists("repurchaseOfStock", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["repurchaseOfStock"])) {
                            $cashflow["repurchase_of_stock"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["repurchaseOfStock"]["raw"];
                        }
                    }

                    if (array_key_exists("totalCashFromOperatingActivities", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["totalCashFromOperatingActivities"])) {
                            $cashflow["total_cash_from_operating_activities"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["totalCashFromOperatingActivities"]["raw"];
                        }
                    }

                    if (array_key_exists("totalCashflowsFromInvestingActivities", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["totalCashflowsFromInvestingActivities"])) {
                            $cashflow["total_cashflows_from_investing_activities"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["totalCashflowsFromInvestingActivities"]["raw"];
                        }
                    }

                    if (array_key_exists("totalCashFromFinancingActivities", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["totalCashFromFinancingActivities"])) {
                            $cashflow["total_cash_from_financing_activities"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistory"]["cashflowStatements"][0]["totalCashFromFinancingActivities"]["raw"];
                        }
                    }
                }
            }
        }

        return $cashflow;
    }

    /**
     * Get balance sheet from API.
     * @param string $symbol
     * @return array
     */
    private function getBalanceSheet(string $symbol): array
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

        $url = $this->finance_url . $symbol . "&modules=balanceSheetHistory";
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("balanceSheetHistory", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("balanceSheetStatements", $data["quoteSummary"]["result"][0]["balanceSheetHistory"])) {
                    if (array_key_exists("endDate", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["endDate"])) {
                            $balance_sheet["date"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["endDate"]["raw"];
                        }
                    }

                    if (array_key_exists("cash", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["cash"])) {
                            $balance_sheet["cash"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["cash"]["raw"];
                        }
                    }

                    if (array_key_exists("shortTermInvestments", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["shortTermInvestments"])) {
                            $balance_sheet["short_term_investments"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["shortTermInvestments"]["raw"];
                        }
                    }

                    if (array_key_exists("netReceivables", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["netReceivables"])) {
                            $balance_sheet["net_receivables"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["netReceivables"]["raw"];
                        }
                    }

                    if (array_key_exists("inventory", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["inventory"])) {
                            $balance_sheet["inventory"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["inventory"]["raw"];
                        }
                    }

                    if (array_key_exists("otherCurrentAssets", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["otherCurrentAssets"])) {
                            $balance_sheet["other_current_assets"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["otherCurrentAssets"]["raw"];
                        }
                    }

                    if (array_key_exists("totalCurrentAssets", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["totalCurrentAssets"])) {
                            $balance_sheet["total_current_assets"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["totalCurrentAssets"]["raw"];
                        }
                    }

                    if (array_key_exists("longTermInvestments", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["longTermInvestments"])) {
                            $balance_sheet["long_term_investments"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["longTermInvestments"]["raw"];
                        }
                    }

                    if (array_key_exists("propertyPlantEquipment", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["propertyPlantEquipment"])) {
                            $balance_sheet["property_plant_equipment"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["propertyPlantEquipment"]["raw"];
                        }
                    }

                    if (array_key_exists("otherAssets", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["otherAssets"])) {
                            $balance_sheet["other_assets"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["otherAssets"]["raw"];
                        }
                    }

                    if (array_key_exists("totalAssets", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["totalAssets"])) {
                            $balance_sheet["total_assets"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["totalAssets"]["raw"];
                        }
                    }

                    if (array_key_exists("accountsPayable", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["accountsPayable"])) {
                            $balance_sheet["accounts_payable"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["accountsPayable"]["raw"];
                        }
                    }

                    if (array_key_exists("shortLongTermDebt", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["shortLongTermDebt"])) {
                            $balance_sheet["short_long_term_debt"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["shortLongTermDebt"]["raw"];
                        }
                    }

                    if (array_key_exists("otherCurrentLiab", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["otherCurrentLiab"])) {
                            $balance_sheet["other_current_liab"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["otherCurrentLiab"]["raw"];
                        }
                    }

                    if (array_key_exists("longTermDebt", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["longTermDebt"])) {
                            $balance_sheet["long_term_debt"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["longTermDebt"]["raw"];
                        }
                    }

                    if (array_key_exists("otherLiab", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["otherLiab"])) {
                            $balance_sheet["other_liab"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["otherLiab"]["raw"];
                        }
                    }

                    if (array_key_exists("totalCurrentLiabilities", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["totalCurrentLiabilities"])) {
                            $balance_sheet["total_current_liabilities"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["totalCurrentLiabilities"]["raw"];
                        }
                    }

                    if (array_key_exists("totalLiab", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["totalLiab"])) {
                            $balance_sheet["total_liab"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["totalLiab"]["raw"];
                        }
                    }

                    if (array_key_exists("commonStock", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["commonStock"])) {
                            $balance_sheet["common_stock"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["commonStock"]["raw"];
                        }
                    }

                    if (array_key_exists("retainedEarnings", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["retainedEarnings"])) {
                            $balance_sheet["retained_earnings"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["retainedEarnings"]["raw"];
                        }
                    }

                    if (array_key_exists("treasuryStock", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["treasuryStock"])) {
                            $balance_sheet["treasury_stock"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["treasuryStock"]["raw"];
                        }
                    }

                    if (array_key_exists("otherStockholderEquity", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["otherStockholderEquity"])) {
                            $balance_sheet["other_stockholder_equity"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["otherStockholderEquity"]["raw"];
                        }
                    }

                    if (array_key_exists("totalStockholderEquity", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["totalStockholderEquity"])) {
                            $balance_sheet["total_stockholder_equity"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["totalStockholderEquity"]["raw"];
                        }
                    }

                    if (array_key_exists("netTangibleAssets", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["netTangibleAssets"])) {
                            $balance_sheet["net_tangible_assets"] = $data["quoteSummary"]["result"][0]["balanceSheetHistory"]["balanceSheetStatements"][0]["netTangibleAssets"]["raw"];
                        }
                    }
                }
            }
        }

        $balance_sheet["cash_total"] = $balance_sheet["cash"] + $balance_sheet["short_term_investments"];
        $balance_sheet["long_term_liab"] = $balance_sheet["total_liab"] - $balance_sheet["total_current_liabilities"];
        $balance_sheet["total_non_current_assets"] = $balance_sheet["total_assets"] - $balance_sheet["total_current_assets"];

        return $balance_sheet;
    }

    /**
     * Get recommendations from API.
     *
     * @param string $symbol
     * @return array
     */
    private function getRecommendations(string $symbol): array
    {
        $recommendations = [
            "strong_buy" => null,
            "buy" => null,
            "hold" => null,
            "sell" => null,
            "strong_sell" => null
        ];

        $url = $this->finance_url . $symbol . "&modules=recommendationTrend";
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("recommendationTrend", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("trend", $data["quoteSummary"]["result"][0]["recommendationTrend"])) {
                    if (array_key_exists(0, $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"])) {
                        if (array_key_exists("strongBuy", $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"][0])) {
                            $recommendations["strong_buy"] = $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"][0]["strongBuy"];
                        }

                        if (array_key_exists("buy", $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"][0])) {
                            $recommendations["buy"] = $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"][0]["buy"];
                        }

                        if (array_key_exists("hold", $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"][0])) {
                            $recommendations["hold"] = $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"][0]["hold"];
                        }

                        if (array_key_exists("sell", $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"][0])) {
                            $recommendations["sell"] = $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"][0]["sell"];
                        }

                        if (array_key_exists("strongSell", $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"][0])) {
                            $recommendations["strong_sell"] = $data["quoteSummary"]["result"][0]["recommendationTrend"]["trend"][0]["strongSell"];
                        }
                    }
                }
            }
        }

        return $recommendations;
    }

    private function getUpgradeDowngrade(string $symbol): array
    {
        $upgrade_downgrade = [];

        $url = $this->finance_url . $symbol . "&modules=upgradeDowngradeHistory";
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("upgradeDowngradeHistory", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("history", $data["quoteSummary"]["result"][0]["upgradeDowngradeHistory"])) {
                    $index = 0;

                    foreach ($data["quoteSummary"]["result"][0]["upgradeDowngradeHistory"]["history"] as $set) {
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
                }
            }
        }

        return $upgrade_downgrade;
    }
}
