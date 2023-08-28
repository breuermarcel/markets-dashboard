<?php

namespace Breuermarcel\MarketsDashboard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'bm_stocks';

    /**
     * @var string
     */
    protected $primaryKey = 'symbol';

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'symbol',
        'wkn',
        'isin',
        'name',
    ];

    public function information()
    {
        $this->hasOne(Information::class);
    }
}
