<?php

namespace Database\Factories;

use App\Models\Voter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Voter>
 */
class VoterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'identifier' => fake()->unique()->numerify('######'),
            'name' => fake()->name(),
            'type' => fake()->randomElement(['SISWA', 'GURU']),
        ];
    }

    /**
     * State untuk Siswa
     */
    public function siswa(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'SISWA',
        ]);
    }

    /**
     * State untuk Guru
     */
    public function guru(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'GURU',
        ]);
    }


}
