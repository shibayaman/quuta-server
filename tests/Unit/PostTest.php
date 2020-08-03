<?php

namespace Tests\Unit;

use App\Good;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use UserPostSeeder;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserPostSeeder::class);
    }

    /** @test */
    public function getBetweenNullsReturnsAllPosts()
    {
        $this->assertEquals(Post::count(), Post::getBetween(null, null)->count());
    }

    /** @test */
    public function getBetweenGetsPostsBetweenGivenIds()
    {
        $posts = Post::getBetween(2, 7)->get();
        $postKeys = $posts->sortBy('post_id')->values()->modelKeys();

        $this->assertEquals([3, 4, 5, 6, 7], $posts->modelKeys());
    }

    /** @test */
    public function getBetweenLimitsNumOfPosts()
    {
        $posts = Post::getBetween(2, 7, $count = 3)->get();
        $postKeys = $posts->sortBy('post_id')->values()->modelKeys();

        $this->assertEquals([5, 6, 7], $posts->sortBy('post_id')->values()->modelKeys());
    }

    /** @test */
    public function withGoodedByUserLoadsGoodsOfGivenUser()
    {
        [$user, $other] = User::limit(2)->get();
        $post = Post::first();

        $post = Post::withGoodedByUser($user->user_id)->find($post->getKey());
        $this->assertTrue($post->relationLoaded('good'));
        $this->assertTrue($post->good->isEmpty());

        $post->good()->save(factory(Good::class)->make(['user_id' => $user->user_id]));
        $post->good()->save(factory(Good::class)->make(['user_id' => $other->user_id]));

        $post = Post::withGoodedByUser($user->user_id)->find($post->getKey());
        $this->assertEquals(1, $post->good->count());
    }
}
