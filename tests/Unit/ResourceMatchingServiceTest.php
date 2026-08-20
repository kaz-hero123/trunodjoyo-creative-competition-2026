<?php

namespace Tests\Unit;

use App\Models\Assessment;
use App\Models\Resource;
use App\Models\User;
use App\Services\ResourceMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceMatchingServiceTest extends TestCase
{
    use RefreshDatabase;

    private ResourceMatchingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ResourceMatchingService();
    }

    public function test_match_returns_empty_when_all_kuat()
    {
        $user = User::factory()->create(['semester' => 4, 'major' => 'Informatika']);
        $assessment = new Assessment([
            'score_academic' => 80,
            'score_financial' => 80,
            'score_motivational' => 80,
            'score_social' => 80,
        ]);

        // Buat resource dummy (meskipun Kuat, tidak boleh ada yang cocok)
        Resource::factory()->create(['target_dimensions' => ['academic']]);

        $matches = $this->service->match($user, $assessment);

        $this->assertCount(0, $matches);
    }

    public function test_match_filters_out_inactive_expired_and_ineligible()
    {
        $user = User::factory()->create(['semester' => 4, 'major' => 'Informatika']);
        $assessment = new Assessment([
            'score_academic' => 30, // Perlu perhatian
            'score_financial' => 80,
            'score_motivational' => 80,
            'score_social' => 80,
        ]);

        // Tidak aktif
        Resource::factory()->create([
            'target_dimensions' => ['academic'],
            'is_active' => false,
        ]);

        // Expired
        Resource::factory()->create([
            'target_dimensions' => ['academic'],
            'deadline' => now()->subDay()->toDateString(),
        ]);

        // Semester tidak eligible (hanya smt 1-2)
        Resource::factory()->create([
            'target_dimensions' => ['academic'],
            'min_semester' => 1,
            'max_semester' => 2,
        ]);

        // Jurusan tidak eligible
        Resource::factory()->create([
            'target_dimensions' => ['academic'],
            'eligible_majors' => ['Sistem Informasi'], // bukan Informatika
        ]);

        // Eligible
        $eligibleResource = Resource::factory()->create([
            'target_dimensions' => ['academic'],
            'min_semester' => 1,
            'max_semester' => 8,
            'eligible_majors' => ['Informatika'],
            'is_active' => true,
        ]);

        $matches = $this->service->match($user, $assessment);

        $this->assertCount(1, $matches);
        $this->assertEquals($eligibleResource->id, $matches->first()->resource->id);
    }

    public function test_match_limits_to_5_resources()
    {
        $user = User::factory()->create(['semester' => 4, 'major' => 'Informatika']);
        $assessment = new Assessment([
            'score_academic' => 30, // Perlu perhatian
            'score_financial' => 30, // Perlu perhatian
            'score_motivational' => 80,
            'score_social' => 80,
        ]);

        // Buat 10 resource eligible (5 academic, 5 financial)
        Resource::factory()->count(5)->create(['target_dimensions' => ['academic']]);
        Resource::factory()->count(5)->create(['target_dimensions' => ['financial']]);

        $matches = $this->service->match($user, $assessment);

        $this->assertCount(5, $matches);
    }
}
