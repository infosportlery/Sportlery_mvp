<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class AddSoftDeletesToChatMessages extends Migration
{
    public function up()
    {
        Schema::table('spr_chat_messages', function(Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('spr_chat_messages', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
