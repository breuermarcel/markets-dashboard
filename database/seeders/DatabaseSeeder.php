<?php

namespace Breuermarcel\FinanceDashboard\Database\Seeder;

use Illuminate\Database\Seeder;
use Breuermarcel\FinanceDashboard\Models\Stock;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Seed stocks from csv.
         */
        $csv = fopen(base_path("database/seeders/data/stocks.csv"), "r");
        $firstline = true;

        while(($data = fgetcsv($csv)) !== false) {
            if (!$firstline) {
                Stock::create([
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
