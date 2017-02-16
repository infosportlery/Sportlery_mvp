<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLetslistType2 extends Migration
{
    public function up()
    {
        Schema::table('sportlery_letslist_type', function($table)
        {
            $table->string('slug')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_letslist_type', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}
