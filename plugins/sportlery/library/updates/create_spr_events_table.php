<?php

namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSprEventsTable extends Migration
{
    public function up()
    {
        Schema::create('spr_events', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->dateTime('starts_at')->index();
            $table->dateTime('ends_at');
            $table->text('description');
            $table->integer('price')->index(); // Price in cents
            $table->unsignedInteger('location_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('location_id')
                  ->references('id')->on('spr_locations')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('spr_events');
    }
}
