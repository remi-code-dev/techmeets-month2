<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'guests' => fake()->numberBetween(1, 3),
            'reserved_at' => fake()->dateTimeBetween('+1 day', '+1 month'),
        ];
    }
}
