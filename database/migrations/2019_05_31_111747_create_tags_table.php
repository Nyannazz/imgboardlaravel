<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->integer('post_id')->unsigned()->index();
            $table->integer('tag_id')->unsigned()->index();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

        });
        /* Schema::create('tag_user', function (Blueprint $table) {
            $table->integer('tag_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        }); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('post_tag');
    }
}
