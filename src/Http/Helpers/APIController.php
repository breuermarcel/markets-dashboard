<?php

namespace Breuermarcel\FinanceDashboard\Http\Helpers;

use Carbon\Carbon;
use PhpParser\Node\Expr\NullsafeMethodCall;

class APIController
{
    /**
     * Yahoo finance API-url
     *
     * @var string
     */
    private static string $chart_url = "https://query2.finance.yahoo.com/v8/finance/chart/?symbol=";

    /**
     * Yahoo finance API-url
     *
     * @var string
     */
    private static string $finance_url = "https://query2.finance.yahoo.com/v10/finance/quoteSummary/?symbol=";

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

    public static function getFinance(string $symbol): array
    {
        $finance = [];

        $finance["asset_profile"] = self::getAssetProfile($symbol);

        return $finance;
    }

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
                            if (array_key_exists("raw", $officer)) {
                                $asset["company_officiers"][$index]["total_pay"] = $officer["totalPay"]["raw"];
                            }
                        }
                    }
                }
            }
        }

        dd($asset);

        return $asset;
    }
}
