<?php

use App\Comment;
use App\Post;
use App\Thread;
use App\User;
use Illuminate\Database\Seeder;

class ThreadCommentSeeder extends Seeder
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

        $threads = $post->threads()->saveMany(factory(Thread::class, 3)->make());

        $threads->each(function ($thread) use ($user) {
            $comment = $thread->comments()->saveMany(factory(Comment::class, 3)->make(['user_id' => $user->user_id]));
            
            $thread->comment_id = $comment[0]->comment_id;
            $thread->save();
        });
    }
}
