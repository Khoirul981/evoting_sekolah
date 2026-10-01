<?php

namespace Database\Factories;

use App\Models\Voter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Voter>
 */
class VoterFactory extends Factory
{
    protected $model = Voter::class;

    public function definition(): array
    {
        return [
            'identifier' => (string) fake()->unique()->numerify('#####'),
            'name' => fake()->name(),
            'type' => fake()->randomElement(['SISWA', 'GURU']),
            'is_active' => true,
        ];
    }
}
