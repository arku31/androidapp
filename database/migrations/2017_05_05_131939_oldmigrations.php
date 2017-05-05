<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Oldmigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('login')->unique();
            $table->string('password',60)->required();
            $table->float('balance')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('categories', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->integer('user_id');
            $table->timestamps();
        });
        Schema::create('operations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('user_id');
            $table->string('comment');
            $table->float('sum');
            $table->date('tr_date');
            $table->timestamps();
        });
        Schema::create('sessions', function($t)
        {
            $t->string('id')->unique();
            $t->text('payload');
            $t->integer('last_activity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
