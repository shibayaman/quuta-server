<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('post_id');
            $table->string('content');
            $table->string('user_id');
            $table->boolean('like_flag')->default(false);
            $table->unsignedInteger('good_count')->default(0);
            $table->unsignedInteger('comment_count')->default(0);
            $table->string('restaurant_id');
            $table->string('restaurant_name');
            $table->string('restaurant_address');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('user_id')->on('users')
                ->onDelete('cascade');
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
