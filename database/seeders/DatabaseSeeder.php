<?php

namespace Breuermarcel\FinanceDashboard\Database\Seeder;

use Breuermarcel\FinanceDashboard\Database\Seeders\StockSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StockSeeder::class
        ]);
    }
}
