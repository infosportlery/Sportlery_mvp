<?php namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLibraryUserSports3 extends Migration
{
    public function up()
    {
        Schema::table('sportlery_library_user_sports', function($table)
        {
            $table->boolean('favorite')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_library_user_sports', function($table)
        {
            $table->boolean('favorite')->nullable(false)->change();
        });
    }
}
