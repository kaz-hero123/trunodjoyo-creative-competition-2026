<?php

namespace Tests\Feature\AI;

use App\Models\Assessment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AssessmentChatTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Assessment $assessment;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->assessment = Assessment::create([
            'user_id' => $this->user->id,
            'raw_answers' => [], 'score_academic' => 30, 'score_financial' => 80, 'score_motivational' => 80, 'score_social' => 80, 'total_resilience_score' => 67.5,
        ]);
    }

    public function test_successful_gemini_response_saves_chat()
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => json_encode(['advisor_response' => 'Ini saran untuk Anda.'])]
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->actingAs($this->user)->post(route('results.chat', $this->assessment), [
            'message' => 'Saya kesulitan belajar.'
        ]);

        $response->assertRedirect(route('results.show', $this->assessment));

        $this->assertDatabaseHas('assessment_chats', [
            'assessment_id' => $this->assessment->id,
            'role' => 'user',
            'message' => 'Saya kesulitan belajar.',
        ]);

        $this->assertDatabaseHas('assessment_chats', [
            'assessment_id' => $this->assessment->id,
            'role' => 'ai',
            'message' => 'Ini saran untuk Anda.',
        ]);
    }

    public function test_invalid_json_returns_fallback_response()
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Bukan JSON valid']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $this->actingAs($this->user)->post(route('results.chat', $this->assessment), [
            'message' => 'Halo'
        ]);

        $this->assertDatabaseHas('assessment_chats', [
            'assessment_id' => $this->assessment->id,
            'role' => 'ai',
            'message' => 'Terima kasih sudah berbagi. Sistem saya sedang sibuk, namun tetap perhatikan daftar bantuan kampus di bawah yang mungkin bisa membantumu.',
        ]);
    }

    public function test_api_timeout_or_error_returns_fallback_response()
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([], 500)
        ]);

        $this->actingAs($this->user)->post(route('results.chat', $this->assessment), [
            'message' => 'Halo'
        ]);

        $this->assertDatabaseHas('assessment_chats', [
            'assessment_id' => $this->assessment->id,
            'role' => 'ai',
            'message' => 'Terima kasih sudah berbagi. Sistem saya sedang sibuk, namun tetap perhatikan daftar bantuan kampus di bawah yang mungkin bisa membantumu.',
        ]);
    }

    public function test_privacy_sanitization_does_not_send_personal_identifiers()
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => function (\Illuminate\Http\Client\Request $request) {
                $body = $request->body();
                
                // Assert that the request to Gemini does NOT contain the user's name or email
                $this->assertStringNotContainsString($this->user->name, $body);
                $this->assertStringNotContainsString($this->user->email, $body);
                
                return Http::response([
                    'candidates' => [['content' => ['parts' => [['text' => '{"advisor_response": "ok"}']]]]]
                ], 200);
            }
        ]);

        $this->actingAs($this->user)->post(route('results.chat', $this->assessment), [
            'message' => 'Saya stres.'
        ]);

        // The assertion inside Http::fake will fail the test if the condition is not met
        $this->assertTrue(true);
    }
}
