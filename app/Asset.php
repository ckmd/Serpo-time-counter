<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = ['site_id','site','kota','propinsi','sbu','model','type','updated_time','updated_by','status'];
}
