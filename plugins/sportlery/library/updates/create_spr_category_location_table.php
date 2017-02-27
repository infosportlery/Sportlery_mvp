<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateSprCategoryLocationTable extends Migration
{
    public function up()
    {
        Schema::create('spr_category_location', function(Blueprint $table) {
            $table->unsignedInteger('location_id');
            $table->unsignedInteger('category_id');
            $table->timestamps();
            $table->primary(['location_id', 'category_id']);

            // Detach all categories when deleting a location.
            $table->foreign('location_id')->references('id')->on('spr_locations')->onUpdate('cascade')->onDelete('cascade');

            // Don't allow deleting a category with locations attached.
            $table->foreign('category_id')->references('id')->on('spr_categories')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('spr_category_location');
    }
}
