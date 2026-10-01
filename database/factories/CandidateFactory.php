<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Candidate>
 */
class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'candidate_number' => 1,
            'name' => fake()->name(),
            'vision' => fake()->sentence(),
            'mission' => fake()->paragraph(),
            'description' => fake()->sentence(),
            'photo' => null,
        ];
    }
}
