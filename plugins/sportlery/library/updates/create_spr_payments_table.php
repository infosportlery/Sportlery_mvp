<?php

namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSprPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('spr_payments', function(Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 30)->unique();
            $table->string('payment_method');
            $table->decimal('amount', 8, 2);
            $table->decimal('amount_refunded', 8, 2)->default(0);
            $table->string('transaction_reference')->index();
            $table->string('status');
            $table->dateTime('paid_at')->nullable();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('spr_payments');
    }
}
