<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CheckInHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_rate_limiting_blocks_excessive_requests()
    {
        $user = User::factory()->create(['semester' => 4, 'major' => 'Informatika']);
        
        $payload = [
            'answers' => [
                'academic_1' => 3, 'academic_2' => 3, 'academic_3' => 3,
                'financial_1' => 3, 'financial_2' => 3, 'financial_3' => 3,
                'motivational_1' => 3, 'motivational_2' => 3, 'motivational_3' => 3,
                'social_1' => 3, 'social_2' => 3, 'social_3' => 3,
            ]
        ];

        // 3 requests allowed per minute
        for ($i = 0; $i < 3; $i++) {
            $response = $this->actingAs($user)->post('/check-in', $payload);
            $response->assertStatus(302);
        }

        // 4th request should redirect with flash message
        $response = $this->actingAs($user)->post('/check-in', $payload);
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Permintaan Anda sedang diproses, mohon tunggu sebentar sebelum mencoba lagi.');
    }

    public function test_atomic_lock_prevents_concurrent_submissions()
    {
        $user = User::factory()->create(['semester' => 4, 'major' => 'Informatika']);
        
        $payload = [
            'answers' => [
                'academic_1' => 3, 'academic_2' => 3, 'academic_3' => 3,
                'financial_1' => 3, 'financial_2' => 3, 'financial_3' => 3,
                'motivational_1' => 3, 'motivational_2' => 3, 'motivational_3' => 3,
                'social_1' => 3, 'social_2' => 3, 'social_3' => 3,
            ]
        ];

        // Simulate acquiring the lock (as if a concurrent request is processing)
        $lock = Cache::lock('check-in:' . $user->id, 10);
        $this->assertTrue($lock->get(), 'Failed to acquire lock for test setup.');

        // Attempting to submit while lock is held by the "concurrent" process
        $response = $this->actingAs($user)->post('/check-in', $payload);
        
        // Assert it redirects with the specific error message instead of processing
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'Permintaan Anda sedang diproses. Mohon tunggu sesaat.');

        // Verify no assessment was created by the blocked request
        $this->assertDatabaseCount('assessments', 0);

        // Clean up
        $lock->release();
    }
}
