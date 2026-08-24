<?php

namespace Tests\Feature\Workspace;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class FlashcardTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_create_flashcard_deck()
    {
        $user = User::factory()->create(['role' => 'student']);
        
        $response = $this->actingAs($user)->post(route('workspace.flashcard-decks.store'), [
            'name' => 'My Deck'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('flashcard_decks', [
            'user_id' => $user->id,
            'name' => 'My Deck'
        ]);
    }

    public function test_student_can_add_card_to_deck()
    {
        $user = User::factory()->create(['role' => 'student']);
        $deck = $user->flashcardDecks()->create(['name' => 'My Deck']);
        
        $response = $this->actingAs($user)->post(route('workspace.flashcard-decks.cards.store', $deck), [
            'question' => 'Q1',
            'answer' => 'A1'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('flashcards', [
            'flashcard_deck_id' => $deck->id,
            'question' => 'Q1'
        ]);
    }
}
