<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_by' => User::factory(),
            'title' => fake()->sentence(4),
            'category' => fake()->randomElement(['KANDIDAT', 'OPSI']),
            'room_code' => strtoupper(Str::random(6)),
            'description' => fake()->paragraph(),
            'start_time' => now(),
            'end_time' => now()->addDays(2),
            'status' => 'DRAFT',
        ];
    }
}
