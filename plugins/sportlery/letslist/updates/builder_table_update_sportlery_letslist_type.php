<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLetslistType extends Migration
{
    public function up()
    {
        Schema::table('sportlery_letslist_type', function($table)
        {
            $table->renameColumn('name', 'name_type');
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_letslist_type', function($table)
        {
            $table->renameColumn('name_type', 'name');
        });
    }
}
