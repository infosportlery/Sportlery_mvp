<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateLocationTimeManager extends Migration
{
    public function up()
    {
        Schema::create('spr_location_times', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->integer('location_id')->unsigned();
            $table->string('monday_start');
            $table->string('tuesday_start');
            $table->string('wednesday_start');
            $table->string('thursday_start');
            $table->string('friday_start');
            $table->string('saturday_start');
            $table->string('sunday_start');
            $table->string('monday_end');
            $table->string('tuesday_end');
            $table->string('wednesday_end');
            $table->string('thursday_end');
            $table->string('friday_end');
            $table->string('saturday_end');
            $table->string('sunday_end');
            $table->string('monday_off');
            $table->string('tuesday_off');
            $table->string('wednesday_off');
            $table->string('thursday_off');
            $table->string('friday_off');
            $table->string('saturday_off');
            $table->string('sunday_off');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('spr_location_times');
    }
}