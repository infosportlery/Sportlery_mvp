<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSportleryLetslistCatas extends Migration
{
    public function up()
    {
        Schema::create('sportlery_letslist_catas', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('id');
            $table->string('cat_name', 255);
            $table->primary(['id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sportlery_letslist_catas');
    }
}
