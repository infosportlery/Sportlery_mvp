<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSportleryLetslistType extends Migration
{
    public function up()
    {
        Schema::create('sportlery_letslist_type', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 255);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sportlery_letslist_type');
    }
}
