<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function itReturnsAccessTokenWhenUserLogsIn()
    {
        $me = factory(User::class)->create([
            'email' => 'quuta@quuta.com',
            'password' => Hash::make('quuta'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'quuta@quuta.com',
            'password' => 'quuta'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
        ]);
    }

    /** @test */
    public function itReturns401WhenLoginFailed()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'thereIsNoSuchUser@fake.com',
            'password' => 'thisIsTheMostSecurePasswordInTheWorld'
        ]);

        $response->assertStatus(401);
    }
}
