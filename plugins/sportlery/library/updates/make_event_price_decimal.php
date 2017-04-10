<?php

namespace Sportlery\Library\Updates;

use DB;
use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class MakeEventPriceDecimal extends Migration
{
    public function up()
    {
        $events = DB::table('spr_events')->lists('price', 'id');

        Schema::table('spr_events', function(Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('spr_events', function(Blueprint $table) {
            $table->decimal('price', 8, 2)->after('description');
        });

        foreach ($events as $id => $price) {
            DB::table('spr_events')->where('id', $id)->update(compact('price'));
        }
    }

    public function down()
    {
        $events = DB::table('spr_events')->lists('price', 'id');

        Schema::table('spr_events', function(Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('spr_events', function(Blueprint $table) {
            $table->integer('price')->after('description');
        });

        foreach ($events as $id => $price) {
            DB::table('spr_events')->where('id', $id)->update(compact('price'));
        }
    }
}

