<?php namespace Sportlery\Library\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSportleryLibraryTickets extends Migration
{
    public function up()
    {
        Schema::create('sportlery_library_tickets', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('id');
            $table->string('ticket_hash');
            $table->string('slug');
            $table->primary(['id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sportlery_library_tickets');
    }
}
