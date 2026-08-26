<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Assessment;
use App\Models\AssessmentChat;
use App\Models\Note;
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

        AssessmentChat::create([
            'assessment_id' => $assessment->id,
            'role' => 'user',
            'message' => 'Hello',
        ]);
        
        $note = $user->notes()->create([
            'title' => 'My Note',
            'content' => 'Test'
        ]);

        $this->actingAs($user)
            ->delete('/account')
            ->assertRedirect('/');

        $this->assertGuest();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('assessments', ['id' => $assessment->id]);
        $this->assertDatabaseMissing('assessment_chats', ['assessment_id' => $assessment->id]);
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }
}
