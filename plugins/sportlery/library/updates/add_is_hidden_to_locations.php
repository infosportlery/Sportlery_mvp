<?php

namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddIsHiddenToLocations extends Migration
{
    public function up()
    {
        Schema::table('spr_locations', function (Blueprint $table) {
            $table->boolean('is_hidden')->after('is_public')->default(0)->index();
        });
    }

    public function down()
    {
        Schema::table('spr_locations', function (Blueprint $table) {
            $table->dropIndex(['is_hidden']);
            $table->dropColumn('is_hidden');
        });
    }
}
