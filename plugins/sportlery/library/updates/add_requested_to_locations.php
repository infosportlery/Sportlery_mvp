<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class AddRequestedToLocations extends Migration
{
    public function up()
    {
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->tinyInteger('requested')->default(2);
        });
    }

    public function down()
    {
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->dropColumn('requested');
        });
    }
}
