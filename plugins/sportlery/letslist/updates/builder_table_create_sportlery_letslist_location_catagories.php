<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSportleryLetslistLocationCatagories extends Migration
{
    public function up()
    {
        Schema::create('sportlery_letslist_location_catagories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('catagory_id');
            $table->integer('location_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sportlery_letslist_location_catagories');
    }
}
