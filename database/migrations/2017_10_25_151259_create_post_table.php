<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::create('post', function(Blueprint $table) {
            $table->increments('id')->unsigned();            
            $table->integer('user_id')->unsigned()->index();
            $table->string('title', 200)->index();
            $table->string('url', 255)->index();
            $table->string('description', 255)->nullable();
            $table->integer('total_up_votes')->default(0)->unsigned()->index();
            $table->integer('total_down_votes')->default(0)->unsigned()->index();
            $table->integer('score')->default(0)->unsigned()->index();
            $table->tinyInteger('status')->default(1)->unsigned()->index()->comment = "1: Active, 0: Inactive";
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('post');
    }
}
