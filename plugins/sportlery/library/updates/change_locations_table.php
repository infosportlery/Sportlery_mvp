<?php

namespace Sportlery\Library\Updates;

use October\Rain\Database\Schema\Blueprint;
use DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class ChangeLocationsTable extends Migration
{
    public function up()
    {
        $emails = DB::table('spr_locations')->lists('email', 'id');
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->dropColumn('email', 'kvk_number', 'iban_id', 'btw_number', 'avatar');
        });
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->string('email')->after('name')->nullable();
            $table->string('kvk_number')->after('is_hidden')->nullable();
            $table->string('btw_number')->after('kvk_number')->nullable();
            $table->string('iban_number')->after('btw_number')->nullable();
            $table->unsignedInteger('user_id')->after('btw_number')->nullable()->index();
        });

        foreach ($emails as $id => $email) {
            DB::table('spr_locations')->where('id', $id)->update(compact('email'));
        }
    }

    public function down()
    {
        $emails = DB::table('spr_locations')->lists('email', 'id');
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->dropColumn(['email', 'kvk_number', 'iban_number', 'btw_number', 'user_id']);
        });
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->string('email')->after('name');
            $table->string('avatar')->after('description');
            $table->string('kvk_number');
            $table->string('btw_number');
            $table->string('iban_number');
        });
        foreach ($emails as $id => $email) {
            $email = $email ?: '';
            DB::table('spr_locations')->where('id', $id)->update(compact('email'));
        }
    }
}
