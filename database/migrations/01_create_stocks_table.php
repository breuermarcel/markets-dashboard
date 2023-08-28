<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bm_stocks', function (Blueprint $table) {
            $table->string('symbol')->primary();
            $table->string('wkn')->unique()->nullable();
            $table->string('isin')->unique()->nullable();
            $table->string('name');
        });
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bm_stocks');
    }
};
