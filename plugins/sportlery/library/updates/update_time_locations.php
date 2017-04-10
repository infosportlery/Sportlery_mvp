<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateTimeLocations extends Migration
{
    public function up()
    {
        Schema::table('spr_location_times', function(Blueprint $table) {
            

            $table->dropColumn('monday_start');
            $table->dropColumn('tuesday_start');
            $table->dropColumn('wednesday_start');
            $table->dropColumn('thursday_start');
            $table->dropColumn('friday_start');
            $table->dropColumn('saturday_start');
            $table->dropColumn('sunday_start');
            $table->dropColumn('monday_end');
            $table->dropColumn('tuesday_end');
            $table->dropColumn('wednesday_end');
            $table->dropColumn('thursday_end');
            $table->dropColumn('friday_end');
            $table->dropColumn('saturday_end');
            $table->dropColumn('sunday_end');
            $table->dropColumn('monday_off');
            $table->dropColumn('tuesday_off');
            $table->dropColumn('wednesday_off');
            $table->dropColumn('thursday_off');
            $table->dropColumn('friday_off');
            $table->dropColumn('saturday_off');
            $table->dropColumn('sunday_off');


            $table->time('monday_start')->nullable();
            $table->time('tuesday_start')->nullable();
            $table->time('wednesday_start')->nullable();
            $table->time('thursday_start')->nullable();
            $table->time('friday_start')->nullable();
            $table->time('saturday_start')->nullable();
            $table->time('sunday_start')->nullable();
            $table->time('monday_end')->nullable();
            $table->time('tuesday_end')->nullable();
            $table->time('wednesday_end')->nullable();
            $table->time('thursday_end')->nullable();
            $table->time('friday_end')->nullable();
            $table->time('saturday_end')->nullable();
            $table->time('sunday_end')->nullable();

        });
    }

    public function down()
    {
        Schema::table('spr_location_times', function(Blueprint $table) {
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


            $table->dropColumn('monday_start');
            $table->dropColumn('tuesday_start');
            $table->dropColumn('wednesday_start');
            $table->dropColumn('thursday_start');
            $table->dropColumn('friday_start');
            $table->dropColumn('saturday_start');
            $table->dropColumn('sunday_start');
            $table->dropColumn('monday_end');
            $table->dropColumn('tuesday_end');
            $table->dropColumn('wednesday_end');
            $table->dropColumn('thursday_end');
            $table->dropColumn('friday_end'));
            $table->dropColumn('saturday_end');
            $table->dropColumn('sunday_end');
        });
    }
}