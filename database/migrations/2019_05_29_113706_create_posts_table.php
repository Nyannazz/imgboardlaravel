<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('createdBy');
            $table->string('title');
            $table->string('resourceurl');
            $table->string('thumbnail');
            $table->integer('upvotes');
            $table->integer('downvotes');
            $table->integer('views');
            $table->timestamps();
        });

        Schema::create('post_user', function (Blueprint $table) {
            $table->integer('post_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}