<?php

namespace Tests\Feature;

use App\Post;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use UserPostSeeder;

class ChildCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itSavesComment()
    {
        $this->seed(UserPostSeeder::class);
        $user = User::first();
        $thread = factory(Thread::class)->create([
            'post_id' => Post::first()->post_id
        ]);

        $response = $this->actingAs($user)->postJson('/api/comment/child', [
            'comment' => 'hello world',
            'thread_id' => $thread->thread_id
        ]);

        $response->assertCreated();
        
        $this->assertEquals(1, $thread->comments->count());

        $comment = $thread->comments[0];
        $this->assertEquals($user->user_id, $comment->user_id);
        $this->assertEquals('hello world', $comment->comment);
    }
}
