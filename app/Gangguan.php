<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gangguan extends Model
{
    protected $fillable = ['kategori_gangguan', 'parameter'];
}
