<?php

use App\Post;
use App\Thread;
use App\User;
use Illuminate\Database\Seeder;

class ThreadSeeder extends Seeder
{
    public function run()
    {
        $users = User::with('post')->get();

        $users->each(function ($user) {
            $user->post->random(3)->each(function ($post) {
                $post->thread()->createMany(
                    factory(Thread::class, 2)->make()->toArray()
                );
            });
        });
    }
}
