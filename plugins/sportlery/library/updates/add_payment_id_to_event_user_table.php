<?php

namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddPaymentIdToEventUserTable extends Migration
{
    public function up()
    {
        Schema::table('spr_event_user', function(Blueprint $table) {
            $table->unsignedInteger('payment_id')->nullable()->index()->after('status');
            $table->foreign('payment_id')
                  ->references('id')->on('spr_payments')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('spr_event_user', function(Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropColumn('payment_id');
        });
    }
}
