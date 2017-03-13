<?php namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSportleryLibraryTicketMessages extends Migration
{
    public function up()
    {
        Schema::create('sportlery_library_ticket_messages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('ticket_id');
            $table->text('message');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sportlery_library_ticket_messages');
    }
}
