<?php namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSportleryLibraryUserSports extends Migration
{
    public function up()
    {
        Schema::create('sportlery_library_user_sports', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('user_id');
            $table->integer('sports_id');
            $table->smallInteger('interest_level');
            $table->primary(['user_id','sports_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sportlery_library_user_sports');
    }
}
