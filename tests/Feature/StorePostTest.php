<?php

namespace Tests\Feature;

use App\Image;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use UserImageSeeder;

class StorePostTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserImageSeeder::class);
    }

    /** @test */
    public function itSavesPostAndLinkImages()
    {
        $user = User::first();
        $imageIds = $user->images()->pluck('image_id');

        $response = $this->actingAs($user)->postJson('/api/post', [
            'content' => 'this will succeed',
            'restaurant_id' => 'idOfARestaurant',
            'image_ids' => $imageIds
        ]);

        $response->assertCreated();

        $post = Post::all();
        $this->assertEquals($post->count(), 1);
        $this->assertEquals($post[0]->user_id, $user->user_id);

        Image::find($imageIds)->each(function ($image) use ($post) {
            $this->assertEquals($image->post_id, $post[0]->post_id);
        });
    }

    /** @test */
    public function itAcceptOnlyImagesOfAuthenticatedUser()
    {
        $users = User::all();
        $user = $users[0];
        $other = $users[1];

        $imageIdsOfOther = $other->images()->pluck('image_id');

        $response = $this->actingAs($user)->postJson('/api/post', [
            'content' => 'this will fail',
            'restaurant_id' => 'idOfARestaurant',
            'image_ids' => $imageIdsOfOther
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function itDoesNotAcceptImagesAlreadyBelongToPost()
    {
        $user = User::first();

        $post = $user->posts()->save(factory(Post::class)->make());
        $image = $user->images[0];
        $image->post_id = $post->post_id;
        $image->save();

        $response = $this->actingAs($user)->postJson('/api/post', [
            'content' => 'this will fail',
            'restaurant_id' => 'idOfARestaurant',
            'image_ids' => [$image->image_id]
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function itAcceptOnlyImagesThatExist()
    {
        $user = User::first();

        $response = $this->actingAs($user)->postJson('/api/post', [
            'content' => 'this will fail',
            'restaurant_id' => 'idOfARestaurant',
            'image_ids' => [1000]
        ]);

        $response->assertStatus(422);
    }
}
