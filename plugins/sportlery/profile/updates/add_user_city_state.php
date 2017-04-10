<?php namespace Sportlery\profile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddUserCityState extends Migration
{

    public function up()
    {
        Schema::table('users', function($table)
        {

            $table->string('state');
            $table->string('country');

        });
    }

    public function down()
    {
        Schema::table('users',function($table) {
            $table->dropColumn([
                'country',
                'state'
                ]);
            

            });
    }

}
