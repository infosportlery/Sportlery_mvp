<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSportleryLetslistLocationCatas extends Migration
{
    public function up()
    {
        Schema::create('sportlery_letslist_location_catas', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('location_id');
            $table->integer('catas_id');
            $table->primary(['location_id','catas_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sportlery_letslist_location_catas');
    }
}
