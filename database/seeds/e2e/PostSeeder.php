<?php

use App\Post;
use App\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $users->each(function ($user) {
            $user->post()->createMany(
                factory(Post::class, 5)->make()->toArray()
            );
        });
    }
}
