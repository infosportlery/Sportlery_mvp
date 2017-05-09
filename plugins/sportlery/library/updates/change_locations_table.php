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
            $table->dropColumn('email', 'avatar');
        });
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->string('email')->after('name')->nullable();
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
            $table->dropColumn(['email', 'user_id']);
        });
        Schema::table('spr_locations', function(Blueprint $table) {
            $table->string('email')->after('name');
            $table->string('avatar')->after('description');
        });
        foreach ($emails as $id => $email) {
            $email = $email ?: '';
            DB::table('spr_locations')->where('id', $id)->update(compact('email'));
        }
    }
}
