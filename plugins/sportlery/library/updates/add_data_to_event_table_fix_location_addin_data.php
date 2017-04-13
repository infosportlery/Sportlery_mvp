<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class AddDataToEventTableFixLocationAddinData extends Migration
{
    public function up()
    {
        Schema::table('spr_events', function(Blueprint $table) {
            $table->string('kvk_number')->nullable();
            $table->string('btw_number')->nullable();
            $table->string('iban_id')->nullable();
        });

        Schema::table('spr_locations', function(Blueprint $table) {
            $table->dropColumn('kvk_number');
            $table->dropColumn('btw_number');
            $table->dropColumn('iban_id');

            $table->string('kvk_number')->nullable();
            $table->string('btw_number')->nullable();
            $table->string('iban_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('spr_events', function(Blueprint $table) {
            $table->dropColumn('kvk_number');
            $table->dropColumn('btw_number');
            $table->dropColumn('iban_id');

        });
    }
}