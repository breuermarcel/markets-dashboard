<?php

namespace Breuermarcel\MarketsDashboard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "bm_markets_stocks";

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = "symbol";

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = "string";

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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

    public function information()
    {
        $this->hasOne(Information::class);
    }
}
