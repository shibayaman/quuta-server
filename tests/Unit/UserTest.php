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

        $this->assertEmpty($users[0]->homeTimeline()->toArray());

        $users[0]->followings()->save(
            factory(Follow::class)->make(['follow_user_id' => $users[1]->user_id])
        );

        $posts = $users[0]->homeTimeline();
        $this->assertEquals(3, $posts->count());
        
        $filterd = $posts->filter(function ($post) use ($users) {
            return $post->user_id === $users[1]->user_id;
        });
        $this->assertEquals(3, $filterd->count());
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
    public function getPostsLoadsRelations()
    {
        $user = User::first();

        $post = $user->getPosts(null, null, null)[0];
        $this->assertTrue($post->relationLoaded('goods'));
        $this->assertTrue($post->relationLoaded('images'));
        $this->assertTrue($post->relationLoaded('user'));
    }
}
