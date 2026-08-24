<?php

namespace Tests\Feature\Workspace;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Note;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_create_note()
    {
        $user = User::factory()->create(['role' => 'student']);
        
        $response = $this->actingAs($user)->post(route('workspace.notes.store'), [
            'title' => 'My First Note',
            'content' => 'This is a test note.',
            'course_name' => 'Math'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('notes', [
            'user_id' => $user->id,
            'title' => 'My First Note'
        ]);
    }

    public function test_student_cannot_access_others_note()
    {
        $owner = User::factory()->create(['role' => 'student']);
        $other = User::factory()->create(['role' => 'student']);
        
        $note = $owner->notes()->create([
            'title' => 'Secret Note',
            'content' => 'Content',
            'course_name' => 'Math'
        ]);

        $response = $this->actingAs($other)->get(route('workspace.notes.show', $note));
        
        $response->assertStatus(403);
    }
}
