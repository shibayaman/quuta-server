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

        $commentContent = 'random comment';
        $response = $this->actingAs($user)->postJson('/api/comment/parent', [
            'post_id' => $post->post_id,
            'comment' => $commentContent
        ]);

        $response->assertStatus(201);
        
        $thread = Thread::all();
        $this->assertEquals($thread->count(), 1);
        $this->assertEquals($thread[0]->post_id, $post->post_id);

        $comment = Comment::find($thread[0]->comment_id);
        $this->assertEquals($comment->user_id, $user->user_id);
        $this->assertEquals($comment->comment, $commentContent);
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
