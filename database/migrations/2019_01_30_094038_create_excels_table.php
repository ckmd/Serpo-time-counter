<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ar_id');
            $table->string('prob_id');
            $table->string('kode_wo');
            $table->string('region');
            $table->string('basecamp');
            $table->string('serpo');
            $table->integer('durasi_sbu');
            $table->integer('prep_time');
            $table->integer('travel_time');
            $table->integer('work_time');
            $table->integer('complete_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('excels');
    }
}
