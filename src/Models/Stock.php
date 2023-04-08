<?php

namespace Breuermarcel\MarketsDashboard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = "bm_stocks";

    protected $primaryKey = "symbol";

    protected $keyType = "string";

    public $incrementing = false;

    public $timestamps = false;

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
