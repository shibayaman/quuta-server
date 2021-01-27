<?php

namespace Tests\Feature;

use App\ToGo;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteToGoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        factory(User::class, 2)->create();
    }

    /** @test */
    public function itDeletesToGo()
    {
        $user = User::first();

        factory(ToGo::class)->create([
            'restaurant_id' => 'restaurantToBeDeleted',
            'user_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/goto/restaurantToBeDeleted');

        $response->assertStatus(204);
        $this->assertDeleted('to_goes', [
            'restaurant_id' => 'restaurantToBeDeleted',
            'user_id' => $user->user_id,
        ]);
    }

    /** @test */
    public function itReturns404WhenUserDoesNotHaveSpecifiedToGo()
    {
        [$user, $other] = User::take(2)->get();

        $response = $this->actingAs($user)->deleteJson('/api/goto/noSuchToGo');
        $response->assertNotFound();

        factory(ToGo::class)->create([
            'restaurant_id' => 'notMine',
            'user_id' => $other->user_id,
        ]);

        $response = $this->actingAs($user)->deleteJson('/api/goto/notMine');
        $response->assertNotFound();
    }
}
