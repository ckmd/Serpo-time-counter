<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NationalData extends Model
{
    protected $fillable = ['region','total_durasi','jumlah_wo','durasi_sbu','prep_time','travel_time','work_time','rsps'];
}
