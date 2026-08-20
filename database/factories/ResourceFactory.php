<?php

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['scholarship', 'counseling', 'academic_support', 'community', 'career']),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'provider_name' => $this->faker->company(),
            'url' => $this->faker->url(),
            'contact_info' => $this->faker->email(),
            'deadline' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'target_dimensions' => [$this->faker->randomElement(['academic', 'financial', 'motivational', 'social'])],
            'min_semester' => 1,
            'max_semester' => 8,
            'eligible_majors' => null,
            'is_active' => true,
        ];
    }
}
