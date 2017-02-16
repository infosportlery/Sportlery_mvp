<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLetslistLocationCatas extends Migration
{
    public function up()
    {
        Schema::table('sportlery_letslist_location_catas', function($table)
        {
            $table->dropPrimary(['location_id','catas_id']);
            $table->renameColumn('catas_id', 'cata_id');
            $table->primary(['location_id','cata_id']);
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_letslist_location_catas', function($table)
        {
            $table->dropPrimary(['location_id','cata_id']);
            $table->renameColumn('cata_id', 'catas_id');
            $table->primary(['location_id','catas_id']);
        });
    }
}
