<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateSprCategoryEventTable extends Migration
{
    public function up()
    {
        Schema::create('spr_category_event', function(Blueprint $table) {
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('event_id');
            $table->timestamps();
            $table->primary(['category_id', 'event_id']);

            // Detach all categories when a event is deleted.
            $table->foreign('event_id')
                  ->references('id')->on('spr_events')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Don't allow deleting a category with events attached.
            $table->foreign('category_id')
                  ->references('id')->on('spr_categories')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('spr_category_event');
    }
}
