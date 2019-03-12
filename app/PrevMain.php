<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrevMain extends Model
{
    protected $fillable = ['status','scheduled_date','duration','wo_code','description','wo_date','asset_code','material_code','classification','child_asset','address','region','basecamp','serpo','company','type'];
}
