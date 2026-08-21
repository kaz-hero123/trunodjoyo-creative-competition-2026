<?php

namespace Tests\Feature\Assessment;

use App\Models\Assessment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private function getValidAnswers($value = 3): array
    {
        return [
            'academic_1' => $value, 'academic_2' => $value, 'academic_3' => $value,
            'financial_1' => $value, 'financial_2' => $value, 'financial_3' => $value,
            'motivational_1' => $value, 'motivational_2' => $value, 'motivational_3' => $value,
            'social_1' => $value, 'social_2' => $value, 'social_3' => $value,
        ];
    }

    public function test_valid_submission_creates_assessment_and_redirects()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/check-in', [
            'answers' => $this->getValidAnswers()
        ]);

        $this->assertDatabaseCount('assessments', 1);
        $assessment = Assessment::first();

        $response->assertRedirect(route('results.show', $assessment));
        $this->assertEquals($user->id, $assessment->user_id);
    }

    public function test_invalid_submission_missing_answers()
    {
        $user = User::factory()->create();
        $answers = $this->getValidAnswers();
        unset($answers['academic_1']); // missing one answer

        $response = $this->actingAs($user)->post('/check-in', [
            'answers' => $answers
        ]);

        $response->assertSessionHasErrors(['answers']);
        $this->assertDatabaseCount('assessments', 0);
    }

    public function test_invalid_submission_extra_key()
    {
        $user = User::factory()->create();
        $answers = $this->getValidAnswers();
        $answers['extra_key'] = 3;

        $response = $this->actingAs($user)->post('/check-in', [
            'answers' => $answers
        ]);

        $response->assertSessionHasErrors(['answers']); // due to exact size:12
        $this->assertDatabaseCount('assessments', 0);
    }

    public function test_invalid_submission_invalid_value()
    {
        $user = User::factory()->create();
        
        $invalidValues = [0, 6, null];

        foreach ($invalidValues as $invalidValue) {
            $answers = $this->getValidAnswers();
            $answers['academic_1'] = $invalidValue;

            $response = $this->actingAs($user)->post('/check-in', [
                'answers' => $answers
            ]);

            $response->assertSessionHasErrors();
        }

        $this->assertDatabaseCount('assessments', 0);
    }

    public function test_scoring_boundaries_integration()
    {
        $user = User::factory()->create();

        $scenarios = [
            1 => ['score' => 20.0, 'status' => 'Perlu Perhatian'],
            2 => ['score' => 40.0, 'status' => 'Berkembang'],
            3 => ['score' => 60.0, 'status' => 'Berkembang'],
            4 => ['score' => 80.0, 'status' => 'Kuat'],
            5 => ['score' => 100.0, 'status' => 'Kuat'],
        ];

        foreach ($scenarios as $answerValue => $expected) {
            $response = $this->actingAs($user)->post('/check-in', [
                'answers' => $this->getValidAnswers($answerValue)
            ]);
            
            $assessment = $user->assessments()->latest()->first();

            $this->assertEquals($expected['score'], $assessment->total_resilience_score);
            $this->assertEquals($expected['score'], $assessment->score_academic);
            $this->assertEquals($expected['status'], $assessment->dimensionStatus('academic'));
            
            // Fast forward 15 days to bypass cooldown for the next loop
            $this->travel(15)->days();
        }
    }

    public function test_rate_limiting_redirects_with_flash_message()
    {
        $user = User::factory()->create();

        // 3 requests to exhaust the limit
        for ($i = 0; $i < 3; $i++) {
            $this->actingAs($user)->post(route('check-in.store'), [
                'answers' => $this->getValidAnswers()
            ]);
        }

        // 4th request should be throttled
        $response = $this->actingAs($user)->post(route('check-in.store'), [
            'answers' => $this->getValidAnswers()
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Permintaan Anda sedang diproses, mohon tunggu sebentar sebelum mencoba lagi.');
    }
}
