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
        
        $thread = Thread::all();
        $this->assertEquals(1, $thread->count());
        $this->assertEquals($post->post_id, $thread[0]->post_id);

        $this->assertEquals(1, Comment::count());

        $comment = Comment::find($thread[0]->comment_id);
        $this->assertEquals($user->user_id, $comment->user_id);
        $this->assertEquals('hello world', $comment->comment);
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
