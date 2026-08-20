<?php

namespace Tests\Feature\Resource;

use App\Models\Assessment;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceMatchingIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private function getValidAnswers($value = 2): array
    {
        // Value 2 -> score 40 (Berkembang), so it qualifies for matching
        return [
            'academic_1' => $value, 'academic_2' => $value, 'academic_3' => $value,
            'financial_1' => $value, 'financial_2' => $value, 'financial_3' => $value,
            'motivational_1' => $value, 'motivational_2' => $value, 'motivational_3' => $value,
            'social_1' => $value, 'social_2' => $value, 'social_3' => $value,
        ];
    }

    public function test_resource_matching_integration_rules()
    {
        $user = User::factory()->create(['semester' => 4, 'major' => 'Informatika']);

        // 1. Active & Eligible -> Should match
        $activeResource = Resource::factory()->create([
            'is_active' => true,
            'min_semester' => 1,
            'max_semester' => 8,
            'target_dimensions' => ['academic'],
            'eligible_majors' => ['Informatika']
        ]);

        // 2. Expired -> Should NOT match
        $expiredResource = Resource::factory()->create([
            'deadline' => now()->subDays(1)->format('Y-m-d'),
            'target_dimensions' => ['academic'],
        ]);

        // 3. Inactive -> Should NOT match
        $inactiveResource = Resource::factory()->create([
            'is_active' => false,
            'target_dimensions' => ['academic'],
        ]);

        // 4. Wrong semester -> Should NOT match
        $wrongSemesterResource = Resource::factory()->create([
            'min_semester' => 7,
            'max_semester' => 8,
            'target_dimensions' => ['academic'],
        ]);

        // 5. Wrong major -> Should NOT match
        $wrongMajorResource = Resource::factory()->create([
            'eligible_majors' => ['Sistem Informasi'],
            'target_dimensions' => ['academic'],
        ]);

        // Perform Check-In
        $this->actingAs($user)->post('/check-in', [
            'answers' => $this->getValidAnswers()
        ]);

        $assessment = Assessment::first();

        // Assert matches
        $this->assertDatabaseHas('resource_matches', [
            'assessment_id' => $assessment->id,
            'resource_id' => $activeResource->id,
        ]);

        $this->assertDatabaseMissing('resource_matches', [
            'assessment_id' => $assessment->id,
            'resource_id' => $expiredResource->id,
        ]);

        $this->assertDatabaseMissing('resource_matches', [
            'assessment_id' => $assessment->id,
            'resource_id' => $inactiveResource->id,
        ]);

        $this->assertDatabaseMissing('resource_matches', [
            'assessment_id' => $assessment->id,
            'resource_id' => $wrongSemesterResource->id,
        ]);

        $this->assertDatabaseMissing('resource_matches', [
            'assessment_id' => $assessment->id,
            'resource_id' => $wrongMajorResource->id,
        ]);
    }

    public function test_resource_matching_limits_to_maximum_5()
    {
        $user = User::factory()->create(['semester' => 4, 'major' => 'Informatika']);

        // Create 10 eligible resources
        Resource::factory()->count(10)->create([
            'is_active' => true,
            'min_semester' => 1,
            'max_semester' => 8,
            'target_dimensions' => ['academic'],
        ]);

        $this->actingAs($user)->post('/check-in', [
            'answers' => $this->getValidAnswers() // Scores 40 (Berkembang) on all dimensions
        ]);

        $assessment = Assessment::first();

        // Assert exactly 5 matches were created
        $this->assertDatabaseCount('resource_matches', 5);
        $this->assertCount(5, $assessment->matches);
    }
}
