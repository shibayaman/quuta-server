<?php

use App\Comment;
use App\Post;
use App\User;
use App\Thread;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $threads = Thread::all();
        $users = User::all();
        $threads->each(function ($thread) use ($users) {
            $commentingUsers = $users->random(2)->shuffle();
            
            $comments = $users->random(2)->map(function ($user) use ($thread) {
                return $thread->comment()->save(
                    factory(Comment::class)->make(['user_id' => $user->user_id])
                );
            });

            $thread->comment_id = $comments->min('comment_id');
            $thread->save();
        });
    }
}
