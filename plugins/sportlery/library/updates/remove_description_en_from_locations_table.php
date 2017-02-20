<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class RemoveDescriptionEnFromLocationsTable extends Migration
{
    public function up()
    {
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->dropColumn('description_en');
        });
    }

    public function down()
    {
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->text('description_en')->after('description');
        });
    }
}
