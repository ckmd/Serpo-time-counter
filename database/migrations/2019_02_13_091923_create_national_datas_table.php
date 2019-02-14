<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNationalDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('national_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('region');
            $table->integer('jumlah_wo');
            $table->decimal('durasi_sbu',10,2)->nullable()->default(NULL);
            $table->decimal('prep_time',10,2)->nullable()->default(NULL);
            $table->decimal('travel_time',10,2)->nullable()->default(NULL);
            $table->decimal('work_time',10,2)->nullable()->default(NULL);
            $table->decimal('rsps',10,2)->nullable()->default(NULL);
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
        Schema::dropIfExists('national_datas');
    }
}
