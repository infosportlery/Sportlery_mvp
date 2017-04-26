<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateSprLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('spr_locations', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('street');
            $table->string('zipcode', 15);
            $table->string('city');
            $table->text('description');
            $table->text('description_en');
            $table->string('url');
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spr_locations');
    }
}
