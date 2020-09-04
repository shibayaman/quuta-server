<?php

namespace Tests\Feature;

use App\Good;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use UserPostSeeder;

class StoreGoodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itSavesGoodAndIncrementsPostGoodCount()
    {
        $this->seed(UserPostSeeder::class);
        $user = User::first();
        $post = Post::first();

        $response = $this->actingAs($user)->postJson('/api/good', [
            'post_id' => $post->post_id
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('goods', [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id
        ]);

        $post = $post->fresh();
        $this->assertEquals(1, $post->good_count);
    }

    /** @test */
    public function itDeniesMultipleGoodsToBeCreated()
    {
        $this->seed(UserPostSeeder::class);
        $user = User::first();
        $post = Post::first();

        factory(Good::class)->create([
            'user_id' => $user->user_id,
            'post_id' => $post->post_id
        ]);

        $response = $this->actingAs($user)->postJson('/api/good', [
            'post_id' => $post->post_id
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function itValidatesIfPostExists()
    {
        $user = factory(User::class)->create();
        
        $response = $this->actingAs($user)->postJson('/api/good', [
            'post_id' => 12345
        ]);

        $response->assertJsonValidationErrors(['post_id']);
    }
}
