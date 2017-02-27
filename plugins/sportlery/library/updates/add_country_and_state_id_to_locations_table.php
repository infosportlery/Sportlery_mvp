<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class UseCountryAndStateIdInLocationsTable extends Migration
{
    public function up()
    {
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->unsignedInteger('state_id')->after('city')->nullable()->index();
            $table->unsignedInteger('country_id')->after('state_id')->nullable()->index();

            $table->foreign('country_id')->references('id')->on('rainlab_location_countries')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('state_id')->references('id')->on('rainlab_location_states')->onUpdate('cascade')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->dropIndex(['country_id']);
            $table->dropIndex(['state_id']);
            $table->dropForeign(['country_id']);
            $table->dropForeign(['state_id']);
        });
    }
}
