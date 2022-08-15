<?php

namespace Breuermarcel\FinanceDashboard\Http\Helpers;

use Carbon\Carbon;

class APIController
{
    /**
     * Yahoo finance API-url.
     *
     * @var string
     */
    private static string $chart_url = "https://query2.finance.yahoo.com/v8/finance/chart/?symbol=";

    /**
     * Yahoo finance API-url.
     *
     * @var string
     */
    private static string $finance_url = "https://query2.finance.yahoo.com/v10/finance/quoteSummary/?symbol=";

    /**
     * Yahoo finance modules.
     *
     * @var array
     * @todo remove when all modules added.
     */
    private static array $modules = [
        "earnings" => "earnings",
        "balance_sheet" => "balanceSheetHistoryQuarterly",
        "recommendations" => "recommendationTrend",
        "upgrade_downgrade" => "upgradeDowngradeHistory",
        "index_trend" => "indexTrend",
        "industry_trend" => "industryTrend",
        "sector_trend" => "sectorTrend",
        "institution_ownership" => "institutionOwnership",
        "fund_ownership" => "fundOwnership",
        "insider_ownership" => "insiderHolders",
        "insider_transactions" => "insiderTransactions",
    ];

    /**
     * Get financial chart from API.
     *
     * @param string $symbol
     * @param int $period
     * @return array
     */
    public static function getChart(string $symbol, int $period): array
    {
        $chart = [];

        $from_date = Carbon::now()->subDays($period)->format("U");
        $interval = "1d";

        $url = self::$chart_url . $symbol . "&period1=" . $from_date . "&period2=9999999999&interval=" . $interval;
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
     * Call all API modules.
     *
     * @param string $symbol
     * @return array
     */
    public static function getFinance(string $symbol): array
    {
        $finance = [];

        $finance["asset_profile"] = self::getAssetProfile($symbol);
        $finance["esg_score"] = self::getEsgScore($symbol);
        $finance["income"] = self::getIncome($symbol);
        $finance["cashflow"] = self::getCashflow($symbol);

        return $finance;
    }

    /**
     * Get asset profile from API.
     *
     * @param string $symbol
     * @return array
     */
    public static function getAssetProfile(string $symbol): array
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

        $url = self::$finance_url . $symbol . "&modules=assetProfile";
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
    public static function getEsgScore(string $symbol): array
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

        $url = self::$finance_url . $symbol . "&modules=esgScores";
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
    private static function getIncome(string $symbol): array
    {
        $income = [
            "total_revenue" => null,
            "cost_of_revenue" => null,
            "gross_profit" => null,
            "research_and_development" => null,
            "selling_general_administrative" => null,
            "total_operating_expanses" => null,
            "operating_income" => null,
            "ebit" => null,
            "interest_expense" => null,
            "income_before_tax" => null,
            "income_tax_expense" => null,
            "net_income" => null,
        ];

        $url = self::$finance_url . $symbol . "&modules=incomeStatementHistory";
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("incomeStatementHistory", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("incomeStatementHistory", $data["quoteSummary"]["result"][0]["incomeStatementHistory"])) {
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

                    if (array_key_exists("totalOperatingExpenses", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["totalOperatingExpenses"])) {
                            $income["total_operating_expanses"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["totalOperatingExpenses"]["raw"];
                        }
                    }

                    if (array_key_exists("operatingIncome", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["operatingIncome"])) {
                            $income["operating_income"] = $data["quoteSummary"]["result"][0]["incomeStatementHistory"]["incomeStatementHistory"][0]["operatingIncome"]["raw"];
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

        return $income;
    }

    /**
     * Get cashflow from API.
     *
     * @param string $symbol
     * @return array
     */
    private static function getCashflow(string $symbol):array
    {
        $cashflow = [
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

        $url = self::$finance_url . $symbol . "&modules=cashflowStatementHistoryQuarterly";
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($data["quoteSummary"]["error"] === null) {
            if (array_key_exists("cashflowStatementHistoryQuarterly", $data["quoteSummary"]["result"][0])) {
                if (array_key_exists("cashflowStatements", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"])) {
                    if (array_key_exists("netIncome", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["netIncome"])) {
                            $cashflow["net_income"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["netIncome"]["raw"];
                        }
                    }

                    if (array_key_exists("depreciation", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["depreciation"])) {
                            $cashflow["depreciation"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["depreciation"]["raw"];
                        }
                    }

                    if (array_key_exists("changeToNetincome", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeToNetincome"])) {
                            $cashflow["change_to_net_income"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeToNetincome"]["raw"];
                        }
                    }

                    if (array_key_exists("changeToAccountReceivables", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeToAccountReceivables"])) {
                            $cashflow["change_to_account_receivables"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeToAccountReceivables"]["raw"];
                        }
                    }

                    if (array_key_exists("changeToLiabilities", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeToLiabilities"])) {
                            $cashflow["change_to_liabilities"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeToLiabilities"]["raw"];
                        }
                    }

                    if (array_key_exists("changeToInventory", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeToInventory"])) {
                            $cashflow["change_to_inventory"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeToInventory"]["raw"];
                        }
                    }

                    if (array_key_exists("changeToOperatingActivities", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeToOperatingActivities"])) {
                            $cashflow["change_to_operating_activities"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeToOperatingActivities"]["raw"];
                        }
                    }

                    if (array_key_exists("capitalExpenditures", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["capitalExpenditures"])) {
                            $cashflow["capital_expenditures"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["capitalExpenditures"]["raw"];
                        }
                    }

                    if (array_key_exists("investments", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["investments"])) {
                            $cashflow["investments"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["investments"]["raw"];
                        }
                    }

                    if (array_key_exists("dividendsPaid", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["dividendsPaid"])) {
                            $cashflow["dividends_paid"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["dividendsPaid"]["raw"];
                        }
                    }

                    if (array_key_exists("netBorrowings", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["netBorrowings"])) {
                            $cashflow["net_borrowings"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["netBorrowings"]["raw"];
                        }
                    }

                    if (array_key_exists("changeInCash", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeInCash"])) {
                            $cashflow["change_in_cash"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["changeInCash"]["raw"];
                        }
                    }

                    if (array_key_exists("repurchaseOfStock", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["repurchaseOfStock"])) {
                            $cashflow["repurchase_of_stock"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["repurchaseOfStock"]["raw"];
                        }
                    }

                    if (array_key_exists("totalCashFromOperatingActivities", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["totalCashFromOperatingActivities"])) {
                            $cashflow["total_cash_from_operating_activities"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["totalCashFromOperatingActivities"]["raw"];
                        }
                    }

                    if (array_key_exists("totalCashflowsFromInvestingActivities", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["totalCashflowsFromInvestingActivities"])) {
                            $cashflow["total_cashflows_from_investing_activities"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["totalCashflowsFromInvestingActivities"]["raw"];
                        }
                    }

                    if (array_key_exists("totalCashFromFinancingActivities", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0])) {
                        if (array_key_exists("raw", $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["totalCashFromFinancingActivities"])) {
                            $cashflow["total_cash_from_financing_activities"] = $data["quoteSummary"]["result"][0]["cashflowStatementHistoryQuarterly"]["cashflowStatements"][0]["totalCashFromFinancingActivities"]["raw"];
                        }
                    }
                }
            }
        }

        return $cashflow;
    }
}
