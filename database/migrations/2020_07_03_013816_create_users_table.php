<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('password_updated_at')->nullable();
            $table->boolean('private_flag');
            $table->date('birthday_date');
            $table->unsignedInteger('sex_id')->nullable();
            $table->string('icon_url');
            $table->string('password_reset_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->string('description');
            $table->unsignedInteger('follower_count')->default(0);
            $table->unsignedInteger('following_count')->default(0);
            $table->unsignedInteger('good_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->primary('user_id');
            $table->foreign('sex_id')
                ->references('sex_id')->on('sexes')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
