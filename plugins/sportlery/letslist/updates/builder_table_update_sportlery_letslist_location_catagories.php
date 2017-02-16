<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLetslistLocationCatagories extends Migration
{
    public function up()
    {
        Schema::table('sportlery_letslist_location_catagories', function($table)
        {
            $table->integer('loc_id');
            $table->integer('cat_id');
            $table->dropColumn('catagory_id');
            $table->dropColumn('location_id');
            $table->primary(['loc_id','cat_id']);
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_letslist_location_catagories', function($table)
        {
            $table->dropPrimary(['loc_id','cat_id']);
            $table->dropColumn('loc_id');
            $table->dropColumn('cat_id');
            $table->integer('catagory_id');
            $table->integer('location_id');
        });
    }
}
