<?php namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLibraryTickets extends Migration
{
    public function up()
    {
        Schema::table('sportlery_library_tickets', function($table)
        {
            $table->boolean('completed');
            $table->integer('user_id');
            $table->increments('id')->change();
        });
    }
    
    public function down()
    {
        Schema::table('sportlery_library_tickets', function($table)
        {
            $table->dropColumn('completed');
            $table->dropColumn('user_id');
            $table->integer('id')->change();
        });
    }
}
