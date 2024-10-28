<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Mockery;

class AuthControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up mocked objects
        Mockery::close();
    }

    public function testSuccessfulLogin()
    {
        $userPassword = $this->faker->password(16);

        // Create a user with a hashed password
        $user = User::factory()->create([
            'name' => "Test",
            'email' => $this->faker->email,
            'password' => $userPassword
        ]);

        // Mock data for login request
        $data = [
            'email' => $user->email,
            'password' => $userPassword,
        ];

        // Mock the login request
        $response = $this->json('post','/api/login', $data);

        // Assert the response
        $response->assertStatus(200); // Adjust the expected status code
        $response->assertJsonStructure(['token']); // Adjust the expected JSON structure
    }

    public function testInvalidCredentials()
    {
        // Mock data for login request with invalid credentials
        $data = [
            'email' => $this->faker->email,
            'password' => $this->faker->password,
        ];

        // Mock the login request
        $response = $this->json('POST', '/api/login', $data);

        // Assert the response
        $response->assertStatus(401); // Invalid credentials should return 401 Unauthorized
        $response->assertJson(['message' => 'Invalid credentials']); // Adjust the expected error message
    }

    public function testMissingCredentials()
    {
        // Mock data for login request with missing credentials
        $data = [];

        // Mock the login request
        $response = $this->json('POST', '/api/login', $data);

        // Assert the response
        $response->assertStatus(422); // Missing credentials should return 422 Unprocessable Entity (validation error)
    }

    public function testLogoutSuccessfully()
    {
        // Create a user and log them in using Sanctum
        $user = User::factory()->create();
        $this->actingAs($user);

        // Make a request to the logout endpoint
        $response = $this->json('POST', '/api/logout');

        // Assert the response
        $response->assertStatus(200)
            ->assertJson(['message' => 'Logout successful']);

        // Assert that the user's token has been revoked
        $this->assertCount(0, $user->tokens);
    }

    public function testLogoutWithoutAuthentication()
    {
        // Make a request to the logout endpoint without authentication
        $response = $this->json('POST', '/api/logout');

        // Assert the response
        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
