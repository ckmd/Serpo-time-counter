<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NationalData extends Model
{
    protected $fillable = ['region','jumlah_wo','durasi_sbu','prep_time','travel_time','work_time','rsps'];
}
