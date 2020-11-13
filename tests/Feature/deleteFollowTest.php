<?php

namespace Tests\Feature;

use App\Follow;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class deleteFollowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itDeletesFollowAndDecrementFollowCountOfUsers()
    {
        [$user, $other] = factory(User::class, 2)->create();

        $follow = factory(Follow::class)->create([
            'user_id' => $user->user_id,
            'follow_user_id' => $other->user_id,
        ]);

        $this->assertEquals(1, $user->fresh()->following_count);
        $this->assertEquals(1, $other->fresh()->follower_count);

        $response = $this->actingAs($user)->deleteJson('/api/follow?user_id=' . $other->user_id);
        $response->assertNoContent();
        
        $this->assertDeleted($follow);

        $this->assertEquals(0, $user->fresh()->following_count);
        $this->assertEquals(0, $other->fresh()->follower_count);
    }
    
    /** @test */
    public function itChecksIfUserExists()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->deleteJson('/api/follow?user_id=noSuchUser');
        $response->assertStatus(422);
    }
    
    /** @test */
    public function userCannotUnfollowUserHeHasNotFollowed()
    {
        [$user, $other] = factory(User::class, 2)->create();

        $response = $this->actingAs($user)->deleteJson('/api/follow?user_id=' . $other->user_id);
        $response->assertStatus(422);
    }
}
