<?php

namespace Tests\Feature\Workspace;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_quiz()
    {
        $user = User::factory()->create(['role' => 'student']);
        
        $quiz = $user->quizzes()->create([
            'title' => 'Test Quiz',
            'total_questions' => 1
        ]);
        
        $question = $quiz->questions()->create([
            'question' => 'Q1',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'a'
        ]);

        $response = $this->actingAs($user)->post(route('workspace.quizzes.submit', $quiz), [
            'answers' => [
                $question->id => 'a'
            ]
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'correct_count' => 1,
            'score' => 100
        ]);
        
        $this->assertNotNull($quiz->fresh()->completed_at);
    }
}
