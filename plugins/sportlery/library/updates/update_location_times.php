<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateLocationTimes extends Migration
{
    public function up()
    {
        Schema::table('spr_location_times', function(Blueprint $table) {
            
            $table->foreign('location_id')
                  ->references('id')->on('spr_locations')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('spr_location_times', function(Blueprint $table) {
            $table->integer('location_id')->unique();
        });
    }
}