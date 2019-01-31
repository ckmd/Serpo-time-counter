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
            $table->bigInteger('ar_id');
            $table->bigInteger('prob_id');
            $table->bigInteger('kode_wo');
            $table->string('region');
            $table->string('basecamp');
            $table->string('serpo');
            $table->integer('durasi_sbu')->nullable()->default(NULL);
            $table->integer('prep_time')->nullable()->default(NULL);
            $table->integer('travel_time')->nullable()->default(NULL);
            $table->integer('work_time')->nullable()->default(NULL);
            $table->integer('complete_time')->nullable()->default(NULL);
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
