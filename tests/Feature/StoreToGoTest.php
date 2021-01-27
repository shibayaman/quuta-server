<?php

namespace Tests\Feature;

use App\ToGo;
use App\User;
use App\Services\GurunaviApiService;
use Grimzy\LaravelMysqlSpatial\Types\Point;
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
                    'latitude' => 30.000000,
                    'longitude' => 100.000000,
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

        $toGo = ToGo::where('restaurant_id', 'idOfARestaurant')->first();
        $this->assertNotNull($toGo);
        $this->assertEquals(30.000000, $toGo->location->getLat());
        $this->assertEquals(100.000000, $toGo->location->getLng());
    }
    
    /** @test */
    public function itRejectsUserAddingSameRestaurant()
    {
        $user = User::first();

        factory(ToGo::class)->create([
            'restaurant_id' => 'duplicateRestaurant',
            'user_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)->postJson('/api/goto', [
            'restaurant_id' => 'duplicateRestaurant'
        ]);

        $response->assertStatus(422);
    }
}
