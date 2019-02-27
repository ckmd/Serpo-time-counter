<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Excel extends Model
{
    protected $fillable = ['ar_id','prob_id','kode_wo','region','basecamp','serpo','wo_date','durasi_sbu','prep_time','travel_time','work_time','rsps','total_durasi','root_cause','kendala','root_cause_description'];
}
