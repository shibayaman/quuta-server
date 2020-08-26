<?php

namespace Tests\Feature;

use App\Good;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PostGoodSeeder;

class DeleteGoodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itDeletesGoodAndDecrementsPostGoodCount()
    {
        $this->seed(PostGoodSeeder::class);
        $user = User::first();
        $post = Post::first();
        $good = $post->goods()->first();

        $response = $this->actingAs($user)->deleteJson('/api/good?post_id=' . $post->post_id);

        $response->assertOk();
        $this->assertDeleted($good);
        
        $this->assertEquals(0, $post->fresh()->good_count);
    }

    public function itValidatesIfGoodBelongsUser()
    {
        $this->seed(PostGoodSeeder::class);
        $post = Post::first();
        $other = factory(User::class)->create();

        $response = $this->actingAs($other)->deleteJson('/api/good?post_id=' . $post->post_id);

        $response->assertStatus(422);
    }

    /** @test */
    public function itValidatesIfPostExists()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->deleteJson('/api/good?post_id=' . 12345);
        
        $response->assertJsonValidationErrors(['post_id']);
    }
}
