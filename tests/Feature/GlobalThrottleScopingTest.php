<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class GlobalThrottleScopingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Daftarkan route dummy khusus untuk testing rate limiter
        Route::get('/dummy-rate-limited-web', function () {
            return 'OK';
        })->middleware('throttle:1,1');

        Route::get('/api/dummy-rate-limited-api', function () {
            return response()->json(['message' => 'OK']);
        })->middleware('throttle:1,1');
    }

    public function test_unrelated_web_route_returns_standard_429()
    {
        // Request pertama lolos
        $response1 = $this->get('/dummy-rate-limited-web');
        $response1->assertStatus(200);

        // Request kedua terkena rate limit (batas cuma 1/menit)
        $response2 = $this->get('/dummy-rate-limited-web');
        
        // HARUS 429 raw, BUKAN 302 redirect
        $response2->assertStatus(429);
        $response2->assertSee('Too Many Requests');
    }

    public function test_unrelated_api_route_returns_json_429()
    {
        // Request pertama lolos
        $response1 = $this->getJson('/api/dummy-rate-limited-api');
        $response1->assertStatus(200);

        // Request kedua terkena rate limit
        $response2 = $this->getJson('/api/dummy-rate-limited-api');
        
        // HARUS 429 raw JSON
        $response2->assertStatus(429);
        $response2->assertJsonStructure(['message']);
    }
}
