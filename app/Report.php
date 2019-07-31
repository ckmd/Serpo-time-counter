<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'region', 
        'total_POP_asset',
        'total_PM_POP',
        'ratio_total',
        'asset_POP_D',
        'PM_POP_D',
        'ratio_POP_D',
        'asset_POP_B',
        'PM_POP_B',
        'ratio_POP_B',
        'asset_POP_SB',
        'PM_POP_SB',
        'ratio_POP_SB',
        'PM_FOC',
        'PM_lain'
    ];
}
