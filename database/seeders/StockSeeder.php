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
        /**
         * Seed stocks from csv.
         */
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
    }
}
