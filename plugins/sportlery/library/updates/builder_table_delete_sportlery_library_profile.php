<?php namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteSportleryLibraryProfile extends Migration
{
    public function up()
    {
        Schema::dropIfExists('sportlery_library_profile');
    }
    
    public function down()
    {
        Schema::create('sportlery_library_profile', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('street', 255);
            $table->integer('str_no');
            $table->string('zip', 6);
            $table->string('city', 255);
            $table->integer('tel_no');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('favorite_sport', 255);
        });
    }
}
