<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Demo Student',
            'email' => 'student@demo.com',
            'password' => bcrypt('password'),
            'semester' => 4,
            'faculty' => 'Fakultas Teknik',
            'major' => 'Informatika',
        ]);

        \App\Models\Resource::factory()->count(20)->create();
    }
}
