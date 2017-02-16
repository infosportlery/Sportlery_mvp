<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLetslistCatas2 extends Migration
{
    public function up()
    {
        Schema::table('sportlery_letslist_catas', function($table)
        {
            $table->increments('id')->change();
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_letslist_catas', function($table)
        {
            $table->integer('id')->change();
        });
    }
}
