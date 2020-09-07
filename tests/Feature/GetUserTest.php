<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itGetsUser()
    {
        $this->getJson('/api/user/someone')
            ->assertNotFound();

        factory(User::class)->create(['user_id' => 'someone']);

        $response = $this->getJson('/api/user/someone');
        $response->assertOk();
        $response->assertJson([
            'user_id' => 'someone'
        ]);
    }
}
