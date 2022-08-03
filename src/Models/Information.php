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
    //protected $table = config("finance-dashboard.tables.informations");
    protected $table = "bm_informations";

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
        //
    ];

    public function stock()
    {
        $this->belongsTo(Stock::class);
    }
}
