<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvgExcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avg_excels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('basecamp');
            $table->string('serpo');
            $table->decimal('durasi_sbu',10,2)->nullable()->default(NULL);
            $table->decimal('prep_time',10,2)->nullable()->default(NULL);
            $table->decimal('travel_time',10,2)->nullable()->default(NULL);
            $table->decimal('work_time',10,2)->nullable()->default(NULL);
            $table->decimal('complete_time',10,2)->nullable()->default(NULL);
            $table->decimal('rsps', 3, 2);
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
        Schema::dropIfExists('avg_excels');
    }
}
