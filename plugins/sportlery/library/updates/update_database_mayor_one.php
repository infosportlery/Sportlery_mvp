<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateDatabaseMayorOne extends Migration
{
    public function up()
    {
        Schema::table('sportlery_library_user_sports', function(Blueprint $table) {
            
            $table->tinyInteger('level');
        });

        Schema::table('spr_locations', function(Blueprint $table) {
            $table->string('kvk_number')->nullable();
            $table->string('btw_number')->nullable();
            $table->string('iban_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('sportlery_library_user_sports', function(Blueprint $table) {
            $table->dropColumn('level');
        });

        Schema::table('spt_locations', function(Blueprint $table) {
            $table->dropColumn('kvk_number');
            $table->dropColumn('btw_number');
            $table->dropColumn('iban_id');
        });
    }
}
