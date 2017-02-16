<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLetslistCatas extends Migration
{
    public function up()
    {
        Schema::table('sportlery_letslist_catas', function($table)
        {
            $table->string('slug');
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_letslist_catas', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}
