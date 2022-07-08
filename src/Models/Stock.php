<?php

namespace Breuermarcel\FinanceDashboard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * Deklare tablename
     *
     * @var string
     */
    protected $table = "bm_stocks";

    /**
     * Set timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        "symbol",
        "wkn",
        "isin",
        "name"
    ];
}
