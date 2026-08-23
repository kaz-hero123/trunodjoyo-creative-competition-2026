<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Assessment;
use App\Models\AssessmentChat;
use App\Models\Resource;
use App\Models\ResourceMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_deletion_cascades_and_logs_out(): void
    {
        $user = User::factory()->create();

        $assessment = Assessment::create([
            'user_id' => $user->id,
            'raw_answers' => [],
            'score_academic' => 10,
            'score_financial' => 10,
            'score_motivational' => 10,
            'score_social' => 10,
            'total_resilience_score' => 10,
        ]);

        $resource = Resource::create([
            'title' => 'Test',
            'type' => 'scholarship',
            'description' => 'Test',
            'provider_name' => 'Test',
            'target_dimensions' => ['academic'],
        ]);

        ResourceMatch::create([
            'assessment_id' => $assessment->id,
            'resource_id' => $resource->id,
        ]);

        AssessmentChat::create([
            'assessment_id' => $assessment->id,
            'role' => 'user',
            'message' => 'Hello',
        ]);

        $this->actingAs($user)
            ->delete('/account')
            ->assertRedirect('/');

        $this->assertGuest();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('assessments', ['id' => $assessment->id]);
        $this->assertDatabaseMissing('resource_matches', ['assessment_id' => $assessment->id]);
        $this->assertDatabaseMissing('assessment_chats', ['assessment_id' => $assessment->id]);
    }
}
