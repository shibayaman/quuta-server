<?php

namespace Tests\Feature;

use App\Follow;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class storeFollowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itSavesFollowAndIncrementFollowCountOfUsers()
    {
        [$user, $other] = factory(User::class, 2)->create();

        $response = $this->actingAs($user)->postJson('/api/follow', [
            'user_id' => $other->user_id,
            'subscription_flag' => true
        ]);
        
        $response->assertCreated();
        $this->assertDatabaseHas('follows', [
            'user_id' => $user->user_id,
            'follow_user_id' => $other->user_id,
            'subscription_flag' => true
        ]);

        $this->assertEquals(1, $user->fresh()->following_count);
        $this->assertEquals(1, $other->fresh()->follower_count);
    }

    /** @test */
    public function itChecksIfUserExists()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson('/api/follow', [
            'user_id' => 'there is no such user'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function itDoesNotAllowFollowSameUserTwice()
    {
        [$user, $other] = factory(User::class, 2)->create();

        factory(Follow::class)->create([
            'user_id' => $user->user_id, 'follow_user_id' => $other->user_id
        ]);

        $response = $this->actingAs($user)->postJson('/api/follow', [
            'user_id' => $other->user_id
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function UserCannotFollowHimself()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson('/api/follow', [
            'user_id' => $user->user_id
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['user_id']);
    }
}
