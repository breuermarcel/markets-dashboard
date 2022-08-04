<?php

namespace Breuermarcel\FinanceDashboard\Http\Helpers;

use Carbon\Carbon;

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

        $from_date = Carbon::now()->subDays($period)->format('U');
        $interval = "1d";

        $url = self::$chart_url . $symbol . "&period1=" . $from_date . "&period2=9999999999&interval=" . $interval;
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($data["chart"]["error"] === null) {
            if (array_key_exists('adjclose', $data['chart']['result'][0]['indicators']['adjclose'][0])) {
                foreach ($data['chart']['result'][0]['timestamp'] as $d_key => $date) {
                    foreach ($data['chart']['result'][0]['indicators']['adjclose'][0]['adjclose'] as $v_key => $value) {
                        if (($value !== null) && $d_key === $v_key) {
                            $chart[] = [
                                'adj_close' => $value,
                                'date' => Carbon::parse($date)->format('d.m.Y'),
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

        return $finance;
    }

    public static function getAssetProfile(string $symbol): array
    {
        $asset = [];

        return $asset;
    }
}
