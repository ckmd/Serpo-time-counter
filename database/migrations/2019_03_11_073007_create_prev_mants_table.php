<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrevMantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prev_mants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->datetime('scheduled_date');
            $table->string('duration');
            $table->bigInteger('wo_code');
            $table->longtext('description');
            $table->datetime('wo_date');
            $table->string('asset_code');
            $table->string('material_code');
            $table->string('classification');
            $table->string('child_asset');
            $table->string('address');
            $table->string('region');
            $table->string('basecamp');
            $table->string('serpo');
            $table->string('company');
            $table->string('type')->nullable()->default(NULL);
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
        Schema::dropIfExists('prev_mants');
    }
}
