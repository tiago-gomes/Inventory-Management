<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class ApiLiveTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_public_application_returns_a_successful_response(): void
    {
        $response = $this->json('get','api/public/test');

        $response->assertStatus(200);
    }

    public function test_the_public_application_returns_a_not_found_response(): void
    {
        $response = $this->json('get','/api/public/no-route');

        $response->assertStatus(404);
    }

    public function test_the_protected_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->json('get','/api/protected/test');

        $response->assertStatus(200);
    }


    public function test_the_protected_application_returns_a_unauthenticated_response(): void
    {
        $response = $this->json('get','/api/protected/test');

        $response->assertStatus(401);
    }
}
