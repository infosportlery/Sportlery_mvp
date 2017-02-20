<?php

namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSprEventSportTable extends Migration
{
    public function up()
    {
        Schema::create('spr_event_sport', function(Blueprint $table) {
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('sport_id');
            $table->timestamps();

            $table->primary(['event_id', 'sport_id']);

            // Delete any links when deleting an event.
            $table->foreign('event_id')
                  ->references('id')->on('spr_events')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Don't allow deleting a sport that has events attached.
            $table->foreign('sport_id')
                  ->references('id')->on('spr_sports')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('spr_event_sport');
    }
}
