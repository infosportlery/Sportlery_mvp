<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLetslistCatagory extends Migration
{
    public function up()
    {
        Schema::table('sportlery_letslist_catagory', function($table)
        {
            $table->increments('id')->unsigned(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_letslist_catagory', function($table)
        {
            $table->increments('id')->unsigned()->change();
        });
    }
}
