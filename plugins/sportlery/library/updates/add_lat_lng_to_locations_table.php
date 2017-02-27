<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class AddLatLngToLocationsTable extends Migration
{
    public function up()
    {
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->decimal('latitude', 8, 6)->after('city')->nullable()->index();
            $table->decimal('longitude', 9, 6)->after('latitude')->nullable()->index();
        });
    }

    public function down()
    {
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->dropColumn('latitude', 'longitude');
        });
    }
}
