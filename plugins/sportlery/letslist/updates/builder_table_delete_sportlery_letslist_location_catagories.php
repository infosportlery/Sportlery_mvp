<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteSportleryLetslistLocationCatagories extends Migration
{
    public function up()
    {
        Schema::dropIfExists('sportlery_letslist_location_catagories');
    }
    
    public function down()
    {
        Schema::create('sportlery_letslist_location_catagories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('location_id');
            $table->integer('catagory_id');
        });
    }
}
