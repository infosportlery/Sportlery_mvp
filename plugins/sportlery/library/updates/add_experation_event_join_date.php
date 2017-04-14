<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class AddExperationEventJoinDate extends Migration
{
    public function up()
    {
        Schema::table('spr_events', function(Blueprint $table) {
            $table->dateTime('booking_ends_at');
        });
    }

    public function down()
    {
        Schema::table('spr_events', function(Blueprint $table) {
            $table->dropColumn('booking_ends_at');
        });
    }
}
