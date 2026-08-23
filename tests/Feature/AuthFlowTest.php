<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@demo.com',
            'password' => 'password123',
            'semester' => 2,
            'faculty' => 'Fakultas Teknik',
            'major' => 'Informatika',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'john@demo.com', 'name' => 'John Doe']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'jane@demo.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'jane@demo.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
