<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use App\Thread;
use App\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use ThreadCommentSeeder;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itDeletesPostAndItsRelations()
    {
        $this->seed(ThreadCommentSeeder::class);

        $user = User::first();
        $post = Post::first();

        $response = $this->actingAs($user)->delete('/api/post/' . $post->post_id);

        $response->assertNoContent();
        $this->assertSoftDeleted($post);
        $this->assertTrue(Thread::all()->isEmpty());
        $this->assertTrue(Comment::all()->isEmpty());
    }

    /** @test */
    public function itReturns403WhenPostNotBelongToUser()
    {
        $this->seed(ThreadCommentSeeder::class);

        $other = factory(User::class)->create();
        $post = Post::first();

        $response = $this->actingAs($other)->delete('/api/post/' . $post->post_id);
        $response->assertForbidden();
    }
}
