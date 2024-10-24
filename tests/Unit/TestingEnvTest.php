<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestingEnvTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_cache_is_working()
    {
        // Arrange: Add a value to the cache
        Cache::put('key', 'value', now()->addMinutes(10));

        // Act: Retrieve the value from the cache
        $cachedValue = Cache::get('key');

        // Assert: Ensure the cache contains the expected value
        $this->assertEquals('value', $cachedValue);
    }

    public function test_it_uses_sqlite_database()
    {
        $this->assertEquals('sqlite', config('database.default'));
        $this->assertEquals(':memory:', config('database.connections.sqlite.database'));
    }


    public function test_it_can_create_a_user()
    {
        // Create a user instance
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
        ]);

        // Assert the user is in the database
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
        ]);
    }
}
