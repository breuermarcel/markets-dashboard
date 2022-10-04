<?php

namespace Breuermarcel\FinanceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "bm_informations";

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
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
