<?php

namespace Tests\Feature\Assessment;

use App\Models\Assessment;
use App\Models\Resource;
use App\Models\ResourceMatch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_view_another_students_assessment()
    {
        $studentA = User::factory()->create();
        $studentB = User::factory()->create();

        $assessmentB = Assessment::create([
            'user_id' => $studentB->id,
            'raw_answers' => [], 'score_academic' => 10, 'score_financial' => 10, 'score_motivational' => 10, 'score_social' => 10, 'total_resilience_score' => 10,
        ]);

        $response = $this->actingAs($studentA)->get(route('results.show', $assessmentB));
        
        $response->assertStatus(403);
    }

    public function test_student_cannot_chat_on_another_students_assessment()
    {
        $studentA = User::factory()->create();
        $studentB = User::factory()->create();

        $assessmentB = Assessment::create([
            'user_id' => $studentB->id,
            'raw_answers' => [], 'score_academic' => 10, 'score_financial' => 10, 'score_motivational' => 10, 'score_social' => 10, 'total_resilience_score' => 10,
        ]);

        $response = $this->actingAs($studentA)->post(route('results.chat', $assessmentB), [
            'message' => 'Hello'
        ]);
        
        $response->assertStatus(403);
        $this->assertDatabaseCount('assessment_chats', 0);
    }

    public function test_student_cannot_click_another_students_resource_match()
    {
        $studentA = User::factory()->create();
        $studentB = User::factory()->create();

        $assessmentB = Assessment::create([
            'user_id' => $studentB->id,
            'raw_answers' => [], 'score_academic' => 10, 'score_financial' => 10, 'score_motivational' => 10, 'score_social' => 10, 'total_resilience_score' => 10,
        ]);

        $resource = Resource::factory()->create();
        
        $matchB = ResourceMatch::create([
            'assessment_id' => $assessmentB->id,
            'resource_id' => $resource->id,
            'match_reason' => 'Test',
        ]);

        $response = $this->actingAs($studentA)->post(route('resources.click', $matchB));
        
        $response->assertStatus(403);
        
        $this->assertFalse($matchB->fresh()->is_clicked);
    }
}
