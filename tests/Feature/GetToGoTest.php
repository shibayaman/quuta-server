<?php

namespace Tests\Feature;

use App\ToGo;
use App\User;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetToGoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function itGetsToGoes()
    {
        $user = factory(User::class)->create();
        $toGo = factory(ToGo::class)->create([
            'user_id' => $user->user_id,
            'location' => new Point(10.0, 100.0, 4326)
        ]);

        $response = $this->actingAs($user)->getJson('/api/goto');
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }
}
