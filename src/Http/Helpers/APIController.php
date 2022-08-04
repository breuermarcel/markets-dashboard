<?php

namespace Breuermarcel\FinanceDashboard\Http\Helpers;

use Carbon\Carbon;

class APIController
{
    private static string $chart_url = "https://query2.finance.yahoo.com/v8/finance/chart/?symbol=";

    /**
     * Get financial chart from API.
     *
     * @param $symbol
     * @param integer $period
     * @return array
     */
    public static function getChart($symbol, $period) : array
    {
        $chart = [];

        $from_date = Carbon::now()->subDays($period)->format('U');
        $interval = "1d";

        $url = self::$chart_url . $symbol . "&period1=" . $from_date . "&period2=9999999999&interval=".$interval;
        $response = file_get_contents($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if (array_key_exists('adjclose', $data['chart']['result']['0']['indicators']['adjclose'][0])) {
            foreach ($data['chart']['result'][0]['timestamp'] as $d_key => $date) {
                foreach ($data['chart']['result'][0]['indicators']['adjclose'][0]['adjclose'] as $v_key => $value) {
                    if (($value !== null) && $d_key === $v_key) {
                        $chart[$date] = [
                            'adj_close' => $value,
                            'date' => Carbon::parse($date)->format('d.m.Y')
                        ];
                    }
                }
            }
        }

        return $chart;
    }
}
