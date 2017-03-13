<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateSprEventInvitesTable extends Migration
{
    public function up()
    {
        Schema::create('spr_event_invites', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('event_id')->index();
            $table->unsignedInteger('invited_by')->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('spr_events')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('invited_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('spr_event_invites');
    }
}
