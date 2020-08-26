<?php

use App\Good;
use App\Post;
use App\User;
use Illuminate\Database\Seeder;

class PostGoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create();
        $post = $user->posts()->save(factory(Post::class)->make());
        $post->goods()->save(factory(Good::class)->make(['user_id' => $user->user_id]));

        $post->good_count = 1;
        $post->save();
    }
}
