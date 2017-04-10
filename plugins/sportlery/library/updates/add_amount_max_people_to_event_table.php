<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class AddAmountMaxPeopleToEvent extends Migration
{
    public function up()
    {
        Schema::table('spr_events', function(Blueprint $table) {
            $table->integer('max_attendees');
            $table->integer('current_attendees');
        });
    }

    public function down()
    {
        Schema::table('spr_events', function(Blueprint $table) {
            $table->dropColumn('max_attendees', 'current_attendees');
        });
    }
}
