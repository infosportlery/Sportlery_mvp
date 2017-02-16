<?php namespace Sportlery\Letslist\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSportleryLetslistLocationType extends Migration
{
    public function up()
    {
        Schema::rename('sportlery_letslist_', 'sportlery_letslist_location_type');
    }
    
    public function down()
    {
        Schema::rename('sportlery_letslist_location_type', 'sportlery_letslist_');
    }
}
