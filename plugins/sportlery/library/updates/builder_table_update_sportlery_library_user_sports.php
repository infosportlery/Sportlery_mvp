<?php namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLibraryUserSports extends Migration
{
    public function up()
    {
        Schema::table('sportlery_library_user_sports', function($table)
        {
            $table->boolean('favorite');
            $table->dropColumn('interest_level');
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_library_user_sports', function($table)
        {
            $table->dropColumn('favorite');
            $table->smallInteger('interest_level');
        });
    }
}
