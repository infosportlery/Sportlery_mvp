<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSportleryLetslistLocation extends Migration
{
    public function up()
    {
        Schema::create('sportlery_letslist_location', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('street', 255);
            $table->string('zipcode', 7);
            $table->text('description');
            $table->string('avatar');
            $table->integer('type_id');
            $table->string('url');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('city');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sportlery_letslist_location');
    }
}
