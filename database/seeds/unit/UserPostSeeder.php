<?php

use App\User;
use App\Follow;
use App\Post;

use Illuminate\Database\Seeder;

class UserPostSeeder extends Seeder
{
    public function run()
    {
        $users = factory(User::class, 3)->create();
        $users->each(function ($user) {
            $user->post()->saveMany(factory(Post::class, 3)->make());
        });
    }
}
