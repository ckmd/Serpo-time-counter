<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvgExcel extends Model
{
    protected $fillable = ['basecamp','serpo','jumlah_wo','total_durasi','durasi_sbu','prep_time','travel_time','work_time','rsps'];
}
