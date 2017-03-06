<?php namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLibraryUserSports2 extends Migration
{
    public function up()
    {
        Schema::table('sportlery_library_user_sports', function($table)
        {
            $table->dropPrimary(['user_id','sports_id']);
            $table->renameColumn('sports_id', 'sport_id');
            $table->primary(['user_id','sport_id']);
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_library_user_sports', function($table)
        {
            $table->dropPrimary(['user_id','sport_id']);
            $table->renameColumn('sport_id', 'sports_id');
            $table->primary(['user_id','sports_id']);
        });
    }
}
