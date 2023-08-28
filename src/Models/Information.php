<?php

namespace Breuermarcel\MarketsDashboard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $table = 'bm_markets_analysis';

    protected $fillable = [
        //
    ];

    public function stock()
    {
        $this->belongsTo(Stock::class);
    }

    public function cashflow()
    {
        //
    }

    public function esg()
    {
        //
    }

    public function profile()
    {
        //
    }

    public function income()
    {
        //
    }

    public function balanceSheet()
    {
        //
    }

    public function earnings()
    {
        //
    }

    public function upgradeDowngrade()
    {
        //
    }
}
