<?php

namespace Tests\Feature\Workspace;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class StreakTest extends TestCase
{
    use RefreshDatabase;

    public function test_streak_increments_on_consecutive_days()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'streak_count' => 1,
            'last_active_at' => Carbon::yesterday()
        ]);

        $this->actingAs($user)->get(route('workspace.notes.index'));

        $this->assertEquals(2, $user->fresh()->streak_count);
        $this->assertTrue(Carbon::today()->isSameDay($user->fresh()->last_active_at));
    }

    public function test_streak_resets_after_missing_a_day()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'streak_count' => 5,
            'last_active_at' => Carbon::now()->subDays(2)
        ]);

        $this->actingAs($user)->get(route('workspace.notes.index'));

        $this->assertEquals(1, $user->fresh()->streak_count);
    }
}
