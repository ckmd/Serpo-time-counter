<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class avgExcel extends Model
{
    protected $fillable = ['basecamp','serpo','jumlah_wo','durasi_sbu','prep_time','travel_time','work_time','complete_time','rsps'];
}
