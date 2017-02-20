<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateSprCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('spr_categories', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('for');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spr_categories');
    }
}
