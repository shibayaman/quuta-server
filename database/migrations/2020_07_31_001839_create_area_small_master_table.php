<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaSmallMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_small_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('areacode_s')->unique();
            $table->string('areaname_s');
            $table->string('areacode_m');
            $table->string('areaname_m');
            $table->string('areacode_l');
            $table->string('areaname_l');
            $table->string('pref_code');
            $table->string('pref_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('area_small_master');
    }
}
