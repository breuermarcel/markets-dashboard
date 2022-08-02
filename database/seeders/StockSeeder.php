<?php

namespace Breuermarcel\FinanceDashboard\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testarray = [
            ["AAPL","865985","US0378331005","Apple Inc."],
            ["NFLX","552484","US64110L1061", "Netflix Inc."]
        ];

        DB::table("bm_stocks")->insert([
            "symbol" => "AAPL",
            "wkn" => "865985",
            "isin" => "US0378331005",
            "name" => "Apple Inc."
        ]);

        foreach ($testarray as $stock) {
            DB::table("bm_stocks")->insert([
                "symbol" => $stock[0],
                "wkn" => $stock[1],
                "isin" => $stock[2],
                "name" => $stock[3]
            ]);
        }

        /**
         * Seed stocks from csv.
         */
        /*
        $csv = fopen("./data/stocks.csv", "r");
        $firstline = true;

        while (($data = fgetcsv($csv)) !== false) {
            if (!$firstline) {
                DB::table("bm_stocks")->insert([
                    "symbol" => $data[0],
                    "wkn" => $data[1],
                    "isin" => $data[2],
                    "name" => $data[3]
                ]);
            }

            $firstline = false;
        }

        fclose($csv);
        */
    }
}
