<?php

namespace Tests\Feature;

use App\ToGo;
use App\User;
use App\Services\GurunaviApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreToGoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        factory(User::class)->create();

        $this->partialMock(GurunaviApiService::class, function ($mock) {
            $mock->shouldReceive('getRestaurant')
                ->with('idOfARestaurant')
                ->andReturn([
                    'latitude' => 100.000001,
                    'longitude' => 200.000002,
                ]);
        });
    }

    /** @test */
    public function itSavesToGo()
    {
        $user = User::first();
        $response = $this->actingAs($user)->postJson('/api/goto', [
            'restaurant_id' => 'idOfARestaurant'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('to_goes', [
            'restaurant_id' => 'idOfARestaurant',
            'latitude' => 100.000001,
            'longitude' => 200.000002,
            'user_id' => $user->user_id
        ]);
    }
    
    /** @test */
    public function itRejectsUserAddingSameRestaurant()
    {
        $user = User::first();

        ToGo::create([
            'restaurant_id' => 'duplicateRestaurant',
            'latitude' => 1.1,
            'longitude' => 2.2,
            'user_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)->postJson('/api/goto', [
            'restaurant_id' => 'duplicateRestaurant'
        ]);

        $response->assertStatus(422);
    }
}
