<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('region');
            $table->integer('total_POP_asset')->nullable()->default(NULL);
            $table->integer('total_PM_POP')->nullable()->default(NULL);
            $table->decimal('ratio_total',10,4)->nullable()->default(NULL);
            $table->integer('asset_POP_D')->nullable()->default(NULL);
            $table->integer('PM_POP_D')->nullable()->default(NULL);
            $table->decimal('ratio_POP_D',10,4)->nullable()->default(NULL);
            $table->integer('asset_POP_B')->nullable()->default(NULL);
            $table->integer('PM_POP_B')->nullable()->default(NULL);
            $table->decimal('ratio_POP_B',10,4)->nullable()->default(NULL);
            $table->integer('asset_POP_SB')->nullable()->default(NULL);
            $table->integer('PM_POP_SB')->nullable()->default(NULL);
            $table->decimal('ratio_POP_SB',10,4)->nullable()->default(NULL);
            $table->integer('PM_FOC')->nullable()->default(NULL);
            $table->integer('PM_lain')->nullable()->default(NULL);
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
        Schema::dropIfExists('reports');
    }
}
