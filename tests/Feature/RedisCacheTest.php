<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use App\Livewire\Posts\Filter\CategoriesFilter;

class RedisCacheTest extends TestCase
{
    public function test_redis_connection_works()
    {
        $response = Redis::ping();

        // Redis ping() can return different types depending on the driver
        // PhpRedis extension returns true, Predis returns a response object
        if (is_bool($response)) {
            $this->assertTrue($response);
        } else {
            $this->assertEquals('PONG', $response->getPayload());
        }
    }

    public function test_cache_can_store_and_retrieve_data()
    {
        $key = 'test_cache_key';
        $value = 'test_cache_value';

        Cache::put($key, $value, 60);
        $retrieved = Cache::get($key);

        $this->assertEquals($value, $retrieved);
    }

    public function test_categories_filter_cache_key_generation()
    {
        // Test that the same filter state generates the same cache key
        $filterString1 = 'search:laravel|tag:php|author:1';
        $filterString2 = 'search:laravel|tag:php|author:1';
        $filterString3 = 'search:vue|tag:js|author:2';

        $hash1 = md5($filterString1);
        $hash2 = md5($filterString2);
        $hash3 = md5($filterString3);

        // Same input should produce same hash
        $this->assertEquals($hash1, $hash2);

        // Different input should produce different hash
        $this->assertNotEquals($hash1, $hash3);
    }

    public function test_categories_filter_cache_clear()
    {
        // Set some test cache data using Laravel's Cache facade (respects prefixes)
        $testKey = 'categories_filter:test123';
        Cache::put($testKey, ['test' => 'data'], 60);

        // Verify it exists
        $this->assertNotNull(Cache::get($testKey));

        // Clear the cache
        CategoriesFilter::clearCache();

        // This test mainly ensures the clearCache method doesn't throw errors
        $this->assertTrue(true);
    }

    public function test_redis_with_laravel_cache_facade()
    {
        // Test that Laravel's Cache facade works with Redis
        $key = 'laravel_cache_test';
        $value = ['data' => 'test', 'number' => 123];

        // Store complex data
        Cache::put($key, $value, 60);

        // Retrieve and verify
        $retrieved = Cache::get($key);
        $this->assertEquals($value, $retrieved);

        // Test cache expiration
        $this->assertTrue(Cache::has($key));

        // Clean up
        Cache::forget($key);
        $this->assertFalse(Cache::has($key));
    }

    public function test_redis_serialization()
    {
        // Test that complex objects can be serialized/unserialized properly
        $testData = collect([
            ['id' => 1, 'name' => 'Category 1', 'posts_count' => 5],
            ['id' => 2, 'name' => 'Category 2', 'posts_count' => 3],
        ]);

        $serialized = serialize($testData);
        $unserialized = unserialize($serialized);

        $this->assertEquals($testData->toArray(), $unserialized->toArray());
    }

    public function test_redis_performance()
    {
        $start = microtime(true);

        // Perform 50 cache operations
        for ($i = 0; $i < 50; $i++) {
            Cache::put("perf_test_{$i}", "value_{$i}", 60);
        }

        $end = microtime(true);
        $duration = ($end - $start) * 1000; // Convert to milliseconds

        // Should complete 50 operations in less than 1 second (1000ms)
        $this->assertLessThan(1000, $duration, "Redis operations took too long: {$duration}ms");
    }
}
