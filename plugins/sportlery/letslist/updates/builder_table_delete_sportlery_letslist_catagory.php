<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteSportleryLetslistCatagory extends Migration
{
    public function up()
    {
        Schema::dropIfExists('sportlery_letslist_catagory');
    }
    
    public function down()
    {
        Schema::create('sportlery_letslist_catagory', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 255);
            $table->string('slug', 255)->nullable();
        });
    }
}
