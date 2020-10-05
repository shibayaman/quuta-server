<?php

namespace Tests\Feature;

use App\Comment;
use App\Post;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use UserPostSeeder;
use Tests\TestCase;

class ParentCommentTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function itSavesThreadsAndComment()
    {
        $this->seed(UserPostSeeder::class);
        $user = User::first();
        $post = Post::first();

        $response = $this->actingAs($user)->postJson('/api/comment/parent', [
            'post_id' => $post->post_id,
            'comment' => 'hello world'
        ]);

        $response->assertStatus(201);

        $post = $post->fresh();
        $this->assertEquals(1, $post->comment_count);


        $comment = Comment::first();
        
        $this->assertEquals(1, Thread::count());
        $this->assertDatabaseHas('threads', [
            'post_id' => $post->post_id
        ]);

        $thread = Thread::first();

        $this->assertEquals(1, Comment::count());
        $this->assertDatabaseHas('comments', [
            'thread_id' => $thread->thread_id,
            'user_id' => $user->user_id,
            'comment' => 'hello world'
        ]);
    }

    /** @test */
    public function itValidatesIfPostExistsWhenCreatingNewThread()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson('/api/comment/parent', [
            'post_id' => 12345,
            'comment' => 'post_id above does not exist'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['post_id']);
    }
}
