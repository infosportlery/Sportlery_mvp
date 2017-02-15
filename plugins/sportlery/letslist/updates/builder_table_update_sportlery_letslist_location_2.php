<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLetslistLocation2 extends Migration
{
    public function up()
    {
        Schema::table('sportlery_letslist_location', function($table)
        {
            $table->dropColumn('type_id');
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_letslist_location', function($table)
        {
            $table->integer('type_id');
        });
    }
}
