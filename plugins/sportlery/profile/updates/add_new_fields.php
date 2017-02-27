<?php namespace Sportlery\profile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddNewFields extends Migration
{

    public function up()
    {
        Schema::table('users', function($table)
        {

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('street')->nullable();
            $table->string('street_num')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->integer('tel_no')->nullable();
            $table->string('favorite_sport')->nullable();
            $table->text('bio')->nullable();


        });
    }

    public function down()
    {
        $table->dropDown([
            'first_name',
            'last_name',
            'street',
            'street_num',
            'zip_code',
            'city',
            'tel_no',
            'favorite_sport',
            'bio'
            ]);
    }

}
