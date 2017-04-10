<?php namespace Sportlery\profile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddNewFields extends Migration
{

    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('street_num', 'first_name', 'last_name');

            $table->decimal('latitude', 8, 6)->after('city')->nullable()->index();
            $table->decimal('longitude', 9, 6)->after('latitude')->nullable()->index();

        });
    }

    public function down()
    {
        Schema::table('users',function($table) {
            $table->dropColumn([
                'latitude',
                'longitude'
                ]);
            $table->string('street_num')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            

            });
    }

}
