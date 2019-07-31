<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kendala extends Model
{
    protected $fillable = ['kategori_kendala', 'parameter'];
}
