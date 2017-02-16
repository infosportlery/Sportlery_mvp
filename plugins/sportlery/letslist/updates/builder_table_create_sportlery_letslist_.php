<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSportleryLetslist extends Migration
{
    public function up()
    {
        Schema::create('sportlery_letslist_', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('location_id');
            $table->integer('type_id');
            $table->primary(['location_id','type_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sportlery_letslist_');
    }
}
