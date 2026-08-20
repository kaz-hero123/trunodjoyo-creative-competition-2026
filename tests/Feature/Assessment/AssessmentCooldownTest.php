<?php

namespace Tests\Feature\Assessment;

use App\Models\Assessment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentCooldownTest extends TestCase
{
    use RefreshDatabase;

    private function getValidAnswers(): array
    {
        return [
            'academic_1' => 3, 'academic_2' => 3, 'academic_3' => 3,
            'financial_1' => 3, 'financial_2' => 3, 'financial_3' => 3,
            'motivational_1' => 3, 'motivational_2' => 3, 'motivational_3' => 3,
            'social_1' => 3, 'social_2' => 3, 'social_3' => 3,
        ];
    }

    public function test_first_assessment_is_allowed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/check-in', [
            'answers' => $this->getValidAnswers()
        ]);

        $this->assertDatabaseCount('assessments', 1);
        $response->assertRedirect();
    }

    public function test_second_assessment_before_14_days_is_rejected()
    {
        $user = User::factory()->create();

        // First assessment
        $this->actingAs($user)->post('/check-in', [
            'answers' => $this->getValidAnswers()
        ]);

        $this->travel(13)->days();

        // Second assessment
        $response = $this->actingAs($user)->post('/check-in', [
            'answers' => $this->getValidAnswers()
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('assessments', 1); // No new assessment
    }

    public function test_assessment_exactly_at_cooldown_boundary()
    {
        $user = User::factory()->create();

        // Create first assessment directly
        $assessment = Assessment::create([
            'user_id' => $user->id,
            'raw_answers' => $this->getValidAnswers(),
            'score_academic' => 10, 'score_financial' => 10, 'score_motivational' => 10, 'score_social' => 10, 'total_resilience_score' => 10,
            'created_at' => now()->subDays(14)
        ]);

        // Attempt new assessment exactly 14 days later
        $response = $this->actingAs($user)->post('/check-in', [
            'answers' => $this->getValidAnswers()
        ]);

        // Should be allowed because diffInDays(now()) < 14 returns false when it's exactly 14 or more
        $this->assertDatabaseCount('assessments', 2);
    }

    public function test_assessment_after_cooldown_is_allowed()
    {
        $user = User::factory()->create();

        Assessment::create([
            'user_id' => $user->id,
            'raw_answers' => $this->getValidAnswers(),
            'score_academic' => 10, 'score_financial' => 10, 'score_motivational' => 10, 'score_social' => 10, 'total_resilience_score' => 10,
            'created_at' => now()->subDays(15)
        ]);

        $response = $this->actingAs($user)->post('/check-in', [
            'answers' => $this->getValidAnswers()
        ]);

        $this->assertDatabaseCount('assessments', 2);
    }

    public function test_existing_assessment_remains_usable_during_cooldown()
    {
        $user = User::factory()->create();

        $assessment = Assessment::create([
            'user_id' => $user->id,
            'raw_answers' => $this->getValidAnswers(),
            'score_academic' => 10, 'score_financial' => 10, 'score_motivational' => 10, 'score_social' => 10, 'total_resilience_score' => 10,
            'created_at' => now()->subDays(5) // In cooldown
        ]);

        // Try to access the results page of the existing assessment
        $response = $this->actingAs($user)->get(route('results.show', $assessment));
        $response->assertStatus(200);

        // Assuming AssessmentChat is mocked or skipped, we just verify the endpoint accepts chat requests
        $response = $this->actingAs($user)->post(route('results.chat', $assessment), [
            'message' => 'Hello AI'
        ]);

        // Should not be forbidden, even if cooldown is active
        $response->assertRedirect(route('results.show', $assessment));
    }
}
