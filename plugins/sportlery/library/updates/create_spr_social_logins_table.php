<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateSprSocialLoginsTable extends Migration
{
    public function up()
    {
        Schema::create('spr_social_logins', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('provider', 50)->index();
            $table->string('provider_user_id', 75)->index();
            $table->string('access_token', 50)->nullable();
            $table->string('refresh_token', 50)->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('spr_social_logins');
    }
}
