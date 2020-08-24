<?php

namespace Tests\Feature;

use App\Comment;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use ThreadCommentSeeder;

class DeleteCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itDeletesComment()
    {
        $this->seed(ThreadCommentSeeder::class);
        $thread = Thread::first();
        $comment = $thread->comments[1];

        $response = $this->actingAs(User::first())
            ->deleteJson('/api/comment/' . $comment->comment_id);

        $response->assertOk();
        $this->assertSoftDeleted($comment);
        $this->assertTrue($thread->is(Thread::first()));
    }
    
    /** @test */
    public function itDeletesEntireThreadWhenParentCommentDeleted()
    {
        $this->seed(ThreadCommentSeeder::class);
        $thread = Thread::first();

        $response = $this->actingAs(User::first())
            ->deleteJson('/api/comment/' . $thread->comment_id);
    
        $response->assertOk();
        $this->assertSoftDeleted($thread);
        $this->assertTrue($thread->comments->isEmpty());
    }

    /** @test */
    public function itReturns403WhenCommentNotBelongToUser()
    {
        $this->seed(ThreadCommentSeeder::class);
        $user = factory(User::class)->create();
        $comment = Comment::first();

        $response = $this->actingAs($user)
            ->deleteJson('/api/comment/' . $comment->comment_id);

        $response->assertForbidden();
    }
}
