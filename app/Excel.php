<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Excel extends Model
{
    protected $fillable = ['ar_id','prob_id','kode_wo','region','basecamp','serpo','wo_date','wo_complete','durasi_sbu','prep_time','travel_time','work_time','rsps','total_durasi','category','root_cause','kendala','terminasi_pop','root_cause_description','kendala_description'];
}
