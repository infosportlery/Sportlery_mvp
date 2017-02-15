<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLetslistLocation3 extends Migration
{
    public function up()
    {
        Schema::table('sportlery_letslist_location', function($table)
        {
            $table->string('slug')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_letslist_location', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}
