<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class ChangeLocationsEmail extends Migration
{
    public function up()
    {
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->dropColumn('email');
            $table->string('email')->nullable();
        });
    }

    public function down()
    {

    }
}
