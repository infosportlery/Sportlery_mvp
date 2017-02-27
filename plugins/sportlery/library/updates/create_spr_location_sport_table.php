<?php

namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSprLocationSportTable extends Migration
{
    public function up()
    {
        Schema::create('spr_location_sport', function (Blueprint $table) {
            $table->unsignedInteger('location_id');
            $table->unsignedInteger('sport_id');
            $table->timestamps();

            $table->primary(['location_id', 'sport_id']);

            // Delete any links when deleting a location.
            $table->foreign('location_id')
                  ->references('id')->on('spr_locations')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Don't allow deleting a sport that has locations attached.
            $table->foreign('sport_id')
                  ->references('id')->on('spr_sports')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('spr_location_sport');
    }
}
