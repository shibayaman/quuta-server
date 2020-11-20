<?php

namespace Tests\Unit;

use App\Follow;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use UserPostSeeder;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserPostSeeder::class);
    }
    
    /** @test */
    public function homeTimelineShouldReturnPostsOfFolloingUsers()
    {
        $users = User::all();

        $this->assertEquals(
            $users[0]->homeTimeline()->pluck('post_id'),
            Post::where('user_id', $users[0]->user_id)->pluck('post_id')
        );

        $users[0]->followings()->save(
            factory(Follow::class)->make(['follow_user_id' => $users[1]->user_id])
        );

        $posts = $users[0]->homeTimeline();
        $this->assertEquals(
            $users[0]->homeTimeline()->pluck('post_id'),
            Post::whereIn('user_id', [$users[0]->user_id, $users[1]->user_id])->pluck('post_id')
        );
    }

    /** @test */
    public function userTimelineShouldReturnPostsOfTheUser()
    {
        $user = User::first();

        $posts = $user->userTimeline($user->user_id);
        $this->assertEquals(3, $posts->count());

        $filterd = $posts->filter(function ($post) use ($user) {
            return $post->user_id === $user->user_id;
        });
        $this->assertEquals(3, $filterd->count());
    }
    
    /** @test */
    public function restaurantTimelineReturnsPostsOfRestaurant()
    {
        $user = User::first();
        $user->posts()->save(factory(Post::class)->make(['restaurant_id' => 'aaa']));

        $posts = $user->restaurantTimeline('aaa');

        $this->assertEquals(1, $posts->count());
        $this->assertEquals('aaa', $posts[0]->restaurant_id);
    }

    /** @test */
    public function getPostsLoadsRelations()
    {
        $user = User::first();

        $post = $user->getPosts(null, null, null)[0];
        $this->assertTrue($post->relationLoaded('goods'));
        $this->assertTrue($post->relationLoaded('images'));
        $this->assertTrue($post->relationLoaded('user'));
    }
}
